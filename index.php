<?php
require_once ('slim/Slim.php');
require_once ('slim/SmartyView.php');
require_once ('classes/app.php');

$app = new Slim(array(
    'templates.path' => 'templates',
    'view'=>'SmartyView',
    'log.path' => 'slim/Logs',
    'log.level' => 4,
    'cookies.secret_key' => "[SALT]",
    'mode' => 'development',
));

$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => false
    ));
});

$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug'=>true
    ));
});

//# Set your API_Key and Client secret                #//
//#   Available here: https://eventbrite.com/api/key  #//
$app->config('api_key', 'YOUR_API_KEY_HERE');
$app->config('client_secret', 'YOUR_CLIENT_SECRET_HERE');

function authenticate() {
    $app = Slim::getInstance();
    if (!App::user()) {
        $app->redirect("/connect/");
    }
}

//## ROUTES ##//
$app->get('/', function () use ($app) {
    $params = App::start();
    $params['title'] = "Home";
    $params['page'] = "home";

    if(!App::user())
        $params['button'] = "Connect";
    else
        $params['button'] = "Logout";

    $params['errors'] = array();

    $app->render('static.tpl', $params);
});

$app->map('/connect/', function () use ($app) {
    $params = App::start();
    $params['title'] = "Connect";
    $params['page'] = "connect";
    $params['errors'] = array();

    # This user already has an active session
    if( App::user() ){
        $user = App::getUser();
        $params['user_email'] = $user->email; 
        $params['user_name'] = $user->first_name . ' ' . $user->last_name;         
        $params['button'] = "Logout";
    }else{
        # This user is not yet authenticated 
        #  (it is their first visit, or they were redirected here after logout)
        if( $app->request()->get('code') ){
            # This user has just authenticated, get their access token and store it
            $connect = App::connect( $app->request()->get('code') );
            if(array_key_exists('error', $connect)){
                $params['errors'][] = $connect['error'];
            }else if(array_key_exists('access_token', $connect)){
                App::begin($connect['access_token'], 1);
                $user = App::getUser( $connect['access_token'] );
                $params['user_email'] = $user->email; 
                $params['user_name'] = $user->first_name . ' ' . $user->last_name;         
                $params['button'] = "Logout";
            }
        }else if( $app->request()->get('error') == 'access_denied' ){
            $params['errors'][] = "Access Denied";
        }
    }

    $params['oauth_link'] = App::getOauthLink(); 
    $app->render('app.tpl', $params);
})->via('GET', 'POST');

$app->get('/logout/', function () use ($app) {
    App::end();
    $app->redirect("/");
});

$app->get('/events/', 'authenticate', function () use ($app){
    $params = App::start();
    $params['title'] = "Events";
    $params['page'] = "events";
    $params['button'] = "Logout";
    $params['errors'] = array();

    try{
      $params['events'] = App::client()->user_list_events(array('event_statuses' => 'live,started'))->events;
    }catch( Exception $e){
      $params['events'] = array();
    }
    $app->render('app.tpl', $params);
});

$app->map('/event/:id/(:tool)', 'authenticate', function ($id, $tool = null) use ($app) {
    $params = App::start();
    $params['title'] = "Tools";
    $params['button'] = "Logout";
    $params['page'] = "tools";
    $tools = App::tools();

    if(!is_null($tool)) {
        if(!in_array($tool, $tools)) $app->redirect("/event/$id/");
        $params['page'] = "tools/$tool";
    }
    else {
        $params['tools'] = $tools;
    }

    if($app->request()->isPost()) {
        $post = $app->request()->post();
        if($tool == "random") {
            if(isset($post['count']) && !is_null($post['count']) && trim($post['count']) != '' && is_numeric($post['count'])) {
                $attendees = App::attendeesForEvent($id);
                $params['attendees'] = $attendees;
                $params['count'] = ($post['count'] < count($attendees)) ? $post['count'] : count($attendees);
            }
        }
        else if($tool == "teams") {
            if( !isset($post['teams']) || trim($post['teams']) == "" || $post['teams'] == 0 || $post['teams'] == "0" || !is_numeric($post['teams'])){ 
                $teams = "?";
            }else{
                $teams = $post['teams'];
            }
            if( !isset($post['of']) || trim($post['of']) == "" || $post['of'] == 0 || $post['of'] == "0" || !is_numeric($post['of'])) {
                $of = "?";
            }else{
                $of = $post['of'];
            }

            if($teams == "?" && $of == "?") {
                $params['teams'] = App::teamsForEvent($id, "2", "?");
            }else{
                $params['teams'] = App::teamsForEvent($id, $teams, $of);
            }
        }
    }

    $params['id'] = $id;
    $params['errors'] = array();
    $app->render('app.tpl', $params);
})->via('GET', 'POST');

$app->get('/error/(:code)/', function ($code) use ($app) {
    if(isset($code) && $code != null)
    $app->render('error.tpl', array('code'=>$code));
    else
    $app->render('error.tpl');
});

$app->notFound(function () use ($app) {
    $app->redirect("/error/404/");
});

$app->error(function ( Exception $e ) use ($app) {
    $app->notFound();
});

$app->run();

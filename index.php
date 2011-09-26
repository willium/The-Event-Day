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

$app->config('app', '[APP ID]');

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
    $params['button'] = "Connect";
    $params['errors'] = array();
    if($app->request()->isPost()) {
        $post = $app->request()->post();
        $connect = App::connect($post['user'], $post['password']);
        if($connect['success']) {
            $params['key'] = $connect['key'];
            App::begin($connect['key'], 1);
            $app->redirect("/events/");
        }
        else{
            $params['errors'][] = $connect['message'];
        }
    }
    $app->render('app.tpl', $params);
})->via('GET', 'POST');

$app->get('/logout/', function () use ($app) {
    App::end();
    $return = $app->request()->get('return');
    if($return == null)
    $app->redirect("/");
    else
    $app->redirect($return);
});

$app->get('/events/', 'authenticate', function () use ($app){
    $params = App::start();
    $params['title'] = "Events";
    $params['page'] = "events";
    $params['button'] = "Logout";
    $params['errors'] = array();

    $params['events'] = App::client()->user_list_events();

    $app->render('app.tpl', $params);
});

$app->map('/event/:id/(:tool)', 'authenticate', function ($id, $tool = null) use ($app) {
    $params = App::start();
    $params['title'] = "Tools";
    
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
                $params['count'] = $post['count'];
                $params['attendees'] = App::attendeesForEvent($id);
            }
        }
        else if($tool == "teams") {
            if($post['teams'] == "" || $post['teams'] == 0) $post['teams'] = "?";
            if($post['of'] == "" || $post['of'] == 0) $post['of'] = "?";

            $_teams = isset($post['teams']) && !is_null($post['teams']) && trim($post['teams']) != '' && (is_numeric($post['teams']) || trim($post['teams']) == "?");
            $_of = isset($post['of']) && !is_null($post['of']) && trim($post['of']) != '' && (is_numeric($post['of']) || trim($post['of']) == "?");
            $_both = trim($post['of']) == "?" && trim($post['teams']) == "?";
            if($_teams && $_of && !$_both) {
                $params['teams'] = App::teamsForEvent($id, $post['teams'], $post['of']);
            }
        }
    }

    $params['button'] = "Logout";
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
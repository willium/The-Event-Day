<?php
require('eventbrite.php');
class App {
	public static function tools() {
		return array(
        "Random"=>"random",
        "Team Maker"=>"teams"
    	);
	}

	public static function start() {
		return array("logged_in"=>!!self::user());
	}

	public static function attendeesForEvent($id) {
		try {
		    $attendees = self::client()->event_list_attendees(array(
		        "id" => $id,
		        "count" => 1000000000,
		        "show_full_barcodes" => true
  		    ))->attendees;
		    shuffle($attendees);
            return $attendees;
        }catch(Exception $e){
            return array();
        }
	}

	public static function teamsForEvent($id, $team_count, $of_count) {
		$attendees = self::attendeesForEvent($id);
        if(is_array($attendees) && count($attendees) == 0){
            return false;
        }

		if($team_count == "?") {
			return array_chunk($attendees, $of_count);
		}
		else if($of_count == "?") {
		    $diff = round( count($attendees) / $team_count );
			return array_chunk($attendees, $diff);
		}
		else if($of_count !== "?" && $team_count !== "?"){
		    $total_players = $team_count * $of_count;
			return array_chunk(array_slice($attendees, 0, $total_players), $of_count);
		}
        else{
            return array($attendees);
        }
	}

	public static function begin($key, $remember = 0) {
		$app = Slim::getInstance();

		$length = 60*60*24;
	    if($remember == 1) $length = 60*60*24*30;

		$app->setEncryptedCookie('key', $key, time()+$length);
	}

	public static function connect($access_code) {
		$resp = array();
		$app = Slim::getInstance();
		try {
			$eb = new Eventbrite(array(
				'access_code'   => $access_code,
				'app_key'       => $app->config('api_key'),
                'client_secret' => $app->config('client_secret')));
            $resp['access_token'] = $eb->auth_tokens['access_token'];
		} catch (Exception $e) { 
			$resp['error'] = $e->getMessage();
		}
		return $resp;
	}

	public static function end($length = 2592000) {
		$app = Slim::getInstance();
		$app->setEncryptedCookie('key', null, time()-$length);
	}

	public static function client($access_token=false) {
		$app = Slim::getInstance();
        if($access_token !== false){
            return new Eventbrite(array( 'access_token' => $access_token));
        }else if(self::user()){ 
            return new Eventbrite(array( 'access_token' => self::user()));
        }
        return new Eventbrite(array( 'app_key' => $app->config('api_key'))); 
	}

    public static function getOauthLink(){
		$app = Slim::getInstance();
        return Eventbrite::oauthNextStep($app->config('api_key'));
    }

	public static function getUser( $access_token=false) {
		return self::client( $access_token )->user_get()->user;
	}

	public static function user() {
		$app = Slim::getInstance();
		$key = $app->getEncryptedCookie('key');
		if(is_null($key)) return false;
		return $key;
	}
}

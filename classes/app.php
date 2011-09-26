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
		$attendees = self::client()->event_list_attendees(array(
		    "id" => $id,
		    "count" => 1000000000,
		    "show_full_barcodes" => true
		))->attendees;
		shuffle($attendees);
		return $attendees;
	}

	public static function teamsForEvent($id, $team_count, $of_count) {
		$attendees = self::attendeesForEvent($id);
		$both = $team_count * $of_count;
		$diff = count($attendees)/$team_count;

		if($team_count == "?") {
			return array_chunk($attendees, $of_count);
		}
		else if($of_count == "?") {
			return array_chunk($attendees, $diff);
		}
		else {
			return array_chunk(array_slice($attendees, 0, $both), $of_count);
		}
	}

	public static function begin($key, $remember = 0) {
		$app = Slim::getInstance();

		$length = 60*60*24;
	    if($remember == 1) $length = 60*60*24*30;

		$app->setEncryptedCookie('key', $key, time()+$length);
	}

	public static function connect($user, $password) {
		$resp = array();
		try {
			$app = Slim::getInstance();
			$_app = $app->config('app');
			$tokens = array(
				'user' => $user,
				'password' => $password,
				'app_key' => $_app
			);
			$e = new Eventbrite($tokens);
			if(!$e) throw new Exception("Incorrect Credentials!");

			$user = $e->user_get()->user;
			$key = $user->user_key;
			if(!$key) throw new Exception("Failed!");

			$e = null;
			unset($e);
			$resp['key'] = $key;
			$resp['success'] = true;
		} catch (Exception $e) { 
			$resp['message'] = $e->getMessage();
			$resp['success'] = false;
		}
		return $resp;
	}

	public static function end($length = 2592000) {
		$app = Slim::getInstance();
		$app->setEncryptedCookie('key', null, time()-$length);
	}

	public static function client($_key = null, $_app = null) {
		$app = Slim::getInstance();
		if(is_null($_app)) $_app = $app->config('app');
		if(is_null($_key)) $_key = self::user();

		$tokens = array(
			'app_key'  => $_app,
        	'user_key' => $_key
		);
		return new Eventbrite($tokens);
	}

	public static function user() {
		$app = Slim::getInstance();
		$key = $app->getEncryptedCookie('key');
		if(is_null($key)) return false;
		return $app->getEncryptedCookie('key');
	}
}
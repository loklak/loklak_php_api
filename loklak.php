<?php

// First, include Requests
include('./Requests/library/Requests.php');

// Next, make sure Requests can load internal classes
Requests::register_autoloader();

class Loklak {

	private $baseUrl;
	private $name;
	private $followers;
	private $following;
	private $query;
	private $since;
	private $until;
	private $source;
	private $count;
	private $fields;
	private $from_user;
	private $limit;
	private $action;
	private $data;
	private $requestURL;
	private $place;

	// Allow overloading of baseUrl for other Loklak Servers
	// Useful for private loklak servers and IoT devices running
	// PHP in the background tasks.
	/*
	 * Create a new instance.
	 *
	 * @params String $baseUrl
	 *
	 */
	function __construct($baseUrl='http://loklak.org') {
		$this->baseUrl = $baseUrl;
	}

	public function hello() {
		$this->requestURL = $this->baseUrl . '/api/hello.json';
		$request = Requests::get($this->requestURL, array('Accept' => 'application/json'));
		return json_encode($request);
	}

	public function peers() {
		$this->requestURL = $this->baseUrl . '/api/peers.json';
		$request = Requests::get($this->requestURL, array('Accept' => 'application/json'));
		return json_encode($request);
	}

	public function status() {
		$this->requestURL = $this->baseUrl . '/api/status.json';
		$request = Requests::get($this->requestURL, array('Accept' => 'application/json'));
		return json_encode($request, true);
	}

	public function search($query, $since=null, $until=null, $from_user=null, $count=null) {
        $this->requestURL = $this->baseUrl . '/api/search.json';
        $this->query = $query;
        $this->since = $since;
        $this->until = $until;
        $this->from_user = $from_user;
        $this->count = $count;
        if($query){
            $params = array('q'=>$this->query);
        	if ($since) {
            	$params['q'] = $params['q'] . ' since:'.$this->since;
        	}
        	if ($until) {
         		$params['q'] = $params['q'] . ' until:'.$this->until;
        	}
         	if ($from_user) {
         		$params['q'] = $params['q'] . ' from:'.$this->from_user;
         	}
         	if ($count) {
        		$params['count'] = $this->count;
         	}
            $request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
            if ($request->status_code == 200)
                return json_encode($request, true);
            else {
                $request = array();
                $error = "Looks like something is wrong. Request failed.";
                $request['error'] = array_push($request, $error);
                return json_encode($request, true);
            }
        }
        else {
            $request = array();
            $error = "Looks like something is wrong. Request failed.";
            $request['error'] = array_push($request, $error);
            return json_encode($request, true);
        }
    }

	public function geocode($place) {
		$this->place = $place;
		$this->requestURL = $this->baseUrl . '/api/geocode.json';
		$params = array('places'=>$this->place);
		$request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
		if ($request->status_code == 200)
			return json_encode($request, true);
		else {
			$request = array();
			return json_encode($request, true);
		}
	}

	public function user($name, $followers=null, $following=null) {
		$this->requestURL = $this->baseUrl . '/api/user.json';
		$this->name = $name;
		$this->followers = $followers;
		$this->following = $following;
		if($name) {
			$params = array('screen_name'=>$this->name);
			if($followers) {
				$params['screen_name'] = $params['screen_name'] . ' followers:'.$this->followers;
			}
			if($following) {
				$params['screen_name'] = $params['screen_name'] . ' following:'.$this->following;
			}
			$request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
			if ($request->status_code == 200)
				return json_encode($request, true);
			else {
				$request = array();
				return json_encode($request, true);
			}
		}
		else {
			$request = array();
			$error = "No user name given to query. Please check and try again";
			$request['error'] = array_push($request, $error);
			return json_encode($request, true);
		}
	}
}

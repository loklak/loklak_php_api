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

	public function search($query, $since=null, $until=null, $from_user=null, $count=null){
        $this->requestURL = $this->baseUrl . '/api/search.json';
        $this->query = $query;
        $this->since = $since;
        $this->until = $until;
        $this->from_user = $from_user;
        $this->count = $count;
        if($query){
            $params = array('q'=>$this->query);
            $params['query'] = $this->$query;
            if ($since)
            	$params['query'] = $params['query'] . ' since:'.$this->since;
         	if ($until)
         		$params['query'] = $params['query'] . ' until:'.$this->until;
         	if ($from_user)
         		$params['query'] = $params['query'] . ' from:'.$this->from_user;
         	if ($count)
        		$params['count'] = $this->count;
            $request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
            if ($request->status_code == 200)
                return json_encode($request, true);
            else{
                $request = array();
                $error = "Looks like something is wrong. Request failed.";
                $request['error'] = array_push($request, $error);
                return json_encode($request, true);
            }
        }
        else{
            $request = array();
            $error = "Looks like something is wrong. Request failed.";
            $request['error'] = array_push($request, $error);
            return json_encode($request, true);
        }
    }
}

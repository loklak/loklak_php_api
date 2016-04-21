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


}

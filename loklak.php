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

	public function account($name, $action=null, $data=null) {
		// This API is localhost access ONLY.
		$this->requestURL = 'http://localhost:9000/api/account.json';
		$this->name = $name;
		$this->action = $action;
		$this->data = json_encode($data);
		$headers = array();
		$headers['User-Agent'] = "Mozilla/5.0 (Android 4.4; Mobile; rv:41.0) Gecko/41.0 Firefox/41.0";
		$headers['From'] = "info@loklak.org";
		if ($name) {
			$params = array('screen_name'=>$this->name);
			$request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
			if ($request->status_code == 200) {
				return json_encode($request, true);
			}
			else {
				$request = array();
				$error = "Something went wrong in fetching user account information, looks like the query is wrong";
				$request['error'] = array_push($request, $error);
				return json_encode($request, true);
			}
		}
		elseif ($this->action == 'update' && $data) {
			$params = array('data'=>json_encode($data), 'action'=>$this->action);
			$request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
			if ($request->status_code == 200) {
				return json_encode($request, true);
			}
			else {
				$request = array();
				$error = "Something went wrong in updating, looks like the query is wrong";
				$request['error'] = array_push($request, $error);
				return json_encode($request, true);
			}
		}
		else {
			$request = array();
			$error = "Something went wrong, looks like the query is wrong.";
			$request['error'] = array_push($request, $error);
			return json_encode($request, true);
		}
	}

	public function search($query, $since=null, $until=null, $from_user=null, $count=null) {
		$this->requestURL = $this->baseUrl . '/api/search.json';
		$this->query = $query;
		$this->since = $since;
		$this->until = $until;
		$this->from_user = $from_user;
		$this->count = $count;
		if($query) {
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

	public function settings() {
		$this->requestURL = 'http://localhost:9000/api/settings.json';
		$request = Requests::get($this->requestURL, array('Accept' => 'application/json'));
		if ($request->status_code == 200)
			return json_encode($request, true);
		else {
			$request = array();
			$error = "This API has access restrictions: only localhost clients are granted.";
			$request['error'] = array_push($request, $error);
			return json_encode($request, true);
		}
	}

	public function suggest($query="", $count=null, $order=null, $orderby=null, $since=null, $until=null) {
		$this->requestURL = $this->baseUrl . '/api/suggest.json';
		$this->query = $query;
		$this->count = $count;
		$this->order = $order;
		$this->orderby = $orderby;
		$this->since = $since;
		$this->until = $until;
		if ($query) {
			$params = array('q'=>$this->query);
		}
		if ($count) {
			$params['count'] = $this->count;
		}
		if ($order) {
			$params['order'] = $this->order;
		}
		if ($orderby) {
			$params['orderby'] = $this->orderby;
		}
		if ($since) {
			$params['since'] = $this->since;
		}
		if ($until) {
			$params['until'] = $this->until;
		}
		$request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
		if ($request->status_code == 200)
			return json_encode($request, true);
		else {
			$request = array();
			$error = "Something went wrong, looks like the server is down.";
			$request['error'] = array_push($request, $error);
			return json_encode($request, true);
		}
	}

	public function aggregations($query="", $since=null, $until=null, $fields=null, $limit=6, $count=0) {
		$this->requestURL = $this->baseUrl . '/api/search.json';
		$this->query = $query;
		$this->since = $since;
		$this->until = $until;
		$this->fields = $fields;
		$this->limit = $limit;
		$this->count = $count;
		if ($query) {
			$params = array('q'=>$this->query);
			if ($since) {
				$params['q'] = $params['q'] . ' since:'.$this->since;
			}
			if ($until) {
				$params['q'] = $params['q'] . ' until:'.$this->until;
			}
			if ($fields) {
				if (is_array($fields)) {
					$params['fields'] = implode(',', $this->fields);
				}
				else {
					$params['fields'] = $this->fields;
				}
			}
			$params['limit'] = $this->limit;
			$params['count'] = $this->count;
			$params['source'] = "cache";
			$request = Requests::request($this->requestURL, array('Accept' => 'application.json'), $params);
			if ($request->status_code == 200)
				return json_encode($request, true);
			else {
				$request = array();
				$error = "Something went wrong, looks like the server is down.";
				$request['error'] = array_push($request, $error);
				return json_encode($request, true);
			}
		}
		else {
			$request = array();
			$error = "No Query string has been given to run an aggregation query for";
			$request['error'] = array_push($request, $error);
			return json_encode($request, true);
		}
	}
}

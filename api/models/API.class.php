<?php
abstract class API
{
	/**
	 * The HTTP method this request was made in, either GET, POST, PUT or DELETE
	 */
	protected $method = '';
	
	/**
	 * The Model requested in the URI. eg: /files
	 */
	protected $endpoint = '';
	
	/**
	 * An optional additional descriptor about the endpoint, used for things that can
	 * not be handled by the basic methods. eg: /files/process
	 */
	protected $verb = '';
	
	/**
	 * Any additional URI components after the endpoint and verb have been removed, in our
	 * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
	 * or /<endpoint>/<arg0>
	 */
	protected $args = Array();
	
	/**
	 * Stores the input of the PUT and POST request
	 */
	protected $file = Null;
	
	/**
         * Stores the response codes for the requests
         */
	protected $response = Null;

	/**
	 * Constructor: __construct
	 * Allow for CORS, assemble and pre-process the data
	 */
	public function __construct($request) {
		header("Access-Control-Allow-Orgin: *");
		header("Access-Control-Allow-Methods: *");
		header("Content-Type: application/json");

		$this->args = explode('/', rtrim($request, '/'));
		$this->endpoint = array_shift($this->args);
		if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
			$this->verb = array_shift($this->args);
		}
		$this->method = $_SERVER['REQUEST_METHOD'];
		if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
				$this->method = 'DELETE';
			} else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
				$this->method = 'PUT';
			} else {
				throw new Exception("Unexpected Header");
			}
		}

		switch($this->method) {
			case 'DELETE':
				break;
			case 'POST':
				$this->file = $this->_cleanInputs(json_decode(file_get_contents("php://input"), true));
				break;
			case 'GET':
				break;
			case 'PUT':
				$this->file = $this->_cleanInputs(json_decode(file_get_contents("php://input"), true));
				break;
			default:
				$this->_response('Invalid Method', 405);
				break;
		}
	}
	public function processAPI() {
		if (method_exists($this, $this->endpoint)) {
			return $this->_response($this->{$this->endpoint}($this->args), $this->response);
		}
		return $this->_response("No Endpoint: $this->endpoint", 404);
	}

	private function _response($data, $status) {
		header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		if($data != NULL)
			return json_encode($data);
		else 
			return NULL;
	}

	private function _cleanInputs($data) {
		$clean_input = Array();
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$clean_input[$k] = $this->_cleanInputs($v);
			}
		} else {
			$clean_input = trim(strip_tags($data));
		}
		return $clean_input;
	}

	private function _requestStatus($code) {
		$status = array(  
				200 => 'OK',
				400 => 'Bad Request',
				404 => 'Not Found',   
				405 => 'Method Not Allowed',
				500 => 'Internal Server Error',
			       ); 
		return ($status[$code])?$status[$code]:$status[500]; 
	}
}
?>

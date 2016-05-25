<?php
require_once('autoload.php');
class myStoreAPI extends API
{
	private $storeManagerObj;

	public function __construct($request, $origin) {
		parent::__construct($request);
		$this->storeManagerObj = new storeManager();
		$APIKey = new APIKey();
		if (!$APIKey->verifyKey()) {
			header("HTTP/1.1 " . "404" . " " . "Not Found");
			echo json_encode("Unauthorized access, specify Authorization header correctly");
			die;
		}
	}

	protected function items() {
		$body = $this->file;
		$args = $this->args;
		$action = $this->verb;
		if ($this->method == 'POST') {
			$response = $this->storeManagerObj->post($body);
		} else if ($this->method == 'GET') {
			$response = $this->storeManagerObj->get($action, $args);
		} else if ($this->method == 'DELETE') {
			$response = $this->storeManagerObj->delete($args);
		} else if ($this->method == 'PUT') {
			$response = $this->storeManagerObj->put($args, $body);
		} else {
			$response['code'] = 404;
			$response['data'] = "Only accepts GET, POST, PUT, & DELETE requests";
		}
		$this->response = $response['code'];
		return $response['data'];
	}
}
?>

<?php
require_once 'autoload.php';
class storeManager {
	private $storeDBObj;
	public function __construct() {
		$this->storeDBObj = new storeDB();
	}

	public function get($action, $args) {
		$name = NULL;
		$id = NULL;
		$action = strtolower($action);
		if($action == "search" && $args != NULL)
			$name = $args[0];
		else if($action == NULL && $args != NULL) {
			if(is_numeric($args[0]))
				$id = $args[0];
			else {
				$response['code'] = 404;
				$response['data'] = array("response" => "Only numeric id's allowed, for names please use search");
				return $response;
			}
		}
		else if($args == NULL && $action == NULL);
		else if($action == "search" && $args == NULL)
		{
			$response['code'] = 404;
			$response['data'] = array("response" => "Numeric id required after search");
			return $response;
		}
		else {
			$response['code'] = 404;
			$response['data'] = array("response" => "Only search action can be processed");
			return $response;
		}

		$response['code'] = 200;				
		if($name)
			$response['data'] = $this->storeDBObj->selectByName($name);
		else if($id)
			$response['data'] = $this->storeDBObj->selectById($id);
		else
			$response['data'] = $this->storeDBObj->selectAll();

		if($response['data'] == NULL)
			$response['code'] = 404;		
		
		return $response;
	}

	public function post($body) {
		if(!array_key_exists('name', $body) || !array_key_exists('quantity', $body) || !array_key_exists('price', $body)) {
			$response['code'] = 400;
			$response['data'] = array("response" => "Please provide all property values to insert");
			return $response;
		}
		
		if(!is_numeric($body['price']) || !is_numeric($body['quantity'])) {
			$response['code'] = 400;
                        $response['data'] = array("response" => "Please provide price & quantity as numeric, and name & description(optional) as string");
                        return $response;
		}	
		$response['code'] = 200;			
		$response['data'] = array("response" => $this->storeDBObj->insert($body)); 
		return $response;
	}	

	public function put($args, $body) {
		if($args[0] && is_numeric($args[0]))
			$id = $args[0];
		else {
			$response['code'] = 404;
			$response['data'] = array("response" => "Only numeric id's allowed");	
			return $response;
		}
		if(!array_key_exists('name', $body) || !array_key_exists('quantity', $body) || !array_key_exists('price', $body)) {
			$response['code'] = 400;
			$response['data'] = array("response" => "Please provide all property values to update");
			return $response;
		} 	
		if(!is_numeric($body['price']) || !is_numeric($body['quantity'])) {
                        $response['code'] = 400;
                        $response['data'] = array("response" => "Please provide price & quantity as numeric, and name & description(optional) as string");
                        return $response;
                } 
		$response['code'] = 200;		
		$response['data'] = array("response" => $this->storeDBObj->update($id, $body));	
		return $response;
	}

	public function delete($args) {
		if($args[0] && is_numeric($args[0]))
			$id = $args[0];
		else {
			$response['code'] = 404;
			$response['data'] = array("response" => "Only numeric id's allowed");
			return $response;
		}
		$response['code'] = 200;			
		$response['data'] = array("response" => $this->storeDBObj->delete($id));	
		return $response;
	}	
}
?>

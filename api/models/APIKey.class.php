<?php
class APIKey
{
        private $conn;
	public function __construct() {
		$servername = "localhost";
                $username = "root";
                $password = "root";
                try {
                        $this->conn = new PDO("mysql:host=$servername;dbname=store", $username, $password);
                        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
                catch(PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                        die;
                }
        }
	public function verifyKey() {
		$authHeader = $this->parseAuthorisationHeader();
		if(!empty($authHeader)) {
			if($authHeader['schemeName'] == "WingAuth") {
				$params = $this->parseAuthParams($authHeader['param']);
				return $this->validateId($params['id']);
			}
			else	
				return false;
		} else
			return false;
	}

	private function parseAuthorisationHeader()
	{
		$header = getallheaders();
                if(!array_key_exists('Authorization', $header))
                        return NULL;
                $authorisationHeader = $header['Authorization'];
                $tokenRegex = '/^(?P<schemeName>[\x21-\x27\x2A-\x2B\x2D\x2E0-9A-Z\x5E-z\x7C\7E]*)\s+(?P<param>.*)$/';
                preg_match($tokenRegex,$authorisationHeader,$output);
                return $output;
	}

	private function validateId($idValue)
	{
		if($idValue) {
			$username = $this->FetchUsernameFromUniqueId($idValue);
			if(!$username)
				return false;
			return true;
		} else
			return false;
	}

	private function parseAuthParams($paramString)
	{
		$paramArray = explode(',',$paramString);

		$paramArrayFinal = [];
		foreach($paramArray as $param) {
			$pos = strpos($param,"=");
			$paramArrayFinal[trim(substr($param,0,$pos))] = trim(substr($param,$pos+1),'"');
		}
		return $paramArrayFinal;
	}
	
	function FetchUsernameFromUniqueId($idValue) {
		return $this->selectUser($idValue);				
	}
	
	private function selectUser($password) {
                try {
                        $sql = "SELECT username FROM authenticate WHERE password = :pass";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bindParam(':pass', $password);
                        $stmt->execute();
                        $result = $stmt->fetchColumn();
                        return $result;
                }
                catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                        die;
                }
        }
}

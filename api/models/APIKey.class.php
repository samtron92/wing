<?php
class APIKey
{
	private $salt = 'wingStore=>sb.iiita15@gmail.com';
	private $method = 'blowfish';
	public function verifyKey() {
		$authHeader = $this->parseAuthorisationHeader();
		if(!empty($authHeader)) {
			if($authHeader['schemeName'] == "WingAuth") {
				$params = $this->parseAuthParams($authHeader['param']);
				return $this->validateId($params['id']);
			}
			else	
				return NULL;
		} else
			return NULL;
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
			$username = $this->decrypt($idValue);
			if(!$username)
				return NULL;
			return $username;
		} else
			return NULL;
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
	
	private function encrypt($data) {
		return openssl_encrypt($data, $this->method, $this->salt);
	}
		
	private function decrypt($data) {
                return openssl_decrypt($data, $this->method, $this->salt);
        }
}

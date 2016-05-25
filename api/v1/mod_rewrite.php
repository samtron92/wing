<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (dirname($path) == '/' && pathinfo($path, PATHINFO_EXTENSION) == 'php') {
	$file = pathinfo($path, PATHINFO_BASENAME);
}
else {
	$file = 'api.php';
	$path = explode('/', $path);
	$path = $path[3];
	$_REQUEST['request'] = $path;
}
$_SERVER['SCRIPT_NAME'] = '/' . $file;
require $file;
?>

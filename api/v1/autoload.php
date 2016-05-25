<?php
spl_autoload_register(function ($class_name) {
        $file = getcwd() . "/../models/" . $class_name . '.class.php';
        if(file_exists($file))
                require_once $file;
        else
                require_once $class_name . '.class.php';
});
?>

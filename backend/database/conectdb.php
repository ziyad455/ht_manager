<?php
$config = require('config.php');

spl_autoload_register(function ($classname) {
  require($classname . '.php');


});

$db = new Database(
    $config['DB'], 
    $_ENV['DB_USERNAME'] ?? 'root',
    $_ENV['DB_PASSWORD'] ?? ''
);


?>

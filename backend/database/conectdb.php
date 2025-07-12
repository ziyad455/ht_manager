<?php
$config = require('config.php');

spl_autoload_register(function ($classname) {
  require($classname . '.php');


});

$db = new Database($config['DB'], $_ENV['MYSQLUSER'] ?? 'root',$_ENV['MYSQLPASSWORD'] ??  'kLbpvikcnCHjfWHVJtuCqdTbIgUGnJsj');


?>

<?php
return [
  'DB' => [
    'host' => $_ENV['MYSQLHOST'] ?? 'localhost',
    'port' => $_ENV['MYSQLPORT'] ?? 3306,
    'dbname' => $_ENV['MYSQLDATABASE'] ?? 'railway',
    'charset' => 'utf8mb4'
  ]
];
<?php
return [
  'DB' => [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? 3306,
    'dbname' => $_ENV['DB_DATABASE'] ?? 'railway',
    'charset' => 'utf8mb4'
  ]
];
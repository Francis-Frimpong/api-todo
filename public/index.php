<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require_once __DIR__ . '/../routes/api.php';


// // Show everything PHP sees
// header('Content-Type: application/json');
// echo json_encode([
//     'server_vars' => $_SERVER,
//     'getallheaders' => function_exists('getallheaders') ? getallheaders() : null
// ], JSON_PRETTY_PRINT);
// exit;

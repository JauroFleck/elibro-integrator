<?php

// This script is used to redirect the user from the client platform to the eLibro platform
// base_url/?email=USER_EMAIL

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Dotenv\Dotenv;

// Read from .env file
// Use the .env-example as pattern

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('CHANNEL_NAME', $_ENV['CHANNEL_NAME']);
define('SECRET', $_ENV['SECRET']);
define('CHANNEL_ID', $_ENV['CHANNEL_ID']);
define('TOKEN', $_ENV['TOKEN']);

// Check if the email is set

if (!isset($_GET['email']) || empty($_GET['email'])) { die("URL corrompido!"); }
$user_name = $_GET['email'];

// Structure the request

$headers = [
    'Authorization' => TOKEN,
    'Content-Type' => 'application/json',
];

$body = [
    "secret" => SECRET,
    "channel_id" => CHANNEL_ID,
    "user" => $user_name,
];

$request = new Request('POST', "https://auth.elibro.net/auth/sso/?next=https://elibro.net/pt/lc/".CHANNEL_NAME."/inicio", $headers, json_encode($body));

// Send the request

$client = new Client();
$response = $client->send($request);

// Redirect the user to the eLibro platform

$url = json_decode($response->getBody())->url;
echo $url;
// header("Location: ".$url);

// EOF


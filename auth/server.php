<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/src/env.php');
$dsn      = 'mysql:dbname=oauth;host=localhost';
$username = $_ENV['SQL_USERNAME'];
$password = $_ENV['SQL_PASSWORD'];

require_once('OAuth2/Autoloader.php');
OAuth2\Autoloader::register();

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
$server = new OAuth2\Server($storage);
$server -> addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
$server -> addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
?>
<?php
session_start();
if (!isset($_SESSION['state']) || !isset($_REQUEST['state']) || !isset($_REQUEST['code']))
    die('A problem has occured');
if ($_SESSION['state'] != $_REQUEST['state'])
    die('A problem has occured');
$url = 'http://localhost/auth/token.php';
$params = [
    'grant_type' => 'authorization_code',
    'code' => urldecode($_REQUEST['code']),
    'redirect_uri' => 'http://localhost/sandbox/authorize.php'
];
$username = 'sandbox';
$password = 'password';
$data = http_build_query($params);
$context = stream_context_create([
    'http' => [
        'header' => "Content-Type: application/x-www-form-urlencoded\r\nAuthorization: Basic " . base64_encode($username . ':' . $password),
        'method' => 'POST',
        'content' => $data,
    ],
]);
$response = file_get_contents($url, false, $context);
$arr = json_decode($response, true);
$token = $arr['access_token'];
$_SESSION['token'] = $token;
header('Location: ./');
?>
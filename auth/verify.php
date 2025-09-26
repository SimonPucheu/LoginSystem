<?php
require_once __DIR__.'/server.php';
require_once __DIR__.'/../src/User.php';
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}
$token = $server->getAccessTokenData(OAuth2\Request::createFromGlobals());
$user_id = $token['user_id'];
$user = new User\Unidentified('', '');
$req = $user -> db -> request('SELECT username, password FROM users WHERE id = :id', ["id" => $user_id]) -> fetchAll();
if (count($req) > 0)
{
    $req = $req[0];
}
$user = new User\Unidentified($req['username'], $req['password']);
$user = new User\Identified($user);
$user -> Signin();
?>
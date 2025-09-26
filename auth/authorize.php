<?php
require(__DIR__ . '/../src/User.php');
User\AutoConnect();
require_once __DIR__.'/server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die;
}
$is_authorized = false;
$auth = $user -> db -> request("SELECT * FROM clients WHERE user_id = :user_id AND client_id = :client_id AND authorized = 1", ["user_id" => $user -> id, "client_id" => $_REQUEST['client_id']]) -> fetchAll();
if (count($auth) > 0)
{
  global $is_first_authorized;
  $is_first_authorized = true;
  $is_authorized = true;
}
if (empty($_POST) && !$is_authorized) {
  exit('
  <!DOCTYPE html>
  <html lang="en">
  
  <head>
	  <meta charset="UTF-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <link rel="stylesheet" href="/styles/main.css">
	  <link rel="stylesheet" href="/styles/form.css">
	  <title>Authorize</title>
  </head>
  
  <body>
    <form method="post">
      <label>Do you authorize ' . $_REQUEST['client_id'] . ' to have access to your account?</label><br />
      <div style="display: flex; justify-contents: space-around;">
        <input type="submit" name="authorized" value="Yes">
        <input type="submit" name="authorized" value="No">
      </div>
    </form>
  </body>
  
  </html>');
}
else if (!$is_authorized)
{
  $is_authorized = ($_POST['authorized'] === 'Yes');
}
$server->handleAuthorizeRequest($request, $response, $is_authorized, $user -> id);
if ($is_authorized) {
  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
  if (!$is_first_authorized)
  {
    $user -> db -> request("INSERT INTO clients (user_id, client_id) VALUES (:user_id, :client_id)", ["user_id" => $user -> id, "client_id" => $_REQUEST['client_id']]);
  }
}
$response->send();
?>
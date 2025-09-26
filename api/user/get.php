<?php
require_once __DIR__.'/../../auth/verify.php';
header('Content-Type: application/json');
if (isset($_REQUEST['key']))
{
    $key = $_REQUEST['key'];
    echo json_encode(array('success' => true, "user_id" => $user -> id, $key => $user -> {$key}));
    die;
}
echo json_encode(array('success' => true, 'user_id' => $user_id));
die;
?>
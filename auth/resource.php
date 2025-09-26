<?php
require_once __DIR__.'/verify.php';
echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!', 'id' => $user_id));
?>
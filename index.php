<?php
require(__DIR__ . '/src/User.php');
User\AutoConnect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IEedge=">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/main.css">
    <title>Home</title>
</head>

<body>
    <h1>Hello <?= $user -> name; ?></h1>
</body>

</html>
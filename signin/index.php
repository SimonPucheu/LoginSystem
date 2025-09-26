<?php
require(__DIR__ . '/../src/User.php');
$error = 0;
if (isset($_REQUEST))
{
    if (isset($_REQUEST['username']) && isset($_REQUEST['password']))
    {
        $user = new User\Unidentified($_REQUEST['username'], hash('sha256', $_REQUEST['password']));
        $user = new User\Identified($user);
        if ($user -> Signin())
        {
            header('Location: ' . (isset($_REQUEST['goto']) ? $_REQUEST['goto'] : "/"));
        }
        else
        {
            $error = 1;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/styles/form.css">
    <title>Signin</title>
</head>

<body>
    <form method="post">
        <h1>Signin</h1>
        <input type="text" id="username" name="username" placeholder="Your username" autocomplete="username">
        <input type="password" id="password" name="password" placeholder="Your password" autocomplete="current-password">
        <input type="submit" value="Signin">
        <p><a href="/signup">No account yet? Signup!</a></p>
        <p style="color: red;"><?php if ($error == 1) echo('Incorrect password') ?></p>
    </form>
</body>

</html>
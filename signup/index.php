<?php
require(__DIR__ . '/../src/User.php');
if (isset($_REQUEST))
{
    if (isset($_REQUEST['name']) && isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['cpassword']))
    {
        if ($_REQUEST['password'] == $_REQUEST['cpassword'])
        {
            $user = new User\Unidentified($_REQUEST['username'], hash('sha256', $_REQUEST['password']), $_REQUEST['name']);
            if ($user -> Create())
            {
                $user = new User\Identified($user);
                if ($user -> Signin())
                {
                    header('Location: ' . (isset($_REQUEST['goto']) ? $_REQUEST['goto'] : "/"));
                }
                else
                {
                    header('Location: /signin');
                }
            }
            else
            {
                $error = 1;
            }
        }
        else
        {
            $error = 0;
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
    <title>Signup</title>
</head>

<body>
    <form method="post">
        <h1>Signup</h1>
        <input type="text" id="name" name="name" placeholder="Your name" autocomplete="name">
        <input type="text" id="username" name="username" placeholder="Your username" autocomplete="username">
        <input type="password" id="password" name="password" placeholder="Your password" autocomplete="new-password">
        <input type="password" id="cpassword" name="cpassword" placeholder="Repeat password">
        <input type="submit" value="Signup">
        <p><a href="/signin">Already have an account? Signin!</a></p>
    </form>
</body>

</html>
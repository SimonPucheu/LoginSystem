<?php
require(__DIR__ . '/../src/User.php');
require(__DIR__ . '/../src/Client.php');
require('../src/ISO.php');
User\AutoConnect();
$change = false;
foreach (User\keys as $key)
{
    if (isset($_REQUEST[$key]))
    {
        $change = true;
        $user -> {$key} = $_REQUEST[$key];
    }
}
if ($change)
{
    $user -> UpdateProfile();
}
if (isset($_REQUEST["revoke"]))
{
    $client_id = explode(' ', $_REQUEST["revoke"])[1];
    $error = $user -> db -> request("UPDATE clients SET authorized = 0 WHERE client_id = :client_id", ["client_id" => $client_id]) -> fetchAll() != 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="style.css">
    <title>Profile</title>
</head>

<body>
    <h1>Hello <?= $user -> name; ?></h1>
    <form method="post">
        <label for="name">Name</label><input type="text" id="name" name="name" value="<?= $user -> name; ?>" autocomplete="name"><br>

        <label for="lang">Lang</label>
        <select name="lang" id="lang">
            <option value="en" <?= 'en' == $user -> lang ? 'selected' : '' ?>>en</option>
        </select><br>

        <label for="gender">Gender</label>
        <select name="gender" id="gender">
            <option value="0" <?= 0 == $user -> gender ? 'selected' : '' ?>>Not defined</option>
            <option value="1" <?= 1 == $user -> gender ? 'selected' : '' ?>>Male</option>
            <option value="2" <?= 2 == $user -> gender ? 'selected' : '' ?>>Female</option>
            <option value="3" <?= 3 == $user -> gender ? 'selected' : '' ?>>Other</option>
        </select><br>
        
        <label for="birthday">Birthday</label><input type="date" id="birthday" name="birthday" value="<?= $user -> birthday; ?>" autocomplete="bday"><br>

        <label for="country">Country</label>
        <select name="country" id="country">
            <option value="0">Not defined</option>
            <?php
            foreach (ISO\countries as $country):
            ?>
            <option value="<?= $country['code'] ?>" <?= $country['code'] == $user -> country ? 'selected' : '' ?>><?= $country['name'] ?></option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" value="Save"><br><br>
        
        <label for="clients"><b>Authorized Applications</b></label>
        <div class="clients">
            <?php
            $clients = $user -> db -> request("SELECT client_id FROM clients WHERE user_id = :user_id AND authorized = 1", ["user_id" => $user -> id]) -> fetchAll();
            if (count($clients) > 0):
                foreach ($clients as $client_id):
                    $client = new Client\Client($client_id["client_id"]);
            ?>
            <div class="client">
                <img src="<?= $client -> icon != "" ? $client -> icon : "https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/Question_mark_basic.svg/1200px-Question_mark_basic.svg.png" ?>" alt="?">
                <span><?= $client -> name != "" ? $client -> name : $client -> id ?></span>
                <input type="submit" id="revoke" name="revoke" value="Revoke <?= $client -> id ?>"></input>
            </div>
            <?php endforeach; else: ?>
                <div><i>Nothing to show here...</i></div>
            <?php endif; ?>
        </div>
    </form>
</body>

</html>
<?php
session_start();
if (!isset($_SESSION['token']))
{
    $state = base64_encode(random_bytes(32));
    $_SESSION['state'] = $state;
    header('Location: http://localhost/auth/authorize.php?response_type=code&client_id=sandbox&redirect_uri=http://localhost/sandbox/authorize.php&state=' . urlencode($state));
    die;
}
$token = $_SESSION['token'];
$result = '';
if (isset($_REQUEST))
{
    if (isset($_REQUEST['url']) && isset($_REQUEST['params']))
    {
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer " . $token
            ]
        ]);
        $result = file_get_contents('http://localhost/api/' . $_REQUEST['url'] . '?' . $_REQUEST['params'], false, $context);
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
    <title>Sandbox</title>
</head>

<body>
    <form method="get">
        <label for="url">http://localhost/api/</label>
        <input type="text" name="url" id="url" value="<?= isset($_REQUEST['url']) ? $_REQUEST['url'] : '' ?>">
        <label for="params">?</label>
        <input type="text" name="params" id="params" value="<?= isset($_REQUEST['params']) ? $_REQUEST['params'] : '' ?>"><br>

        <input type="submit" value="Request">
    </form>
    <textarea name="result" id="result" cols="30" rows="10"><?= $result ?></textarea>
</body>

</html>
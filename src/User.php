<?php
namespace User;
require_once("SQL.php");
require_once("env.php");

const keys = ["name", "lang", "gender", "birthday", "country"];

class Identified
{
    public $id;
    public $username;
    public $password;
    public $name;
    public $lang;
    public $gender;
    public $birthday;
    public $country;
    public $db;
    public $signedIn = false;
    public function __construct($unidentified)
    {
        var_dump($_ENV['SQL_USERNAME']);
        var_dump($_ENV['SQL_PASSWORD']);
        $this -> db = new \SQL("localhost", "users", $_ENV['SQL_USERNAME'], $_ENV['SQL_PASSWORD']);
        $this -> db -> initialize();
        $this -> username = $unidentified -> username;
        $this -> password = $unidentified -> password;
    }
    public function Signin (bool $storeInSession = true)
    {
        $signedIn = false;
        $response = $this -> db -> request("SELECT id, name, lang, gender, birthday, country FROM users WHERE username = :username AND password = :password", ["username" => $this -> username, "password" => $this -> password]) -> fetchAll();
        if (count($response) > 0)
        {
            $this -> id = $response[0][0];
            $this -> name = $response[0][1];
            $this -> lang = $response[0][2];
            $this -> gender = $response[0][3];
            $this -> birthday = $response[0][4];
            $this -> country = $response[0][5];
            if ($storeInSession)
            {
                if (session_status() == PHP_SESSION_NONE)
                {
                    session_start();
                }
                if (session_status() == PHP_SESSION_ACTIVE)
                {
                    $_SESSION['username'] = $this -> username;
                    $_SESSION['password'] = $this -> password;
                }
            }
            $signedIn = true;
            return true;
        }
        return false;
    }
    public function UpdateProfile()
    {
        // if (!$signedIn)
        //     return false;
        return count($this -> db -> request("UPDATE users SET name = :name, gender = :gender, birthday = :birthday, country = :country, lang = :lang WHERE id = :id", ["id" => $this -> id, "name" => $this -> name, "gender" => $this -> gender, "birthday" => $this -> birthday, "country" => $this -> country, "lang" => $this -> lang]) -> fetchAll()) == 0;
    }
}

class Unidentified
{
    public $username;
    public $password;
    public $name;
    public $lang;
    public $db;
    public function __construct(string $username, string $password, string $name = "", string $lang = "")
    {
        $this -> db = new \SQL("localhost", "users", $_ENV['SQL_USERNAME'], $_ENV['SQL_PASSWORD']);
        $this -> db -> initialize();
        $this -> username = $username;
        $this -> password = $password;
        $this -> name = $name;
        $this -> lang = $lang;
    }
    public function Exists ()
    {
        return count($this -> db -> request("SELECT id FROM users WHERE username = :username", ["username" => $this -> username]) -> fetchAll()) >= 1;
    }
    public function Create ()
    {
        if ($this -> exists())
        {
            return false;
        }
        $response = $this -> db -> request("INSERT INTO users (username, password, name, lang) VALUES (:username, :password, :name, :lang)", ["username" => $this -> username, "password" => $this -> password, "name" => $this -> name, "lang" => $this -> lang]);
        return $this;
    }
}

function CleanString($string)
{
   return preg_replace('/[^A-Za-z0-9\-,]/', '', $string);
}

function AutoConnect ()
{
    $queryString = $_SERVER['QUERY_STRING'];
    $encodedParams = urlencode($queryString);
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    if (session_status() == PHP_SESSION_ACTIVE)
    {
        if (isset($_SESSION['username']) && isset($_SESSION['password']))
        {
            global $user;
            $user = new Unidentified($_SESSION['username'], $_SESSION['password']);
            $user = new Identified($user);
            if (!($user -> Signin()))
            {
                header('Location: /signin?goto=' . $_SERVER['PHP_SELF'] . '?' . $encodedParams);
                die;
            }
        }
        else
        {
            header('Location: /signin?goto=' . $_SERVER['PHP_SELF'] . '?' . $encodedParams);
            die;
        }
    }
    else
    {
        header('Location: /signin?goto=' . $_SERVER['PHP_SELF'] . '?' . $encodedParams);
        die;
    }
}
?>
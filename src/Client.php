<?php
namespace Client;
require_once("SQL.php");
require_once("env.php");

class Client
{
    public $id;
    public $secret;
    public $name;
    public $icon;
    public $db;
    public function __construct(string $id)
    {
        $this -> db = new \SQL("localhost", "oauth", $_ENV['SQL_USERNAME'], $_ENV['SQL_PASSWORD']);
        $this -> db -> initialize();
        $this -> id = $id;
        $response = $this -> db -> request("SELECT client_secret, client_name, client_icon FROM oauth_clients WHERE client_id = :client_id", ["client_id" => $this -> id]) -> fetchAll();
        if (count($response) > 0)
        {
            $this -> secret = $response[0]["client_secret"];
            $this -> name = $response[0]["client_name"];
            $this -> icon = $response[0]["client_icon"];
            return true;
        }
        return false;
    }
}
?>
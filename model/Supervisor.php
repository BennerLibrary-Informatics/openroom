<?php

namespace model;
class Supervisor
{
    public $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public static function all()
    {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM `supervisors`');
        foreach ($req->fetchAll() as $supervisor) {
            $list[] = new Supervisor($supervisor['username']);
        }

        return $list;
    }

    public static function find($username)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT * FROM `supervisors` WHERE username = :username');
        $req->execute(array('username' => $username));
        $supervisor = $req->fetch();

        return new Supervisor($supervisor['username']);
    }

    public static function add($username)
    {
        if (!Supervisor::exists($username)) {
            $db = Db::getInstance();
            $req = $db->prepare('INSERT INTO `supervisors`(username) VALUES (:username)');
            $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
            $req->execute();
            return true;
        }
        return false;
    }

    public static function exists($username)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT exists(SELECT * FROM `supervisors` WHERE username = :username)');
        $req->execute(array('username' => $username));
        $supervisor = $req->fetch();
        return $supervisor[0];
    }

    public static function remove($username)
    {
        if (Supervisor::exists($username)) {
            $db = Db::getInstance();
            $req = $db->prepare('DELETE FROM `supervisors` WHERE username = :username');
            $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
            $req->execute();
            return true;
        }
        return false;
    }
}

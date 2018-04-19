<?php

namespace model;
class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

// db
Config::write('db.host', 'localhost');
Config::write('db.port', '3306');
Config::write('db.basename', 'openroom');
Config::write('db.user', 'root');
Config::write('db.password', '');

<?php

class DB
{
    private $conn       = null;
    private static $db  = null;
    private $info;

    private final function __construct()
    {
        $this->info['host'] = '127.0.0.1';
        $this->info['user'] = 'root';
        $this->info['pass'] = '';
        $this->info['db']   = 'gym_trainer_personal_app';

        if($this->conn==null){
            try{
                $dsn = "mysql:host=".$this->info['host'].";dbname=".$this->info['db'].";charset=utf8mb4";
                $pdo = new PDO($dsn, $this->info['user'], $this->info['pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn = $pdo;
            }catch (Exception $e){
                $this->conn = null;
                die("<b>Connection Exception:</b><br>".$e->getMessage());
            }
        }
    }

    public static function getDb()
    {
        if(self::$db==null){
            self::$db = new DB();
        }
        return self::$db;
    }

    public function conn()
    {
        return $this->conn;
    }

    public function info()
    {
        return $this->info;
    }

    public function destroyConn(){
        $this->conn = null;
    }

}





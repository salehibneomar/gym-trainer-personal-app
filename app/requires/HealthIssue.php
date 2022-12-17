<?php

namespace Client;

class HealthIssue
{
    private $conn;
    private $tableName = "client_health_issue";

    public function __construct()
    {
        $this->conn = \DB::getDb()->conn();
    }

    public function readByClient($clientId)
    {
        $result = null;
        $query = "SELECT * FROM ".$this->tableName." WHERE client_id= :client_id LIMIT 1";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':client_id', $clientId);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $result = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function create($data)
    {
        $result  = false;
        $columns = implode(", ", array_keys($data));
        $values  = ":".implode(", :", array_keys($data));
        $query   = "INSERT INTO ".$this->tableName." (".$columns.") "." VALUES( ".$values." ) ";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                foreach ($data as $col => $val) {
                    $stmt->bindValue(":$col", $val);
                }
                $stmt->execute();
                $result = true;
            }
        }catch (\Exception $e){
            //die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function updateByClient($clientId, $data)
    {
        $result = false;
        $query  = "UPDATE ".$this->tableName." SET ";
        foreach ($data as $key => $val){
            if(array_key_last($data)==$key){
                $query.="$key= :$key";
            }
            else{
                $query.="$key= :$key, ";
            }
        }

        $query.=" WHERE client_id= :client_id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                foreach ($data as $key=> $val)
                {
                    $stmt->bindValue(":$key", $val);
                }
                $stmt->bindValue(':client_id', $clientId);
                $stmt->execute();
                $result = true;
            }

        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt  = null;
        return $result;
    }

}
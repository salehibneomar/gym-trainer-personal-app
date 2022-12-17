<?php

namespace Trainer;

class Achievement
{
    private $conn;
    private $tableName = "achievements";
    private $trainerId;

    public function __construct($trainerId)
    {
        $this->conn = \DB::getDb()->conn();
        $this->trainerId = $trainerId;
    }

    public function readAll()
    {
        $result = null;
        $query  = "SELECT * FROM ".$this->tableName." WHERE trainer_id= :trainer_id";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':trainer_id', $this->trainerId);
                $stmt->execute();
                if($stmt->rowCount()>0){
                    $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }
        $stmt       = null;

        return $result;
    }

    public function readById($id)
    {
        $result = null;
        $query = "SELECT * FROM ".$this->tableName." WHERE id= :id AND trainer_id= :trainer_id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':trainer_id', $this->trainerId);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $result = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt       = null;

        return $result;
    }

    public function create($data)
    {
        $data['trainer_id'] = $this->trainerId;
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
        $stmt       = null;

        return $result;
    }

    public function delete($id)
    {
        $result = false;
        $query = "DELETE FROM ".$this->tableName." WHERE id= :id AND trainer_id= :trainer_id";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':trainer_id', $this->trainerId);
                $stmt->execute();
                $result = true;
            }
        }
        catch (\Exception $e){
            //die($e);
        }
        $stmt       = null;

        return $result;
    }

    public function update($id, $data)
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

        $query.=" WHERE id= :id AND trainer_id= :trainer_id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                foreach ($data as $key=> $val)
                {
                    $stmt->bindValue(":$key", $val);
                }
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':trainer_id', $this->trainerId);
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
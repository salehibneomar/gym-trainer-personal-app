<?php

namespace Workout;

class Workout
{
    private $conn;
    private $tableName = "workouts";
    private $viewName  = "workouts_view";

    public function __construct()
    {
        $this->conn = \DB::getDb()->conn();
    }

    private function archive($clientId)
    {
        $result = false;
        $query = "UPDATE ".$this->tableName." SET w_status='archived' WHERE w_status='active' AND client_id= :client_id LIMIT 1";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':client_id', $clientId);
                $stmt->execute();
                $result = true;
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt = null;
        return $result;
    }

    public function create($data)
    {
        $result  = false;

        if($this->archive($data['client_id'])){
            $columns = implode(", ", array_keys($data));
            $values  = ":".implode(", :", array_keys($data));
            $query   = "INSERT INTO ".$this->tableName." (".$columns.") "." VALUES( ".$values." ) ";

            try{
                $stmt  = $this->conn->prepare($query);
                if ($stmt) {
                    foreach ($data as $col => $val) {
                        $stmt->bindValue(":$col", $val);
                    }
                    $stmt->execute();
                    $result = true;
                }
            }
            catch (\Exception $e){
                //die($e);
            }
        }

        $stmt  = null;
        return $result;
    }

    public function readById($id)
    {
        $result = null;
        $query  = "SELECT * FROM ".$this->viewName." WHERE id= :id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id);
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

    public function update($id, $data)
    {
        $result = false;

        if(array_key_exists('w_status', $data) && $data['w_status']=='active'){
            if(!($this->archive($id['client_id']))){
                return $result;
            }
        }

        $query  = "UPDATE ".$this->tableName." SET ";
        foreach ($data as $key => $val){
            if(array_key_last($data)==$key){
                $query.="$key= :$key";
            }
            else{
                $query.="$key= :$key, ";
            }
        }

        $query.=" WHERE id= :id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                foreach ($data as $key=> $val)
                {
                    $stmt->bindValue(":$key", $val);
                }
                $stmt->bindValue(':id', $id['id']);
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

    public function delete($id){
        $result = false;
        $query = "DELETE FROM ".$this->tableName." WHERE id= :id";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id);
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

    public function readByIdAndClientId($id, $clientId)
    {
        $result = null;
        $query  = "SELECT * FROM ".$this->viewName." WHERE id= :id AND client_id= :client_id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id);
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

}
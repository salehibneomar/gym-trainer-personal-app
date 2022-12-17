<?php

namespace Rating;

class Rating
{

    private $conn;
    private $tableName = "ratings";

    public function __construct()
    {
        $this->conn = \DB::getDb()->conn();
    }

    public function exists($id){
        $result = false;
        $query  = "SELECT id FROM ".$this->tableName." WHERE client_id= :client_id";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                $stmt = $this->conn->prepare($query);
                if($stmt){
                    $stmt->bindValue(':client_id', $id);
                    $stmt->execute();
                    if($stmt->rowCount()>=1){
                        $result = true;
                    }
                }
            }
        }catch (\Exception $e){
            die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function create($data){
        $result      = false;
        $columns     = implode(", ", array_keys($data));
        $values      = ":".implode(", :", array_keys($data));
        $query       = "INSERT INTO ".$this->tableName." (".$columns.") "." VALUES( ".$values." ) ";

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
            die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function delete($id){
        $result = false;
        $query = "DELETE FROM ".$this->tableName." WHERE id= :id AND trainer_id= :trainer_id";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id['id']);
                $stmt->bindValue(':trainer_id', $id['trainer_id']);
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

    public function readById($id){
        $result = null;
        $query = "SELECT * FROM ".$this->tableName." WHERE id= :id AND trainer_id= :trainer_id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':id', $id['id']);
                $stmt->bindValue(':trainer_id', $id['trainer_id']);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $result = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt   = null;
        return $result;
    }

    public function updateStatus($id, $data){
        $result = false;
        $query  = "UPDATE ".$this->tableName." SET r_status= :r_status WHERE id= :id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':r_status',$data);
                $stmt->bindValue(':id', $id);
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

    public function getPendingCount($trainerId){
        $result = 0;
        $query  = "SELECT COUNT(id) AS 'totalCount' FROM ".$this->tableName." WHERE r_status='pending' AND trainer_id= :trainer_id";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':trainer_id', $trainerId);
                $stmt->execute();
                $result = ($stmt->fetch(\PDO::FETCH_OBJ))->totalCount;
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt = null;
        return $result;
    }
}
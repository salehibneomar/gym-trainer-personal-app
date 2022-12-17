<?php

namespace Dashboard;

class Dashboard
{
    private $conn;

    public function __construct()
    {
        $this->conn = \DB::getDb()->conn();
    }

    public function clientDashboardById($clientId){
        $data = array();
        $data['routine'] = $this->getActiveRoutine($clientId);
        $data['diet']    = $this->getActiveDiet($clientId);
        return $data;
    }

    private function getActiveRoutine($clientId){
        $data = null;
        $routine = "SELECT * FROM workouts_view WHERE w_status='active' AND client_id= :client_id LIMIT 1";

        try{
            $stmt = $this->conn->prepare($routine);
            if($stmt){
                $stmt->bindValue(':client_id', $clientId);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $data = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
           // die($e);
        }

        $stmt = null;
        return $data;
    }

    private function getActiveDiet($clientId){
        $data = null;
        $routine = "SELECT id FROM diets_view WHERE d_status='active' AND client_id= :client_id LIMIT 1";

        try{
            $stmt = $this->conn->prepare($routine);
            if($stmt){
                $stmt->bindValue(':client_id', $clientId);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $data = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt = null;
        return $data;
    }

    public function trainerDashboardData(){
        $data = null;
        $query = "SELECT  
                    (SELECT COUNT(id) FROM clients WHERE acc_status!='hidden' AND acc_status!='pending') AS 'total_clients',
                    (SELECT COUNT(id) FROM workouts) AS 'total_routines',   
                    (SELECT COUNT(id) FROM diets) AS 'total_diets',   
	                (SELECT COUNT(id) FROM ratings) AS 'total_ratings'";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $data = $stmt->fetch(\PDO::FETCH_OBJ);
                }
            }
        }
        catch (\Exception $e){
            die($e);
        }

        $stmt  = null;
        return $data;

    }

}
<?php

class Frontend
{
    private $conn;

    public function __construct()
    {
        $this->conn = DB::getDb()->conn();
    }

    public function getTrainerSocialMedia($trainerId){
        $data = null;
        $query = "SELECT platform, icon, link FROM social_media WHERE trainer_id= :trainer_id";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':trainer_id', $trainerId);
                $stmt->execute();
                if($stmt->rowCount()>=1){
                    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                }
            }
        }
        catch (Exception $e){
            //die($e);
        }
        $stmt = null;
        return $data;
    }

    public function getTrainerTestimonial($trainerId){
        $data = null;
        $query = "SELECT r.star, r.remark, c.name AS 'client_name', c.profile_picture AS 'client_dp' FROM ratings r, clients c WHERE r.trainer_id= :trainer_id AND r.trainer_id=c.trainer_id AND r.client_id=c.id AND r.r_status='live' AND r.star>=3 ORDER BY r.star DESC LIMIT 5";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':trainer_id', $trainerId);
                $stmt->execute();
                if($stmt->rowCount()>=1){
                    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                }
            }
        }
        catch (Exception $e){
            //die($e);
        }
        $stmt = null;
        return $data;
    }

    public function getTrainerAbout($trainerId){
        $data = null;
        $query = "SELECT name, dob, profile_picture, education, about, debut_date, email FROM trainer WHERE id= :trainer_id LIMIT 1";
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':trainer_id', $trainerId);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $data = $stmt->fetch(PDO::FETCH_OBJ);
                }
            }
        }
        catch (Exception $e){
            //die($e);
        }
        $stmt = null;
        return $data;
    }

    public function getStats(){
        $data = null;
        $query = "SELECT  
                    (SELECT COUNT(id) FROM clients WHERE acc_status!='hidden' AND acc_status!='pending') AS 'total_clients',
                    (SELECT COUNT(id) FROM gyms) AS 'total_gyms',   
                    (SELECT COUNT(id) FROM achievements) AS 'total_ach',   
                    ((SELECT COUNT(id) FROM workouts)+(SELECT COUNT(id) FROM diets)) AS 'total_routines'";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $data = $stmt->fetch(PDO::FETCH_OBJ);
                }
            }
        }
        catch (Exception $e){
            //die($e);
        }

        $stmt = null;
        return $data;
    }

    public function getAchievements($trainerId){
        $data = null;
        $query = "SELECT title, type, remark, attained_date FROM achievements WHERE trainer_id= :trainer_id ORDER BY remark DESC LIMIT 5";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':trainer_id', $trainerId);
                $stmt->execute();
                if($stmt->rowCount()>=1){
                    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                }
            }
        }
        catch (Exception $e){
            //die($e);
        }

        $stmt = null;
        return $data;
    }

}
<?php

namespace Trainer;

class SiteSetting
{
    private $conn;
    private $tableName = "site_setting";

    public function __construct()
    {
        $this->conn = \DB::getDb()->conn();
    }

    public function read()
    {
        $result = null;
        $query = "SELECT st.*, t.name AS 'trainer_name' FROM ".$this->tableName." st , trainer t WHERE st.trainer_id=t.id LIMIT 1";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $result = $stmt->fetch(\PDO::FETCH_OBJ);
                    $result->icon = "images/icon/".$result->icon;
                    $result->banner = "images/banner/".$result->banner;
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function updateTitle($title){
        $result = false;
        $query  = "UPDATE ".$this->tableName." SET title= :title";

        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':title', $title);
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

    public function updateIcon($icon){
        $result = false;
        $query  = "UPDATE ".$this->tableName." SET icon= :icon";

        try{
            $uploaded = move_uploaded_file($icon['iconTmpName'], "images/icon/".$icon['iconName']);
            if($uploaded){
                $stmt = $this->conn->prepare($query);
                if($stmt){
                    $stmt->bindValue(':icon', $icon['iconName']);
                    $stmt->execute();
                    $result = true;
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function updateBanner($banner){
        $result = false;
        $query  = "UPDATE ".$this->tableName." SET banner= :banner";

        try{

            $bannerSource = $banner['bannerTmpName'];
            $quality      = 50;
            $location     = "images/banner/".$banner['bannerName'];
            $bannerImage  = imagecreatefromjpeg($bannerSource);

            $uploaded     = imagejpeg($bannerImage, $location, $quality);

            if($uploaded){
                if(file_exists($banner['previousBanner'])){
                    unlink($banner['previousBanner']);
                }
                $stmt  = $this->conn->prepare($query);
                if($stmt){
                    $stmt->bindValue(':banner', $banner['bannerName']);
                    $stmt->execute();
                    $result = true;
                }
            }
        }
        catch (\Exception $e){
            die($e);
        }

        $stmt  = null;
        return $result;
    }

}
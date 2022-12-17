<?php

namespace Trainer;

class Trainer
{
    private $profileImageDir = "images/trainer/";
    private $conn;
    private $tableName = "trainer";

    public function __construct()
    {
        $this->conn = \DB::getDb()->conn();
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

        $stmt       = null;

        return $result;
    }

    public function readById($id)
    {
        $result = null;
        $query  = "SELECT * FROM ".$this->tableName." WHERE id= :id";

        if(is_int($id)){
            try{
              $stmt  = $this->conn->prepare($query);
                if($stmt){
                    $stmt->bindValue(':id', $id);
                    $stmt->execute();
                    if($stmt->rowCount()==1){
                        $result = $stmt->fetch(\PDO::FETCH_OBJ);
                    }
                }
            }
            catch (\Exception $e){
                $result = $e->getMessage();
                //die($e);
            }
        }
        $stmt       = null;

        return $result;
    }

    public function auth($phone, $pwd)
    {
        $result = null;
        $pwd    = SHA1(md5($pwd));
        $query  = "SELECT id, name, dob, profile_picture, education, about, debut_date, acc_creation_date, phone, email, acc_status FROM ".$this->tableName." WHERE phone= :phone AND pwd= :pwd";

        try{
            $stmt  = $this->conn->prepare($query);
            if($stmt){
                $stmt->bindValue(':phone', $phone);
                $stmt->bindValue(':pwd', $pwd);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                }
            }
        }
        catch (\Exception $e){
            $result = null;
            //die($e);
        }

        $stmt  = null;
        return $result;
    }

    public function updateProfilePicture($id, $imageData){
        $result = false;
        $query  = "UPDATE ".$this->tableName." SET profile_picture= :profile_picture WHERE id= :id";

        try{

            $imageSource = $imageData['imageTempName'];
            $quality     = 55;
            $image       = null;
            $location    = $this->getProfileImageDir().$imageData['imageName'];

            if($imageData['imageExt']=='jpg' || $imageData['imageExt']=='jpeg'){
                $image = imagecreatefromjpeg($imageSource);
            }
            else if($imageData['imageExt']=='png'){
                $image = imagecreatefrompng($imageSource);
            }

            $uploaded = imagejpeg($image, $location, $quality);

            if($uploaded){
                $prevImage = $this->getProfileImageDir().$imageData['previousProPic'];
                if(file_exists($prevImage)){
                    unlink($prevImage);
                }
                $stmt  = $this->conn->prepare($query);
                if($stmt){
                    $stmt->bindValue(':profile_picture', $imageData['imageName']);
                    $stmt->bindValue(':id', $id);
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


    public function updateGeneral($id, $data){
        $result = -1;
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

        $stmt = null;
        try{
            $stmt = $this->conn->prepare($query);
            if($stmt){
                foreach ($data as $key=> $val)
                {
                    $stmt->bindValue(":$key", $val);
                }
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                $result = 1;
            }

        }
        catch (\Exception $e){
            //die($e);
        }
        finally {
            $sql_error_code = $stmt->errorInfo()[1];
            if($sql_error_code!=null && $sql_error_code==1062){
                $result = $sql_error_code;
            }
        }

        $stmt  = null;
        return $result;

    }

    public function updatePassword($id, $password){
        $result = 0;
        $validateQuery = "SELECT pwd FROM ".$this->tableName." WHERE id= :id AND pwd= :pwd";
        $validated     = false;

        try{
            $stmt = $this->conn->prepare($validateQuery);
            $prevPwd = SHA1(md5($password['prevPwd']));

            if($stmt){
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':pwd',$prevPwd);
                $stmt->execute();
                if($stmt->rowCount()==1){
                    $validated = true;
                    $stmt = null;
                }
                else{
                    return -1;
                }
            }
        }
        catch (\Exception $e){
            //die($e);
        }

        if($validated){
            $updateQuery = "UPDATE ".$this->tableName." SET pwd= :pwd WHERE id= :id";
            try{
                $stmt = $this->conn->prepare($updateQuery);
                $newPwd = SHA1(md5($password['newPwd']));
                if($stmt){
                    $stmt->bindValue(':id', $id);
                    $stmt->bindValue(':pwd', $newPwd);
                    $stmt->execute();
                    $result = 1;
                }
            }
            catch (\Exception $e){
                //die($e);
            }
        }

        $stmt  = null;
        return $result;
    }

    public function getProfileImageDir()
    {
        return $this->profileImageDir;
    }

}




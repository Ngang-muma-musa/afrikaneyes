<?php

class CLient extends Connection {
    public function clientRegistration($dataObject):array{
        $data = (array) json_decode(json_encode($dataObject),true);
        $sql = "SELECT email FROM agent WHERE email=:email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $count = $stmt->rowCount();
        if($count > 0){
            $data = array(
                "message" => "Error Account not created Email already exist",
            );
            return $data;
            exit();
        }
        $sql = "INSERT INTO client (first_name,last_name,uname,phoneNumber,password,email,profile_image) VALUES(:firstName,:lastName,:userName,:phoneNumber,:password,:email,:profileImage)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':firstName', $data['firstName'], PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $data['lastName'], PDO::PARAM_STR);
        $stmt->bindParam(':userName', $data['userName'], PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $data['phoneNumber'], PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':profileImage', $data['profileImage'], PDO::PARAM_STR);
        if($stmt->execute()){
            $data = array(
                "message" => "Account created successfully",
            );
            return $data;
        }else {
            $data = array(
                "message" => "Error Account not created",
            );
            return $data;
        }
    }
    public function clientLogin($email){
        $sql = "SELECT * FROM `client` WHERE email=:email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email",$email);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count == 0){
            $data = array(
                "message" => "Email Not Found",
            );
            return $data;
        }
        else{
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }
    }
    public function fetchCLientDetails($client_id):array{
        $sql = "SELECT * FROM client WHERE client_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id",$client_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public function createHidddenInfo($data):array{
        $sql = "INSERT INTO hiddeninfo (request_id,location,contact,view_date) VALUES (:requestId,:location,:contact,:viewDate)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':requestId', $data['requestId'], PDO::PARAM_STR);
        $stmt->bindParam(':location', $data['location'], PDO::PARAM_STR);
        $stmt->bindParam(':contact', $data['contact'], PDO::PARAM_STR);
        $stmt->bindParam(':viewDate', $data['viewDate'], PDO::PARAM_STR);
        if($stmt->execute()){
            $data = array(
                "message" => "Information uploaded successfuly",
            );
            return $data;
        }
        else{
            $data = array(
                "message" => "Error information not uploaded",
            );
            return $data;
        }
    }
}
<?php
class Agent extends Connection{

    public  function agentRegistration($dataObject):array{
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
        $sql = "INSERT INTO agent (first_name,last_name,uname,dob,gender,address,phoneNumber,password,email,profile_image,id_front,id_back,status) VALUES(:firstName,:lastName,:userName,:dob,:gender,:address,:phoneNumber,:password,:email,:profileImage,:idFront,:idBack,:status)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':firstName', $data['firstName'], PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $data['lastName'], PDO::PARAM_STR);
        $stmt->bindParam(':userName', $data['userName'], PDO::PARAM_STR);
        $stmt->bindParam(':dob', $data['dob'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $data['phoneNumber'], PDO::PARAM_STR);
        $stmt->bindParam(':password', password_hash($data['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':profileImage', $data['profileImage'], PDO::PARAM_STR);
        $stmt->bindParam(':idFront', $data['idFront'], PDO::PARAM_STR);
        $stmt->bindParam(':idBack', $data['idBack'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
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

    public function uploadReport($data):array{
        $report = (array) json_decode(json_encode($data),true);
        $status = "unverified";
        $reportId = uniqid();
        $sql = "INSERT INTO report (report_id,introduction,body,conclusion,request_id,status) VALUES (:reportId,:introduction,:body,:conclusion,:requestId,:status)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':reportId',$reportId,PDO::PARAM_STR);
        $stmt->bindParam(':introduction',$report['introduction'],PDO::PARAM_STR);
        $stmt->bindParam(':body',$report['body'],PDO::PARAM_STR);
        $stmt->bindParam(':conclusion',$report['conclusion'],PDO::PARAM_STR);
        $stmt->bindParam(':requesttId',$report['requestId'],PDO::PARAM_STR);
        $stmt->bindParam(':status',$status,PDO::PARAM_STR);
        if($stmt->execute()){
            $reportProof = (array) json_decode(json_encode($report['proof']),true);
            foreach($reportProof as $key => $value){
                $sql = "INSERT INTO proof (report_id,summary,file) VALUES (:reportId,:summary,:file)";
                $stmt = $this->connect()->prepare($sql);
                $stmt->bindParam(':reportId',$reportId,PDO::PARAM_STR);
                $stmt->bindParam(':summary',$key,PDO::PARAM_STR);
                $stmt->bindParam(':file',$value,PDO::PARAM_STR);
                $stmt->execute();
            }
            $data = array(
                "message" => "report created successfully",
            );
            return $data;
        }else{
            $data = array(
                "message" => "Error Account not created",
            );
            return $data;
        }
    }

    public function getReport($ticketId):array{
        $sql = "SELECT * FROM report WHERE request_id = :request_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('request_id',$ticketId,PDO::PARAM_STR);
        if($stmt->execute()){
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }
        else{
            $data = array(
                "message" => "Error No data found for $ticketId",
            );
            return $data; 
        }
    }

    public function verifyReport($reportId):array{
        $status = "verified";
        $sql = "UPDATE report SET status = :status WHERE report_id = :reportId";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':status',$status,PDO::PARAM_STR);
        $stmt->bindParam(':reportId',$reportId,PDO::PARAM_STR);
        if($stmt->execute()){
            $data = array(
                "message" => "Report verified Successfuly",
            );
            return $data;
        }else{
            $data = array(
                "message" => "Error report not verified",
            );
            return $data;
        }
    }

}
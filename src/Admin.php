<?php

class Admin extends Connection {
    public function adminlogin($email):array{
        $sql = "SELECT * FROM `admin` WHERE email=:email";
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
    public function fetchUnverifiedAgents():array{
        $sql = "SELECT * FROM agent WHERE status = 'NOTVERIFIED'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public function fetchVerifiedAgents():array{
        $sql = "SELECT * FROM agent WHERE status = 'VERIFIED'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public function agentDetails($agent_id):array{
        $sql = "SELECT * FROM agent WHERE agent_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id",$agent_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public function updateAgentStatus($agent_id,$status):array{
        $sql = "UPDATE agent SET status = :status WHERE agent_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id",$agent_id);
        $stmt->bindParam(":status",$status);
        if($stmt->execute()){
            $data = array(
                "message" => "status updated successfully",
            );
            return $data;
        }else{
            $data = array(
                "message" => "Error status update unsuccessful",
            );
            return $data;
        }
    }

    public function assignAgent($agentId,$requestId):array{
        $sql = "INSERT INTO assigned (agentid,requestid) VALUES(:agentid,:requestid)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agentid",$agentId);
        $stmt->bindParam(":requestid",$requestId);
        if($stmt->execute()){
            $data = array(
                "message" => "agent assigned successfuly",
            );
            return $data;
        }else{
            $data = array(
                "message" => "Error agent was not assigned",
            );
            return $data;
        }

    }
    public function deleteAgent($agent_id):array{
        $sql = "DELETE FROM agent WHERE agent_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id",$agent_id);
        if($stmt->execute()){
            $data = array(
                "message" => "deleted succesfully",
            );
            return $data;
        } else {
            $data = array(
                "message" => "Error delete unsucceful",
            );
            return $data;
        }
    }

    public function createInvoice($data):array{
        if(count($data['reason']) !== count($data['amount'])){
            $data = array(
                "message" => "Error creating invoice",
            );
            return $data;
            exit();
        }
        for($i = 0; $i <= count($data['reason'])-1;$i++){
            $sql = "INSERT INTO invoice (request_id,reason,amount) VALUES (:requestId,:reason,:amount)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':requestId', $data['requestId'], PDO::PARAM_STR);
            $stmt->bindParam(':reason', $data['reason'][$i], PDO::PARAM_STR);
            $stmt->bindParam(':amount', $data['amount'][$i], PDO::PARAM_INT);
            $stmt->execute();
        }
        $data = array(
            "message" => "Invoice created successfuly",
        );
        return $data;
        exit();
    }
}
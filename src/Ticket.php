<?php
require_once "Connection.php";
class Ticket extends Connection {

    public function createTicket($dataObject):array{
        $data = (array) json_decode(json_encode($dataObject),true);
        $ticketId = uniqid();
        $sql = "INSERT INTO request (request_id ,client_id ,agent_id ,title ,type_of_service ,size ,reason ,support_file ,time_frame ,date ,status) VALUES(:requestId, :clientId, :agentId, :title, :tof, :size, :reason, :supportFile, :timeFrame, :date, :status)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':requestId', $ticketId, PDO::PARAM_STR);
        $stmt->bindParam(':clientId', $data['clientId'], PDO::PARAM_INT);
        $stmt->bindParam(':agentId', $data['agentId'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':tof', $data['typeOfService'], PDO::PARAM_STR);
        $stmt->bindParam(':size', $data['size'], PDO::PARAM_STR);
        $stmt->bindParam(':reason', $data['reason'], PDO::PARAM_STR);
        $stmt->bindParam(':supportFile', $data['supportFile'], PDO::PARAM_STR);
        $stmt->bindParam(':timeFrame', $data['timeFrame'], PDO::PARAM_STR);
        $stmt->bindParam(':date', $data['date'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $result = $stmt->execute();
        if($result === true){
            $ticketActions = (array) json_decode(json_encode($dataObject['ticketActions']),true);
            foreach($ticketActions as $key => $value){
                $sql = "INSERT INTO request_action (request_id,action) VALUES (:requestId,:action)";
                $stmt = $this->connect()->prepare($sql);
                $stmt->bindParam(':requestId', $ticketId, PDO::PARAM_STR);
                $stmt->bindParam(':action', $value, PDO::PARAM_STR);
                $stmt->execute();
            }
            $data = array(
                "message" => "Ticket created successfully",
            );
            return $data;
        }else {
            $data = array(
                "message" => "Error Ticket not created",
            );
            return $data;
        }
        
    }
    
    public function fetchSingleTicket($request_id){
        $sql = "SELECT * FROM request WHERE request_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id",$request_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function fetchAllTickets(){
        $sql = "SELECT * FROM request";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;  
    }
    public function fetchTicketActions($ticket_id){
        $sql = "SELECT * FROM request_action WHERE request_id = :id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":id",$ticket_id);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    public function displayAssignedTickets($agentId){
        $sql = "SELECT * FROM assigned WHERE agentid = :agentId";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agentId",$agentId);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count == 0){
            $data = array(
                "message" => "No Tickets assigned yet",
            );
            return $data;
        }
        else{
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }
    }
}

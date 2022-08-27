<?php
class TicketController extends Ticket {

    //incase ticket needs to be merged with actions
    public function mergerTicketwithActions(array $ticketInfo,array $ticketAction):array{

        $ticket = array(array_merge($ticketInfo, $ticketAction));

        return $ticket;
    }

    public function ticketValidation($dataObject):array{

        $ticket = (array) json_decode(json_encode($dataObject),true);

        $ticketActions = (array) json_decode(json_encode($dataObject['ticketActions']),true);

        $errors = [];

        if(empty($ticket['clientId'])){
            $errors[] = "clientId is required";
        }
        if(empty($ticket['agentId'])){
            $errors[] = "agentId is required";
        }
        if(empty($ticket['title'])){
            $errors[] = "title is required";
        }
        if(empty($ticket['typeOfService'])){
            $errors[] = "typeOfService is required";
        }
        if(empty($ticket['size'])){
            $errors[] = "size is required";
        }
        if(empty($ticket['reason'])){
            $errors[] = "reason is required";
        }
        if(empty($ticket['supportFile'])){
            $errors[] = "supportFile is required";
        }
        if(empty($ticket['timeFrame'])){
            $errors[] = "timeFrane is required";
        }
        if(empty($ticket['date'])){
            $errors[] = "date is required";
        }
        if(empty($ticket['status'])){
            $errors[] = "status is required";
        }
        if(empty($ticketActions)){
            $errors[] = "ticket actions cant be empty";
        }

        return $errors;
    }

    public function getAllTickets():array{

        $row = $this->fetchAllTickets();

        return $row;
    }

    public function getTicketActions($ticket_id):array{

        $row = $this->fetchTicketActions($ticket_id);

        return $row;
    }

    public function createTicketController(string $method):array{

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input"),true);

                $errors = $this->ticketValidation($data);

                if(!empty($errors)){

                    http_response_code(422);

                    $data = array(
                        "message" => $errors,
                    );

                    return $data;

                    break;
                }

                $result = $this->createTicket($data);

                return $result;

                break;

            default:

                http_response_code(405);

                header("Allow: POST");

        }

    }

    public function ticketprocessCollectionRequest(string $method):array {

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $id = $data['id'] ?? NULL;

                $ticket = $this->fetchSingleTicket($id);

                return $ticket;

            case "GET":

                $tickets = $this->fetchAllTickets();

                return $tickets;

                break;
            
            default:

                http_response_code(405);

                header("Allow: POST, GET");
        }
    }

    public function ticketActionprocessCollectionRequest(string $method):array {

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $id = $data['id'] ?? NULL;

                $ticketActions = $this->getTicketActions($id);

                return $ticketActions;

            default:
                
                http_response_code(405);

                header("Allow: POST");

        }
    }

    public function getAssignedTickets(string $method):array{

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $agentId = $data['id'] ?? NULL;

                $tickets = $this->displayAssignedTickets($agentId);

                return $tickets;

                break;

            default:

                http_response_code(405);

                header("Allow: POST");

        }

    }
}
<?php
class AdminController extends Admin {
    public function processRequest(string $method, ?string $id):void {

        if($id){


        }else{

            $this->processCollectionRequest($method);

        }
    }

    public function getUnverrifiedAgents():array{

        $row = $this->fetchUnverifiedAgents();

        return $row;
    }

    public function getAgents(string $method):array{

        switch($method){ 

            case "GET":

                $agents = $this->fetchVerifiedAgents();

                return $agents;

                break;

            case "POST" :

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $agent = $this->agentDetails($data['id']);
                
                return $agent;

                break;

            case "DELETE" :

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $result = $this->deleteAgent($data['id']);
                
                return $result;

                break;

            case "PATCH":
                
                $data = (array) json_decode(file_get_contents("php://input") , true);

                $result = $this->updateAgentStatus($data['agentid'],$data['status']);
                
                return $result;

                break;

            default:

                http_response_code(405);

                header("Allow: POST, GET, DELETE, PATCH");
        }
    }

    // handles admin login 

    private function processCollectionRequest(string $method):void {

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $this->adminLoginController($data['email'],$data['password']);
                
                break;

            default:

                http_response_code(405);

                header("Allow: POST");
        }
    }

    public function adminLoginController($useremail,$pwd){

        $row = $this->adminlogin($useremail);

        print_r($row);

        $Password = $row[0]["password"];

        if(password_verify($pwd,$Password) == true){

            http_response_code(201);

            echo json_encode(["message" => "Login succesful"]);

        }
        else{

            http_response_code(404);

            echo json_encode(["message" => "Login Error"]);
        }
    }

    public function assignAgentController(string $method):array {
        
        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $result = $this->assignAgent($data['agentid'],$data['ticketid']);

                return $result;

                break;

            default:

                http_response_code(405);

                header("Allow: POST");

        }

    }

    public function createInvoiceController(string $method):array{

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $result = $this->createInvoice($data);

                return $result;

                break;

            default:

                http_response_code(405);

                header("Allow: POST");
        }

    }
}
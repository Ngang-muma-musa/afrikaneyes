<?php

class ClientController extends Client {

    public function clientValidation($dataObject):array{

        $data = (array) json_decode(json_encode($dataObject),true);

        $errors = [];

        if(empty($data['firstName'])){
            $errors[] = "FirstName is required";
        }
        if(empty($data['lastName'])){
            $errors[] = "LastName is required";
        }
        if(empty($data['userName'])){
            $errors[] = "userName is required";
        }
        if(empty($data['dob'])){
            $errors[] = "Date of birth is required";
        }
        if(empty($data['gender'])){
            $errors[] = "Gender is required";
        }
        if(empty($data['address'])){
            $errors[] = "Address is required";
        }
        if(empty($data['phoneNumber'])){
            $errors[] = "Phone Number is required";
        }
        if(empty($data['password'])){
            $errors[] = "password is required";
        }
        if(empty($data['email'])){
            $errors[] = "Email is required";
        }
        if(empty($data['profileImage'])){
            $errors[] = "Profile Image is required";
        }

        return $errors;

    }

    public function hiddenInfoValidation($data):array{

        $errors = [];

        if(empty($data['requestId'])){
            $errors[] = "RequestId is required";
        }
        if(empty($data['location'])){
            $errors[] = "Location is required";
        }
        if(empty($data['contact'])){
            $errors[] = "contact is required";
        }
        if(empty($data['viewDate'])){
            $errors[] = "view Date is required";
        }

        return $errors;
    }

    public function clientProcessRequest(string $method):void{

        switch($method){

            case "POST":
                
                $data = (array) json_decode(file_get_contents("php://input") , true);

                $this->clientLoginController($data['email'],$data['password']);
                
                break;

            default:

                http_response_code(405);

                header("Allow: POST");

        }

    }
    
    public function clientRegistrationController(String $method): array{
        
        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input"),true);

                $errors = $this->clientValidation($data);

                if(!empty($errors)){

                    http_response_code(422);

                    $data = array(
                        "message" => $errors,
                    );

                    return $data;

                    break;
                }

                $result = $this->clientRegistration($data);

                return $result;
                
            default:
                
                http_response_code(405);

                header("Allow: POST");

        }

    }

    public function clientLoginController($useremail,$pwd){

        $row = $this->clientLogin($useremail);

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

    public function createHiddenInfoController(string $method){

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input"),true);

                $errors = $this->hiddenInfoValidation($data);

                if(!empty($errors)){

                    http_response_code(422);

                    $data = array(
                        "message" => $errors,
                    );

                    return $data;

                    break;
                }

                $result = $this->createHidddenInfo($data);

                return $result;

        }
    }

}
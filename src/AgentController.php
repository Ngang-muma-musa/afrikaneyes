<?php
class AgentController extends Agent {

    public function getValidationErrors($dataObject):array{

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
        if(empty($data['idFront'])){
            $errors[] = "Id front is required";
        }
        if(empty($data['idBack'])){
            $errors[] = "Id back is required";
        }
        if(empty($data['status'])){
            $errors[] = "status is required";
        }

        return $errors;
    }

    public function reportValidation($dataObject):array{
        $errors = [];

        $report = (array) json_decode(json_encode($dataObject),true);

        $reportProof = (array) json_decode(json_encode($dataObject['proof']),true);

        if(empty($report['introduction'])){
            $errors[] = "Introduction cannot be empty";
        }
        if(empty($report['body'])){
            $errors[] = "body cannot be empty";
        }
        if(empty($report['conclusion'])){
            $errors[] = "conclusion cannot be empty";
        }
        if(empty($reportProof)){
            $errors[] = " Report Proof cannot be empty";
        }

        return $errors;
    }

    public function agentRegistrationController(string $method):array{
        
        switch($method){

            case "POST" :

                $data = (array) json_decode(file_get_contents("php://input") , true);

                $errors = $this->getValidationErrors($data);

                if(!empty($errors)) {

                    http_response_code(422);

                    $data = array(
                        "message" => $errors,
                    );

                    return $data;

                    break;

                }

                $result = $this->agentRegistration($data);

                return $result;

                break;

            default:

                http_response_code(405);

                header("Allow: POST");
        }

    }

    public function uploadReportController(string $method):array{

        switch($method){

            case "POST":

                $data = (array) json_decode(file_get_contents("php://input"),true);

                $errors = $this->reportValidation($data);

                if(!empty($errors)){

                    http_response_code(422);

                    $data = array(
                        "message" => $errors,
                    );

                    return $data;

                    break;

                }

                $result = $this->uploadReport($data);

                return $result;

                break;
        }

    }
}
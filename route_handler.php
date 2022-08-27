<?php
function routeHandler($request_uri):void{

    $adminController = new AdminController();

    $ticketController = new TicketController();

    $agentController = new AgentController();

    $clientController = new ClientController();

    switch($request_uri[2]){
        case "admin":

            $id = $request_uri[3] ?? NULL;

            $adminController->processRequest($_SERVER["REQUEST_METHOD"], $id);

            break;

        case "unverifiedagents":

            echo json_encode($adminController->getUnverrifiedAgents());

            break;
 
        case "agents":

            // to get all agents pass get request without id 
            // to get particulat agent pass post request with id
            // to delete agent pass delete request with id
            // patch to update agent status

            echo json_encode($adminController->getAgents($_SERVER["REQUEST_METHOD"]));

            break;

        case "assignagent":

            echo json_encode($adminController->assignAgentController($_SERVER["REQUEST_METHOD"]));
            
            break;

        case "createticket":

            echo json_encode($ticketController->createTicketController($_SERVER["REQUEST_METHOD"]));

            break;
            
        case "tickets" :

            $id = $request_uri[3] ?? NULL;

            echo json_encode($ticketController->ticketprocessCollectionRequest($_SERVER["REQUEST_METHOD"]));

            break;

        case "ticketactions":
            
            // get the actions of the ticket that is what the client wants you to do on site

            echo json_encode($ticketController->ticketActionprocessCollectionRequest($_SERVER["REQUEST_METHOD"]));

            break;

        case "assignedtickets":

            // get tickets assigned to a particular agent

            echo json_encode($ticketController->getAssignedTickets($_SERVER["REQUEST_METHOD"]));

            break;

        case "createreport":

            echo json_encode($agentController->uploadReportController($_SERVER["REQUEST_METHOD"]));

            break;

        case "createinvoice":

            echo json_encode($adminController->createInvoiceController($_SERVER["REQUEST_METHOD"]));
            
            break;

        case "agentregistration":

            echo json_encode($agentController->agentRegistrationController($_SERVER["REQUEST_METHOD"]));

            break;

        case "clientregistration":

            echo json_encode($clientController->clientRegistrationController($_SERVER["REQUEST_METHOD"]));

            break;

        case "hiddeninfo":

            echo json_encode($clientController->createHiddenInfoController($_SERVER["REQUEST_METHOD"]));

            break;

    }
    
}
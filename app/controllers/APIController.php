<?php
declare(strict_types=1);
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;



class APIController extends \Phalcon\Mvc\Controller
{

    /**
     * Simple GET API Request
     *
     * @method GET
     * @link /apis/get
     */
    public function housesAction()
    {
        // Disable View File Content
        $this->view->disable();

        // Getting a response instance
        $response = new Response();

        // Getting a request instance
        $request = new Request();

        // Check whether the request was made with method GET ( $this->request->isGet() )
        if ($request->isGet()) {

            $houses  = Houses::find();


            if ($houses) {
                // Use Model for database Query
                $returnData = array();

                foreach ($houses as $house) {
                    $returnData[] = array(
                        'id'   => $house->id,
                        'street' => $house->street,
                        'number' => $house->number,
                        'addition' => $house->addition,
                        'zipCode' => $house->zipCode,
                        'city' => $house->city,
                         'rooms' => Rooms::find([
                             "houseID = '$house->id'"
                         ])
                    );
                }
                // Set status code
                $response->setStatusCode(200, 'OK');

                // Set the content of the response
                $response->setJsonContent(["House" => $returnData ]);

            } else {

                // Set status code
                $response->setStatusCode(400, 'Bad Request');

                // Set the content of the response
                $response->setJsonContent(["status" => false, "error" => "Cannot get data from database"]);
            }

        } else {

            // Set status code
            $response->setStatusCode(405, 'Method Not Allowed');

            // Set the content of the response
            // $response->setContent("Sorry, the page doesn't exist");
            $response->setJsonContent(["status" => false, "error" => "Method Not Allowed"]);
        }

        // Send response to the client
        $response->send();
    }



}


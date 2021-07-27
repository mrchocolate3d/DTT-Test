<?php
declare(strict_types=1);
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;
$_SERVER['HTTP_AUTHORIZATION'] = 'dsdJHdb8JKY6756fgU';



class APIController extends \Phalcon\Mvc\Controller
{
    /**
     * Authentication method to check the username and password passed through
     */
    public function Auth(){
        $userName = $this->request->getServer('PHP_AUTH_USER');
        $password = $this->request->getServer('PHP_AUTH_PW');

        $user = Users::findFirst(
            [
                'columns'    => '*',
                'conditions' => 'name = ?1 AND password = ?2',
                'bind'       => [
                    1 => $userName,
                    2 => $password,
                ]
            ]
        );

        if ($user !== null){
            $auth = [
                "roleID"       => $user->roleID,
                "username" => $user->name
            ];
        } else {
            $auth = null;
        }

        return $auth;

    }

    /**
     * Simple GET API Request
     *
     * @method GET
     * @link /apis/get
     */
    public function getHousesAction()
    {
        $returnData = array();

        // Disable View File Content
        $this->view->disable();

        // Getting a response instance
        $response = new Response();

        // Getting a request instance
        $request = new Request();

        // Check whether the request was made with method GET ( $this->request->isGet() )
        if ($request->isGet()) {
            $user = $this->Auth();

            if ($user == null) {
                // Set status code
                $response->setStatusCode(400, 'Bad Request');

                // Set the content of the response
                $response->setJsonContent(["status" => false, "error" => "Cannot get data from database please check credentials"]);
            } else if (true) {
                $houses = Houses::find();

                if ($houses) {
                    // Use Model for database Query

                    foreach ($houses as $house) {
                        $returnData[] = array(
                            'id' => $house->id,
                            'street' => $house->street,
                            'number' => $house->number,
                            'addition' => $house->addition,
                            'zipCode' => $house->zipCode,
                            'city' => $house->city,
                            'rooms' => Rooms::find(array(
                                "bind" => ["id" => $house->id],
                                'conditions' => 'houseID = :id:',
                                'columns' => 'type, width, length, height'
                            ))
                        );
                    }
                    // Set status code
                    $response->setStatusCode(200, 'OK');

                }


            // Set the content of the response
            $response->setJsonContent($returnData);
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


    /**
     * @method POST
     * @link /apis/get
     */
    public function houseAction()
    {
        $this->view->disable();


        $response = new Response();

        // Getting a request instance
        $request = new Request();

        if ($request->isPost()) {
            $house = new Houses();

            $house->street =  $this->request->getPut("street");
            $house->number =  $this->request->getPut("number");
            $house->addition =  $this->request->getPut("addition");
            $house->zipCode =  $this->request->getPut("zipCode");
            $house->city =  $this->request->getPut("city");
            $house->save();
            $rooms = $this->request->getPut("rooms");

            $RoomHouse = Houses::findFirst(
                [
                    'columns'    => '*',
                    'conditions' => 'street = ?1 AND number = ?2',
                    'bind'       => [
                        1 => $house->street,
                        2 => $house->number,
                    ]
                ]
            );

            foreach ($rooms as $room){
                $roomDB = new Rooms();
                $roomDB->width = $room['width'];
                $roomDB->length = $room['length'];
                $roomDB->height = $room['
                '];
                $roomDB->type = $room['type'];
                $roomDB->houseID = $RoomHouse->id;
                $roomDB->save();

            }

            $response->setStatusCode(200, 'OK');

            // Set the content of the response
            $response->setJsonContent(["Status" => "Success", "House" => $house, "Rooms" => $rooms ]);

        } else {

            // Set status code
            $response->setStatusCode(400, 'Bad Request');

            // Set the content of the response
            $response->setJsonContent(["status" => false, "error" => "Invalid Email and Password."]);
        }
        $response->send();
    }

    public function deleteRoomAction(){

        // Disable View File Content
        $this->view->disable();

        // Getting a response instance
        $response = new Response();

        // Getting a request instance
        $request = new Request();

        if ($request->isDelete()) {
            $street =  $this->request->getPut("street");
            $number =  $this->request->getPut("number");
            $addition =  $this->request->getPut("addition");
            $zipCode =  $this->request->getPut("zipCode");
            $roomOrder = $this->request->getPut("roomOrder");
            if ($this->request->getPut("addition") == null){
                $House = Houses::findFirst(
                    [
                        'columns'    => '*',
                        'conditions' => 'street = ?1 AND number = ?2 AND zipCode = ?4',
                        'bind'       => [
                            1 => $street,
                            2 => $number,
                            4 => $zipCode,
                        ]
                    ]
                );
            } else {
                $House = Houses::findFirst(
                    [
                        'columns'    => '*',
                        'conditions' => 'street = ?1 AND number = ?2 AND addition = ?3 AND zipCode = ?4',
                        'bind'       => [
                            1 => $street,
                            2 => $number,
                            3 => $addition,
                            4 => $zipCode,
                        ]
                    ]
                );
            }

            $Room = Rooms::find(
                [
                    'columns'    => '*',
                    'conditions' => 'houseID = ?1',
                    'bind'       => [
                        1 => $House->id,
                    ]
                ]
            );

            $RoomToDelete = new Rooms();
            $count = 1;
            foreach ($Room as $x){
                if($count == $roomOrder){
                    $RoomToDelete = $x;
                }
                $count++;
            }
            //$RoomToDelete->delete();

            // Set status code
            $response->setStatusCode(200, 'OK');

            // Set the content of the response
            $response->setJsonContent([$Room]);
        } else {
            // Set status code
            $response->setStatusCode(400, 'Bad Request');

            // Set the content of the response
            $response->setJsonContent(["status" => false, "error" => "Cannot get data from database"]);
        }
        $response->send();

    }

    public function filterHousesAction(){

        // Disable View File Content
        $this->view->disable();

        // Getting a response instance
        $response = new Response();

        // Getting a request instance
        $request = new Request();

        if ($request->isGet()) {
            $search =  $this->request->getPut("search");
            $BedCount =  $this->request->getPut("minimalBedroomCount");
            $ToiletCount =  $this->request->getPut("minimalToiletCount");
            $count = $this->db->fetchOne('select houseID,type, count(type) as total FROM rooms GROUP BY houseID, type HAVING count(type) >2');

            $houses  = Houses::count(
                array(
                        'column' => 'id',
                )
            );
            // Set status code
            $response->setStatusCode(200, 'OK');

            // Set the content of the response
            $response->setJsonContent($count);

        }  else {

            // Set status code
            $response->setStatusCode(400, 'Bad Request');

            // Set the content of the response
            $response->setJsonContent(["status" => false, "error" => "Cannot get data from database"]);
        }

        $response->send();

    }


}


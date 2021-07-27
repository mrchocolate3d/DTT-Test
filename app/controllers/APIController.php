<?php
declare(strict_types=1);
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Manager;



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
                "userID" => $user->id,
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
            $user = $this->Auth();

            if ($user == null) {
                // Set status code
                $response->setStatusCode(400, 'Bad Request');

                // Set the content of the response
                $response->setJsonContent(["status" => false, "error" => "Cannot get data from database please check credentials"]);
            } else if (true) {
                $house = new Houses();

                $house->street =  $this->request->getPut("street");
                $house->number =  $this->request->getPut("number");
                $house->addition =  $this->request->getPut("addition");
                $house->zipCode =  $this->request->getPut("zipCode");
                $house->city =  $this->request->getPut("city");
                $house->userID =  $user['userID'];
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
                    $roomDB->height = $room['height'];
                    $roomDB->type = $room['type'];
                    $roomDB->houseID = $RoomHouse->id;
                    $roomDB->save();

                }
                // Set the content of the response
                $response->setJsonContent(["Status" => "Success", "House" => $house, "Rooms" => $rooms ]);
            }

            $response->setStatusCode(200, 'OK');


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
            $user = $this->Auth();

            if ($user == null) {
                // Set status code
                $response->setStatusCode(400, 'Bad Request');

                // Set the content of the response
                $response->setJsonContent(["status" => false, "error" => "Cannot get data from database please check credentials"]);
            } else if (true) {

                $street =  $this->request->getPut("street");
                $number =  $this->request->getPut("number");
                $addition =  $this->request->getPut("addition");
                $zipCode =  $this->request->getPut("zipCode");
                $roomOrder = $this->request->getPut("roomOrder");

                if ($this->request->getPut("addition") == null && $roomOrder !== 0){
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
                if ($roomOrder > 0 ){
                    if($House->userID == $user['userID'] || $user['roleID'] == 2){
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
                        $RoomToDelete->delete();
                        // Set status code
                        $response->setStatusCode(200, 'OK');

                        // Set the content of the response
                        $response->setJsonContent('Room has been deleted successfully');
                    }
                } else if ($roomOrder == 0) {
                    $House->delete();
                    // Set status code
                    $response->setStatusCode(200, 'OK');

                    // Set the content of the response
                    $response->setJsonContent('House has been removed from listing');
                } else {
                    // Set status code
                    $response->setStatusCode(401, 'OK');

                    // Set the content of the response
                    $response->setJsonContent('House was not deleted from the database please contact an administrator');
                }
            }
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
            if($BedCount !== null && $ToiletCount == null && $search == null){
                $results = $this->db->fetchAll(
                    "select houseID,type, count(type) as c FROM rooms where type = 'bedroom' GROUP BY houseID, type HAVING count(type) >='$BedCount'"
                );
                $returnData = $this->getHousesFromFilter($results);
            } else if ($BedCount == null && $ToiletCount !== null && $search == null){
                $results = $this->db->fetchAll(
                    "select houseID,type, count(type) as c FROM rooms where type = 'toilet' GROUP BY houseID, type HAVING count(type) >='$ToiletCount'"
                );
                $returnData = $this->getHousesFromFilter($results);
            } else if ($BedCount !== null && $ToiletCount !== null && $search == null){
                $results = $this->db->fetchAll(
                    "select houseID,type, count(type) as c FROM rooms where type = 'toilet' and type = 'bedroom' GROUP BY houseID, type HAVING count(type) >='$ToiletCount'"
                );
                $returnData = $this->getHousesFromFilter($results);
            } else {
                $returnData = 'All values are null enter a filter';
            }

            // Set status code
            $response->setStatusCode(200, 'OK');

            // Set the content of the response
            $response->setJsonContent($returnData);

        }  else {

            // Set status code
            $response->setStatusCode(400, 'Bad Request');

            // Set the content of the response
            $response->setJsonContent(["status" => false, "error" => "Cannot get data from database"]);
        }
        $response->send();

    }

    public function getHousesFromFilter($results): array
    {
        $returnData = array();
        foreach ($results as $result){
            $house = Houses::findFirst(
                [
                    "id = '$result[houseID]'"
                ]
            );
            $returnData[] = array(
                'id' => $house->id,
                'street' => $house->street,
                'number' => $house->number,
                'addition' => $house->addition,
                'zipCode' => $house->zipCode,
                'city' => $house->city,
                'rooms' => Rooms::find(array(
                    "bind" => ["id" => $result['houseID']],
                    'conditions' => 'houseID = :id:',
                    'columns' => 'type, width, length, height'
                ))
            );
        }
        return $returnData;
    }


}


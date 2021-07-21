<?php
declare(strict_types=1);
use Phalcon\Http\Response;
use Phalcon\Http\Request;

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
        // https://docs.phalcon.io/3.4/en/response.html
        $response = new Response();

        // Getting a request instance
        // https://docs.phalcon.io/3.4/en/request
        $request = new Request();

        // Check whether the request was made with method GET ( $this->request->isGet() )
        if ($request->isGet()) {
            $name = $request->getPost("name");
            $password = $request->getPost("password");

            $phql = "SELECT * FROM users ORDER BY name";
            $user  = Users::find([
                'order' => 'name'
            ]);

            /* Check user exist in database table
            $user = Users::findFirst([
                'conditions' => 'name = ?1 AND password = ?2',
                'bind' => [
                    1 => $name,
                    2 => $password,
                ]
            ]);*/

            if ($user) {

                // Use Model for database Query
                $returnData = array();
                foreach ($user as $robot) {
                    $returnData[] = array(
                        'id'   => $robot->id,
                        'name' => $robot->name
                    );
                }
                //echo json_encode($returnData);

                // Set status code
                $response->setStatusCode(200, 'OK');

                // Set the content of the response
                $response->setJsonContent(["status" => true, "error" => false, "message" => "Login Successful. :)", "data" => $returnData ]);

            } else {

                // Set status code
                $response->setStatusCode(400, 'Bad Request');

                // Set the content of the response
                $response->setJsonContent(["status" => false, "error" => "Invalid Email and Password."]);
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


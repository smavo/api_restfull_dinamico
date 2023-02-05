<?php

require_once "models/post.model.php";
require_once "models/connection.php";

class PostController
{

    /*===== Peticion POST para crear datos =====*/
    static public function postData($table, $data) {

        $response = PostModel::postData($table, $data);

        $return = new PostController();
        $return->fncResponse($response);
    }

    /*====== Peticion POST para registrar usuario =====*/
    static public function postRegister($table, $data, $suffix){

        if(isset($data["password_".$suffix]) && $data["password_".$suffix] != null){

            $crypt = crypt($data["password_".$suffix], '$2a$07$azybxcags23425sdg231nd1sm4rt$');

			$data["password_".$suffix] = $crypt;

            $response = PostModel::postData($table, $data);

            $return = new PostController();
            $return -> fncResponse($response);

        } 

    }


    /*===== Respuestas del controlador =====*/
    public function fncResponse($response) {

        if (!empty($response)) {

            $json = array(
                'status' => 200,
                'results' => $response
            );
        } else {


            $json = array(

                'status' => 404,
                'results' => 'Not Found',
                'method' => 'post'

            );
        }

        echo json_encode($json, http_response_code($json["status"]));
    }
}

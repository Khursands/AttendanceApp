<?php

namespace GM_HR {

    class Security {

        public $conn;

        public function __construct() {
            $this->conn = new Connection();
        }

        public static function parseIncomingParams() {
            $parameters = array();

            // first of all, pull the GET vars
            if (isset($_SERVER['QUERY_STRING'])) {
                parse_str($_SERVER['QUERY_STRING'], $parameters);
            }

            // next, do the cookies
            foreach ($_COOKIE as $key => $val) {
                $parameters[$key] = $val;
            }

            // now how about PUT/POST bodies? These override what we got from GET
            $body = file_get_contents("php://input");
            $content_type = false;

            if (isset($_SERVER['CONTENT_TYPE'])) {
                $content_type = $_SERVER['CONTENT_TYPE'];
            }

            switch ($content_type) {
                case "application/json; charset=UTF-8":
                case "application/json; charset=utf-8":
                case "application/json":
                    // Logger::log($this->conn, $_SERVER['REQUEST_URI'] . " - " . $_SERVER['REQUEST_METHOD'], "body", $body);
                    $body_params = json_decode($body);
                    if ($body_params) {
                        foreach ($body_params as $param_name => $param_value) {
                            $parameters[$param_name] = $param_value;
                        }
                    }
                    break;
                case "application/x-www-form-urlencoded":
                    parse_str($body, $postvars);
                    foreach ($postvars as $field => $value) {
                        $parameters[$field] = $value;
                    }
                    break;
                default:
                    // we could parse other supported formats here
                    break;
            }

            return $parameters;
        }

        public static function urlsafeB64Decode($input) {
            $remainder = \strlen($input) % 4;
            if ($remainder) {
                $padlen = 4 - $remainder;
                $input .= \str_repeat('=', $padlen);
            }
            return \base64_decode(\strtr($input, '-_', '+/'));
        }

        public function checkToken() {
            header("Content-Type: application/json; charset=UTF-8");

            $headers = apache_request_headers();
            $token = isset($headers["Authorization"]) ? $headers["Authorization"] : "";
            //var_dump($token);die();
            if (substr($token, 0, 6) === "Basic ") {

                $token = str_replace("Basic ", "", $token);
                //$token = base64_decode($token);
                
                if ($token != "") {
                    $parts = explode(":", $token);
                    if (count($parts) > 1) {
                        $userName = $parts[0];
                        $password = $parts[1];

                        if ($userName != "" && $password != "" && $userName == "apiuser" && $password == "3184568b-b489-45e4-85cf-ee7f4dd9cbcd") {
                            return true;
                        } else {
                            http_response_code(400);
                            echo json_encode(
                                    array("message" => "Authorization is invalid 1.")
                            );
                            die();
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(
                                array("message" => "Authorization is invalid 2.")
                        );
                        die();
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(
                            array("message" => "Authorization is invalid 3.")
                    );
                    die();
                }
            } else {
                http_response_code(400);
                echo json_encode(
                        array("message" => "Authorization is missing 4.")
                );
                die();
            }
        }

    }

}

?>
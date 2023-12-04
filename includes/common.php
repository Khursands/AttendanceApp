<?php

namespace GM_HR {

    class Common {

        public static function jsonSuccess($PayLoad = array()) {
            $response['Good'] = true;
            $response['Error'] = null;
            $response['PayLoad'] = $PayLoad;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        public static function jsonError($PayLoad = '') {
            $response['Good'] = false;
            $response['Error'] = null;
            $response['PayLoad'] = $PayLoad;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

    }

}

<?php
namespace GM_HR {
    class Logger {
        public static function Log($e) {
            $t=time();
///var/www/hueysapi.graymath.com/exception
            //C:\\My Drive\\Projects\\Heuys\\hueys\\Hueys-V2\\api\\exception\\
            //file_put_contents("/var/www/hueysapi.graymath.com/exception/".$t.".txt", "ERROR: $e");                    
        }
    }
}
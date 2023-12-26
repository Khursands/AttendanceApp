<?php

namespace GM_HR {

    class Connection {

        public $connection;

        public function __construct($old = false) {
            $dbhost = "localhost";
            $dbuser = "root";
            $dbpass = "";
            $dbname = "graymath";

            $this->connection = new \mysqli($dbhost, $dbuser, $dbpass, $dbname);
        }

        function __destruct() {
            try {
                if ($this->connection) {
                    if ($this->connection->connect_error) {
                        
                    } else {
                        $this->connection->close();
                    }
                }
            } catch (\Exception $e) {
                
            }
        }

        public function query($sql, $name = "", $user_id = 0) {
            $log_id = 0;

            try {
               /* if (strpos($sql, "INSERT INTO log") === false) {
                    $log_id = Logger::createSQLLog($this->connection, $name, $sql, $user_id);
                }*/

                if ($q = $this->connection->query($sql)) {
                    // Logger::finishSQLLog($this->connection, $log_id);
                    return $q;
                } else {
                    if (strpos($sql, "INSERT INTO log") === false) {
                        Logger::log($this, "SQL ERROR", $this->connection->error, $sql);
                    }
                }
            } catch (\Exception $e) {
                // Logger::errorSQLLog($this->connection, $log_id, $e->getMessage());
            }
        }

        public function escape($input) {
            return $this->connection->real_escape_string($input);
        }

        public function insert_id() {
            return $this->connection->insert_id;
        }

        public function close() {
            //$this->connection->close();
        }

    }

}
?>
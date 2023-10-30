<?php

    require_once("Config.php");

    class DatabaseConnection {
        private $db_manager;
        private $db_fault;

        // Read database credentails from config file and establish connection
        function __construct() {
            $this->db_fault = false;
            $host = Config::$database_connection["host"];
            $user = Config::$database_connection["user"];
            $database = Config::$database_connection["database"];
            $password = Config::$database_connection["pass"];
            $port = Config::$database_connection["port"];
            
            $this->db_manager = pg_connect("host=$host port=$port dbname=$database user=$user password=$password");
            if ($this->db_manager) {
                $this->db_fault = false;
            } else {
                $this->db_fault = true;
            }
        }

        // Execute query on DB and return results as an an array
        public function query($query, ...$params) {
            $result = pg_query_params($this->db_manager, $query, $params);

            if($result === false) {
                echo pg_last_error($this->db_manager);
                return false;
            }

            return pg_fetch_all($result);
        }

        // Returns true if there was an error connecting to the DB
        public function dbError() {
            return $this->db_fault;
        }

        // Close database connection
        public function close() {
            pg_close($this->db_manager);
        }

    }

    

?>
<?php

class DB {
    private static $conn=False;
    
    /**
     * Opens datebase connection
     * 
     */
    function connect() {
      if (!(Config::getValue('dbhost') and Config::getValue('dbuser') and Config::getValue('dbpass') and Config::getValue('dbname'))) {
          Error::printCriticalError(STRING_ERROR_DB_NOCONFIG);
      } 
      error_reporting(0);
      self::$conn=new mysqli(Config::getValue('dbhost'),Config::getValue('dbuser'),Config::getValue('dbpass'),Config::getValue('dbname'));
      error_reporting(E_ALL);
      if (mysqli_connect_error()) {
          Error::printCriticalError(STRING_ERROR_DB_NOCONNECT);
      }        
    }
}
DB::connect();
?>

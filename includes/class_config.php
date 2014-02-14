<?php
$config=array();

$config["dbhost"]="localhost";
$config["dbuser"]="dpass";
$config["dbpass"]="dpass";
$config["dbname"]="dpass";

$config["debug"]=True;


class Config {
    private static $config=array();

    /**
     * Returns the value for a config parameter
     * 
     * @param string $name Name of config value
     * @return boolean returns config value or false if there is no config value for the given name
     */
    function getValue($name) {
        if (isset(self::$config[$name])) 
            return self::$config[$name];
        else 
            return False;
    }
    
    function init() {
        global $config;
        // default config values
        self::$config["lang"]="en";
        self::$config["dbengine"]="mysql";
        self::$config["debug"]=False;
        foreach ($config as $key=>$value) {
            self::$config[$key]=$value;
        }
    }
    
}

Config::init();

?>

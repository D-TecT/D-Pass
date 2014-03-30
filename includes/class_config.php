<?php
$config=array();

include ('config.php');


class Config {
    private static $config=array();

    /**
     * Returns the value for a config parameter
     * 
     * @param string $name Name of config value
     * @return boolean returns config value or false if there is no config value for the given name
     */
    static function getValue($name) {
        if (isset(self::$config[$name])) 
            return self::$config[$name];
        else {
            return DB::getConfig($name);
        }
    }
    
    static function init() {
        global $config;
        // default config values
        self::$config["lang"]="en";
        self::$config["dbengine"]="mysql";
        self::$config["debug"]=False;
        self::$config["sessiontimeout"]=3600;
        self::$config["userpasswordrules"]=array('[a-z]','[A-Z]','[0-9]');
        self::$config["userpasswordminlen"]=8;
                
        foreach ($config as $key=>$value) {
            self::$config[$key]=$value;
        }
    }
    
}

Config::init();

?>

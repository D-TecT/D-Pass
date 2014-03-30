<?php

class JSONResponse {
    private static $responsecode=False;
    private static $action=False;
    private static $data=False;
    
    public static function setResponsecode($code) {
      self::$responsecode=$code;
    }
    
    public static function setAction($action) {
        self::$action=$action;
    }

    public static function setData($data) {
        self::$data=$data;
    }
    
    public static function response() {
      $arr=array();
      $arr['responsecode']=self::$responsecode;
      $arr['action']=self::$action;
      $arr['data']=self::$data;
      print json_encode($arr);
    }
    
}

?>

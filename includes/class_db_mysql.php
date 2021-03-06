<?php

class DB {
    private static $conn=False;
    
    /**
     * Opens datebase connection
     * 
     */
    static function connect() {
      if (!(Config::getValue('dbhost') and Config::getValue('dbuser') and Config::getValue('dbpass') and Config::getValue('dbname'))) {
          Error::printCriticalError(STRING_ERROR_DB_NOCONFIG);
      } 
      error_reporting(0);
      self::$conn=new mysqli(Config::getValue('dbhost'),Config::getValue('dbuser'),Config::getValue('dbpass'),Config::getValue('dbname'));
      error_reporting(E_ALL);
      if (mysqli_connect_error()) {
          Error::printCriticalError(STRING_ERROR_DB_NOCONNECT);
      }    
      if (self::$conn->query('create table if not exists `config` (`name` varchar(256) primary key, `value` varchar(1024))')!==True) {
          Error::printCriticalError(STRING_ERROR_DB_NOTABLE);
      }
      self::$conn->query('insert ignore into `config` (`name`,`value`) values ("dbversion","0")');
    }
    
    static function updateDB() {
      $dbversion=intval(self::getConfig('dbversion'));
      if ($dbversion<1) {
        self::$conn->query('create table if not exists `user` (`userid` int auto_increment primary key, `name` varchar(256), `priv` varchar(8192), `pub` varchar(4096), UNIQUE key `name` (`name`))');      
        self::createUser('admin','admin');
        self::$conn->query('create table if not exists `sessions` (`sid` varchar(64) primary key, `spriv` varchar(8192), `userid` int, `timeout` int)');      
        
      }
    }
    
    static function updateUserkey($userid,$priv,$pub) {
      $q=self::$conn->prepare ('update `user` set `priv`=?, `pub`=? where `userid`=?');
      $q->bind_param('ssi',$priv,$pub,$userid);
      $q->execute();
      $q->close();  
    }
    
    static function createUser($name,$pass) {
      $salt=openssl_random_pseudo_bytes(64);
      $pass=$salt.openssl_digest($pass.$salt,'sha512',true);
      $q=self::$conn->prepare ('insert into `user` (`name`,`priv`,`pub`) values (?,?,"EMPTY")');
      $q->bind_param('ss',$name,$pass);
      $q->execute();
      $q->close();  
    }
    
    static function getConfig($name) {
      $q=self::$conn->prepare ('select `value` from `config` where `name`=?');
      $q->bind_param('s',$name);
      $q->execute();
      $q->bind_result($value);
      if ($q->fetch()===True) {
          return $value;
      } else {
          return False;
      }
      $q->close();
    }
    
    static function setConfig($name,$value) {
      $q=self::$conn->prepare ('insert into `config` (`name`,`value`) values (?,?) on duplicate key update `value`=?');
      $q->bind_param('sss',$name,$value,$value);
      $q->execute();
      $q->close();
    }
    
    static function getSession($sid) {
      $timeout=time()+Config::getValue('sessiontimeout');  
      $q=self::$conn->prepare ('delete from `sessions` where timeout<?');
      $t=time();
      $q->bind_param('i',$t);
      $q->execute();
      $q->close();  
      $q=self::$conn->prepare ('update `sessions` set `timeout`=? where `sid`=?');
      $q->bind_param('is',$timeout,$sid);
      $q->execute();
      $q->close();       
      $q=self::$conn->prepare ('select `spriv`,`userid` from `sessions` where `sid`=?');
      $q->bind_param('s',$sid);
      $q->execute();
      $q->bind_result($spriv,$userid);
      if ($q->fetch()===True) {
          return array("spriv"=>$spriv,"userid"=>$userid);
      } else {
          return False;
      }
      $q->close();
    }
    
    static function updateSession($sid,$spriv,$userid) {
      $timeout=time()+Config::getValue('sessiontimeout');  
      $q=self::$conn->prepare ('insert ignore into `sessions` (`sid`,`spriv`,`userid`,`timeout`) values (?,"",0,?)');
      $q->bind_param('si',$sid,$timeout);
      $q->execute();
      $q->close();  
      if ($spriv!=False) {
        $q=self::$conn->prepare ('update `sessions` set `spriv`=? where `sid`=?');
        $q->bind_param('ss',$spriv,$sid);
        $q->execute();
        $q->close();      
      }
      if ($userid!=False) {
        $q=self::$conn->prepare ('update `sessions` set `userid`=? where `sid`=?');
        $q->bind_param('is',$userid,$sid);
        $q->execute();
        $q->close();      
      }            
    }
    
    static function destroySession($sid) {
       $q=self::$conn->prepare ('delete from `sessions` where `sid`=?');
       $q->bind_param('s',$sid);
       $q->execute();
       $q->close();    
    }
    
    static function getUserById($userid) {
      $q=self::$conn->prepare ('select `name`,`priv`,`pub` from `user` where `userid`=?');
      $q->bind_param('i',$userid);
      $q->execute();
      $q->bind_result($name,$priv,$pub);
      if ($q->fetch()===True) {
          return array("name"=>$name,"priv"=>$priv,"pub"=>$pub);
      } else {
          return False;
      }
      $q->close();
    }
    
    static function getUserByName($name) {
      $q=self::$conn->prepare ('select `userid`,`priv`,`pub` from `user` where `name`=?');
      $q->bind_param('s',$name);
      $q->execute();
      $q->bind_result($userid,$priv,$pub);
      if ($q->fetch()===True) {
          return array("userid"=>$userid,"priv"=>$priv,"pub"=>$pub);
      } else {
          return False;
      }
      $q->close();
    }
            
}
DB::connect();
DB::updateDB();
?>

<?php

class Session {
    private static $sessionid=False;
    private static $sessionkey=False;
    private static $userid=0;
    private static $spriv="";
    private static $userpriv=False;
    
    public function getUserid() { return self::$userid; }
    public function getUserpriv() { return self::$userpriv; }
    
    public function createsession() {
        $sid=base64_encode(openssl_random_pseudo_bytes(32));
        $skey=base64_encode(openssl_random_pseudo_bytes(32));
        setcookie("sid",$sid);
        setcookie("skey",$skey);
        DB::updateSession($sid,false,false);
        ob_end_flush();
        header("Location: ".SCRIPT_NAME."?newsession=true");
        die();
    }
    
    public function destroysession() {
      if (!isset($_COOKIE["sid"])) return;
      $sid=$_COOKIE["sid"];
      DB::destroySession($sid);
      self::createsession();
    }
    
    function init() {
        if ((!isset($_COOKIE["sid"]) or  !isset($_COOKIE["skey"])) and isset($_GET["newsession"])) {
            // there should be cookies - if not = error
            Error::printCriticalError(STRING_ERROR_NOCOOKIES);
        } else if (!isset($_COOKIE["sid"]) or  !isset($_COOKIE["skey"])) {
            // no cookies? create session
            self::createsession();
        }
        // read cookies
        self::$sessionid=$_COOKIE["sid"];
        self::$sessionkey=$_COOKIE["skey"];
        $session=DB::getSession(self::$sessionid);
        if ($session===False) {
            // no corresponding session in db (maybe timeout) - create a new one
            self::createsession();    
        }
        // get user information
        self::$userid=$session["userid"];
        self::$spriv=$session["spriv"];
        if (self::$userid==0 and isset($_POST["u"]) and isset($_POST["p"])) {
            // no user logged in and login information found
            $user=DB::getUserByName($_POST["u"]); 
            // unknown username
            if ($user==False) {
                return;
            }    
            if ($user["pub"]=="EMPTY") {
                // user never logged in before - simple pw check
                $salt=  substr($user["priv"], 0,64);
                $hash=  substr($user["priv"], 64);
                if (openssl_digest($_POST["p"].$salt,'sha512',true)==$hash) {
                    // correct password - set userid
                    self::$userid=$user["userid"];
                    DB::updateSession(self::$sessionid,False,self::$userid);
                } else {
                    // wrong password
                    return;
                }
            }
        }
            
    }
}
Session::init();

?>

<?php

class Session {
    private static $sessionid=False; // cookie session id
    private static $sessionkey=False; // cookie key for spriv
    private static $userid=0;
    private static $spriv=""; // private key from session table
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
        if (self::$userid!=0) $user=DB::getUserById(self::$userid); 
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
        if (self::$userid!=0 and isset($_POST["po"]) and isset($_POST["pn1"]) and isset($_POST["pn2"])) {
            // change password
            if ($_POST["pn1"] != $_POST["pn2"]) {
                // New passwords dont match
                Error::setErrorField("pn1",STRING_CHANGEPW_NEWPW_MATCH_ERROR);
                Error::setErrorField("pn2",STRING_CHANGEPW_NEWPW_MATCH_ERROR);
                return;
            }
            // check password rules
            $pwrules=Config::getValue('userpasswordrules');
            $pwlen=Config::getValue('userpasswordminlen');
            $rules=sprintf(STRING_CHANGEPW_NEWPW_RULES,$pwlen,implode(',',$pwrules));
            if (strlen($_POST["pn1"])<$pwlen) {
                // too short
                $error='<span data-tooltip class="has-tip [tip-top tip-bottom tip-left tip-right] [radius round]" title="'.$rules.'">'.STRING_CHANGEPW_NEWPW_SHORT_ERROR.'</span>';
                Error::setErrorField("pn1",$error);    
                return;
            }
            $check=true;
            foreach ($pwrules as $pwrule) {
                if (preg_match('/'.$pwrule.'/', $_POST["pn1"])!==1) $check=false;
            }
            if (!$check) {
                // not all required letters
                $error='<span data-tooltip class="has-tip [tip-top tip-bottom tip-left tip-right] [radius round]" title="'.$rules.'">'.STRING_CHANGEPW_NEWPW_LETTER_ERROR.'</span>';
                Error::setErrorField("pn1",$error);     
                return;
            }
            // new password is ok
            if ($user["pub"]=="EMPTY") {
                // simple old pw check 
                $salt=  substr($user["priv"], 0,64);
                $hash=  substr($user["priv"], 64);
                if (openssl_digest($_POST["po"].$salt,'sha512',true)!=$hash) {
                    // wrong old password
                    Error::setErrorField("po",STRING_CHANGEPW_OLDPW_MATCH_ERROR);
                    return;
                }
                // generate keys
                $config = array(
                    "private_key_bits" => 4096,
                    "private_key_type" => OPENSSL_KEYTYPE_RSA);
                #$res = openssl_pkey_new($config);
                #openssl_pkey_export($res, $privKey);
                #$pubKey=openssl_pkey_get_details($res);
                #$pubKey=$pubKey["key"];
                $salt=openssl_random_pseudo_bytes(32);
                #$pwkey=openssl_pbkdf2($_POST["pn1"],$salt,32,1000,'sha256');
                print $pwkey;
                
            }
            var_dump($user);
                
        }
            
    }
}
Session::init();

?>

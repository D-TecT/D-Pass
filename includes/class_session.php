<?php

class Session {
    private static $sessionid=False; // cookie session id
    private static $sessionkey=False; // cookie key for spriv
    private static $userid=0;
    private static $spriv=False; // private key from session table
    private static $userpriv=False;
    
    public static function getUserid() { return self::$userid; }
    public static function getUserpriv() { return self::$userpriv; }
    
    public static function createsession() {
        $sid=base64_encode(Crypto::getRandomBytes(32));
        $skey=base64_encode(Crypto::getRandomBytes(32));
        setcookie("sid",$sid);
        setcookie("skey",$skey);
        DB::updateSession($sid,false,false);
        ob_end_flush();
        header("Location: ".SCRIPT_NAME."?newsession=true");
        die();
    }
    
    public static function destroysession() {
      if (!GPC::get("sid")) return;
      $sid=GPC::get("sid");
      DB::destroySession($sid);
      self::createsession();
    }
    
    public static function userlogin($username,$password) {
        // no user logged in and login information found
        $user=DB::getUserByName($username); 
        // slow down login attempts
        sleep(3);
        // unknown username
        if ($user==False) {
            JSONResponse::setAction('printerror');
            JSONResponse::setResponsecode(403);
            JSONResponse::setData(STRING_LOGIN_FAIL);
            return False;
        }    
        if ($user["pub"]=="EMPTY") {
            // user never logged in before - simple pw check
            $salt=  substr($user["priv"], 0,64);
            $hash=  substr($user["priv"], 64);
            if (Crypto::Hash($password.$salt,'sha512',true)==$hash) {
                // correct password - set userid
                self::$userid=$user["userid"];
                DB::updateSession(self::$sessionid,False,self::$userid);
            } else {
                // wrong password
                JSONResponse::setAction('printerror');
                JSONResponse::setResponsecode(403);
                JSONResponse::setData(STRING_LOGIN_FAIL);
                return False;
            }
        } else {
            $salt=  substr($user["priv"], 0,32);
            $userpriv=  substr($user["priv"], 32);
            $pwkey=Crypto::pbkdf2( $password,$salt,1000,32,'sha256',true);
            $plainkey=Crypto::decrypt($userpriv,$pwkey);
            if (substr($plainkey,0,4)!=='----') {
                // wrong password 
                JSONResponse::setAction('printerror');
                JSONResponse::setResponsecode(403);
                JSONResponse::setData(STRING_LOGIN_FAIL);
                return False;
            } else {
                // correct password
                self::$userid=$user["userid"];
                $sessionpriv=Crypto::encrypt($plainkey,base64_decode(self::$sessionkey));
                DB::updateSession(self::$sessionid,$sessionpriv,self::$userid);
            }
        }
        JSONResponse::setAction('reload');
        JSONResponse::setResponsecode(200);
        return True;
    }
    
    static function changepw($pw_old,$pw_new1,$pw_new2) {
        if (self::$userid==0) return False;
        // change password
        if ($pw_new1 != $pw_new2) {
            // New passwords dont match
            JSONResponse::setAction('printerror');
            JSONResponse::setResponsecode(428);
            JSONResponse::setData(STRING_CHANGEPW_NEWPW_MATCH_ERROR);
            return False;
        }
        // check password rules
        $pwrules=Config::getValue('userpasswordrules');
        $pwlen=Config::getValue('userpasswordminlen');
        $rules=sprintf(STRING_CHANGEPW_NEWPW_RULES,$pwlen,implode(',',$pwrules));
        if (strlen($pw_new1)<$pwlen) {
            // too short
            JSONResponse::setAction('printerror');
            JSONResponse::setResponsecode(428);
            JSONResponse::setData('<span data-tooltip class="has-tip [tip-top tip-bottom tip-left tip-right] [radius round]" title="'.$rules.'">'.STRING_CHANGEPW_NEWPW_SHORT_ERROR.'</span>');
            return False;
        }
        $check=true;
        foreach ($pwrules as $pwrule) {
            if (preg_match('/'.$pwrule.'/', $pw_new1)!==1) $check=false;
        }
        if (!$check) {
            // not all required letters
            JSONResponse::setAction('printerror');
            JSONResponse::setResponsecode(428);
            JSONResponse::setData('<span data-tooltip class="has-tip [tip-top tip-bottom tip-left tip-right] [radius round]" title="'.$rules.'">'.STRING_CHANGEPW_NEWPW_LETTER_ERROR.'</span>');
            return False;
        }
        // new password is ok
        $user=DB::getUserById(self::$userid); 
        if ($user["pub"]=="EMPTY") {
            // simple old pw check 
            $salt=  substr($user["priv"], 0,64);
            $hash=  substr($user["priv"], 64);
            if (Crypto::Hash($pw_old.$salt,'sha512',true)!=$hash) {
                // wrong old password
                JSONResponse::setAction('printerror');
                JSONResponse::setResponsecode(428);
                JSONResponse::setData(STRING_CHANGEPW_OLDPW_MATCH_ERROR);
                return False;
            }
            // generate keys
            Crypto::generateRSAKey($privKey,$pubKey,4096);
            $salt=Crypto::getRandomBytes(32);
            $pwkey=Crypto::pbkdf2( $pw_new1,$salt,1000,32,'sha256',true);
            $encpriv=Crypto::encrypt($privKey,$pwkey);
            $sessionpriv=Crypto::encrypt($privKey,base64_decode(self::$sessionkey));
            DB::updateUserkey(self::$userid,$salt.$encpriv,$pubKey);
            DB::updateSession(self::$sessionid,$sessionpriv,self::$userid);
        }
        JSONResponse::setAction('reload');
        JSONResponse::setResponsecode(200);
        return True;
    }
    
    static function init() {
        if ((!GPC::get("sid") or !GPC::get("skey")) and GPC::get("newsession")) {
            // there should be cookies - if not = error
            Error::printCriticalError(STRING_ERROR_NOCOOKIES);
        } else if (!GPC::get("sid") or  !GPC::get("skey")) {
            // no cookies? create session
            self::createsession();
        } else if (GPC::get("sid") and GPC::get("skey") and GPC::get("newsession")) {
            // clean newsession URL
            ob_end_flush();
            header("Location: ".SCRIPT_NAME);
            die();
        }
        // read cookies
        self::$sessionid=GPC::get("sid");
        self::$sessionkey=GPC::get("skey");
        $session=DB::getSession(self::$sessionid);
        if ($session===False) {
            // no corresponding session in db (maybe timeout) - create a new one
            self::createsession();    
        }
        // get user information
        self::$userid=$session["userid"];
        self::$spriv=$session["spriv"];
        if (self::$spriv) {
            $tmp=Crypto::decrypt(self::$spriv,base64_decode(self::$sessionkey));
            if (substr($tmp,0,4)=='----') {
                self::$userpriv=$tmp;
            } else {
                // session corrupt - recreate
                Session::destroySession();
            }                    
        }
    }
}
Session::init();

?>

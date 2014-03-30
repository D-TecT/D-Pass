<?php

if (!function_exists("mcrypt_encrypt")) {
#  die(STRING_ERROR_CRYPTO_NOMCRYPT);    
}

class Crypto {

    static public function getRandomBytes($len) {
        return openssl_random_pseudo_bytes($len);
    }
    
    static public function hash($data,$Hash,$raw_output=false) {
        if(!in_array($Hash, openssl_get_md_methods(), true)) {
            return False;
        }
        return openssl_digest($data,$Hash,$raw_output);
    }
    
    static public function generateRSAKey(&$privKey,&$pubKey,$keyLen=4096) {
        $config = array(
             "private_key_bits" => $keyLen,
             "private_key_type" => OPENSSL_KEYTYPE_RSA);
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);
        $tmp=openssl_pkey_get_details($res);
        $pubKey=$tmp["key"];
    }
   
    static public function encrypt($data,$key,$test=false) {
      // for test fixed iv and no padding
      if ($test) {
        $iv="\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F";   
      } else { 
        $iv=self::getRandomBytes(16);    
        // Padding PKCS#7
        $datalen=strlen($data);
        $padlen=16-($datalen%16);
        $data.=str_repeat(chr($padlen), $padlen);
      }  
      // 128 is blocksize, not keysize!
      $out=  mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$key,$data,MCRYPT_MODE_CBC, $iv);
      if ($test)
        return $out;
      else 
        return $iv.$out;  
    }
    
    static public function decrypt($data,$key) {
      // for test fixed iv and no padding
      $iv=  substr($data, 0,16);    
      $data=substr($data,16);
      // 128 is blocksize, not keysize!
      $out=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,$data,MCRYPT_MODE_CBC, $iv);
      $padlen=ord(substr($out,-1));
      return substr($out,0,  strlen($out)-$padlen);
    }
    
    /**
     *  Function to compute PKCS #5 PBKDF2 
     * 
     *  @param string $P password, an octet string
     *  @param string $S salt, an octet string 
     *  @param string $Hash underlying hash function 
     *  @param integer $c iteration count, a positive integer 
     *  @param integer $dkLen length in octets of derived key, a positive integer 
     *  @param bool $raw_output True: Raw output; False: Hex output 
     *  @return string derived key, a dkLen-octet string
     */
    static function pbkdf2($P, $S, $c = 1000, $dkLen = 32, $Hash = 'sha256', $raw_output = false)
    {
        if(!in_array($Hash, hash_algos(), true)) return False;
        if($c <= 0 || $dkLen <= 0) return False;

        $hLen = strlen(hash($Hash, "", true));
        $l = ceil($dkLen / $hLen);

        $DK = "";
        // Block Function
        for($i = 1; $i <= $l; $i++) {
            // Iteration of the Pseudo-Random-Function (PRF) for each Block
            $U_j = $T_i = hash_hmac($Hash, $S . pack("N", $i), $P, true);
            for ($j = 1; $j < $c; $j++) {
                $T_i ^= ($U_j = hash_hmac($Hash, $U_j, $P, true));
            }
            // Concat Results
            $DK .= $T_i;
        }
        return ($raw_output?substr($DK, 0, $dkLen):bin2hex(substr($DK, 0, $dkLen)));
    }          

    
}

// method tests
$hash=Crypto::hash('The quick brown fox jumps over the lazy dog','sha256',true);
$expecthash="d7a8fbb307d7809469ca9abcb0082e4f8d5651e46d3cdb762d02d0bf37c9e592";
if (bin2hex($hash)!==$expecthash) Error::printCriticalError(STRING_ERROR_CRYPTO_HASHCHECK);

$pbkdf2=Crypto::pbkdf2("password","salt",2,20,'sha1');
$expectpbkdf2="ea6c014dc72d6f8ccd1ed92ace1d41f0d8de8957";
if ($pbkdf2!==$expectpbkdf2) Error::printCriticalError(STRING_ERROR_CRYPTO_PBKDF2CHECK);

$aes256=Crypto::encrypt("\x6b\xc1\xbe\xe2\x2e\x40\x9f\x96\xe9\x3d\x7e\x11\x73\x93\x17\x2a","\x60\x3d\xeb\x10\x15\xca\x71\xbe\x2b\x73\xae\xf0\x85\x7d\x77\x81\x1f\x35\x2c\x07\x3b\x61\x08\xd7\x2d\x98\x10\xa3\x09\x14\xdf\xf4",true);
$expectaes256="f58c4c04d6e5f1ba779eabfb5f7bfbd6";
if (bin2hex($aes256)!==$expectaes256) Error::printCriticalError(STRING_ERROR_CRYPTO_AESCHECK);

?>

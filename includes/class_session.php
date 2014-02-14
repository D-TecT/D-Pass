<?php

class Session {
    private static $sessionid=False;
    private static $sessionkey=False;
    function init() {
        if (!isset($_COOKIE["sid"]) and isset($_GET["newsession"])) {
            Error::printCriticalError(STRING_ERROR_NOCOOKIES);
        } else if (!isset($_COOKIE["sid"])) {
            setcookie("sid","test");
            ob_end_flush();
            header("Location: ".SCRIPT_NAME."?newsession=true");
            die();
        }
        var_dump($_POST);
    }
}
Session::init();

?>

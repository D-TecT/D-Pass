<?php

class GPC {
    static function get($name) {
        if (isset($_POST[$name])) return $_POST[$name];
        if (isset($_GET[$name])) return $_GET[$name];
        if (isset($_COOKIE[$name])) return $_COOKIE[$name];
        return False;
    }
}

?>

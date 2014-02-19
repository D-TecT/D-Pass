<?php
define('SCRIPT_NAME',$_SERVER["SCRIPT_NAME"]);
include ('includes/class_config.php');

$lang=Config::getValue("lang");
if ($lang and file_exists('lang/lang.'.$lang.'.php'))
    include ('lang/lang.'.$lang.'.php');
else
    include ('lang/lang.en.php');

include ('includes/class_error.php');

$dbengine=Config::getValue("dbengine");
if ($lang and file_exists('includes/class_db_'.$dbengine.'.php'))
    include ('includes/class_db_'.$dbengine.'.php');
else
    include ('includes/class_db_mysql.php');

include ('includes/class_session.php');

?>

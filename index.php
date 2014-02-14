<?
ob_start();
include("includes/environment.php");

include('templates/site_header.php');

include('templates/site_login.php');

include('templates/site_footer.php');

$content=  ob_get_contents();
ob_end_clean();
print $content;

?>      
        

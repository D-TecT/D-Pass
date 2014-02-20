<?
ob_start();
include("includes/environment.php");

if (isset($_GET["action"]))
    switch ($_GET["action"]) {
        case "logout":
            Session::destroySession();
            break;
    }


include('templates/site_header.php');

if (Session::getUserid()==0) {
    include('templates/site_minimalmenu.php');
    include('templates/site_login.php');
} else if (Session::getUserpriv()==False) {
    include('templates/site_logoutmenu.php');
    include('templates/site_changepassword.php');
}  

include('templates/site_footer.php');

$content=  ob_get_contents();
ob_end_clean();
print $content;

?>      
        

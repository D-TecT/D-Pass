<?
ob_start();
include("includes/environment.php");

switch (GPC::get("action")) {
    case "logout":
        Session::destroySession();
        break;
    case "login":
        if (GPC::get("u") and GPC::get("p")) Session::userlogin(GPC::get("u"),GPC::get("p"));
        break;
    case "changepw":
        if (Session::getUserid()!=0 and GPC::get("po") and GPC::get("pn1") and GPC::get("pn2")) Session::changepw(GPC::get("po"),GPC::get("pn1"),GPC::get("pn2"));
        break;
}

if (!GPC::get('ajax')) {
    HTMLResponse::print_page('header');
    if (Session::getUserid()==0) {
        HTMLResponse::print_page('minimalmenu');
        HTMLResponse::print_page('login');
    } else if (Session::getUserpriv()==False) {
        HTMLResponse::print_page('logoutmenu');
        HTMLResponse::print_page('firstlogin');
    } else {
        HTMLResponse::print_page('logoutmenu');
        HTMLResponse::print_page('content');
    } 
    HTMLResponse::print_page('footer');
} else {
    JSONResponse::response();
}
$content=  ob_get_contents();
ob_end_clean();
print $content;

?>      
        

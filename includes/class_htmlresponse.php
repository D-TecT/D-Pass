<?php

class HTMLResponse {
    function print_page($page) {
        if (!in_array($page,array('firstlogin','footer','header','login','logoutmenu','minimalmenu','content'))) return;
        $content=file_get_contents('templates/site_'.$page.'.inc');  
        preg_match_all('|\[([A-Z_0-9]+)\]|', $content, $substitutes);
        foreach ($substitutes[1] as $substitute) {
            if (constant($substitute)) $content=str_replace ('['.$substitute.']', constant($substitute), $content);
        }
        print $content;
    }
}

?>

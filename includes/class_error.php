<?php

class Error {
    private static $errorfields=array();  
    
    /**
     * 
     */
    function setErrorField($name,$text) {
        self::$errorfields[$name]=$text;
    }
    
    function getErrorField($name) {
        if (isset(self::$errorfields[$name])) {
            return self::$errorfields[$name]; 
        } else {
            return False;
        }
    }
    
    
    /**
     * Prints a error box with specified message. Exits execution
     * 
     * @param type $message errormessage to print
     */    
  function printCriticalError($message) {
      $debugmode=Config::getValue("debug");
      ob_end_flush();
      HTMLResponse::print_page('header');
      HTMLResponse::print_page('minimalmenu');
      ?><div class="">&nbsp;</div>
      <div class="row">
      <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
      <div class="small-12 medium-8 large-6 columns">
      <div data-alert class="alert-box alert"><?
      if ($debugmode) {
          print $message;
          print "<br/><br/>Backtrace:<br/>";
          $dbt=debug_backtrace();
          array_shift($dbt);
          foreach ($dbt as $bt) {
            echo "<b>".$bt["file"].":".$bt["line"]."</b><br/>&nbsp;&nbsp;".$bt["function"]."(\"".implode("\",\"",$bt["args"])."\")<br/>\n";
          }
      } else
          print STRING_ERROR_CRITICAL_DEFAULT;
      ?></div>
      </div>
      <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
      </div><?
      HTMLResponse::print_page('footer');
      die();
  } 
  
}

?>

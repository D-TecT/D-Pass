<?php

class Error {
    /**
     * Prints a error box with specified message. Exits execution
     * 
     * @param type $message errormessage to print
     */    
  function printCriticalError($message) {
      $debugmode=Config::getValue("debug");
      ob_end_flush();
      include('templates/site_header.php');
      include('templates/site_minimalmenu.php');
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
      include('templates/site_footer.php');
      die();
  } 
  
}

?>

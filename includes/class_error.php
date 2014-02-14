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
      print '<div data-alert class="alert-box alert">';
      if ($debugmode)
          print $message;
      else
          print STRING_ERROR_CRITICAL_DEFAULT;
      include('templates/site_footer.php');
      die();
  } 
  
}

?>

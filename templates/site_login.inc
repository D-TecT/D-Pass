<noscript>
  <div class="show-for-small">&nbsp;</div>
  <div class="row">
      <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
      <div class="small-12 medium-8 large-6 columns">
          <div data-alert class="alert-box alert">[STRING_ERROR_NOSCRIPT]</div>
      </div>
      <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
  </div>
</noscript>
<div class="row">
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
    <div class="small-12 medium-8 large-6 columns panel">
        <form id="frm_login" action="index.php" method="POST">
        <input type="hidden" name="action" value="login">    
        <input type="hidden" name="ajax" value="1">    
        <h4>[STRING_LOGIN_TITLE]</h4>
        <div class="row">
            <div class="small-12 columns">
                <label>[STRING_LOGIN_USERNAME]
                    <input type="text" name="u" placeholder="[STRING_LOGIN_ENTER_USERNAME]" />
                </label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label>[STRING_LOGIN_PASSWORD]
                    <input type="password" name="p" placeholder="[STRING_LOGIN_ENTER_PASSWORD]" />
                </label>
            </div>
        </div>
        <a id="frm_submit" onclick="$('#frm_login').submit();" class="button expand radius">[STRING_LOGIN_SUBMIT]</a>
        </form>
    </div>
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
</div>
<script>
  $("#frm_login").on("submit",login);
  function login(e)  
  {
      var postData=$(this).serializeArray();
      var formURL = $(this).attr("action");
      $.ajax({
         url:formURL,
         type:"POST",
         data:postData,
         dataType: "json",
         success:function(data,textStatus,jqXHR) {
             processJSON(data);
             if (data.responsecode!=200) {
                $("#frm_login").on("submit",login); 
                $("#frm_submit").html('[STRING_LOGIN_SUBMIT]');
             }
         },
         error: function(jqXHR, textStatus, errorThrown) {
             $("#frm_login").on("submit",login); 
             $("#frm_submit").html('[STRING_LOGIN_SUBMIT]');
             showAlert('[STRING_ERROR_UNEXPECTED]');
         }
      });  
      
      e.preventDefault();
      $("#frm_login").off("submit",login);
      $("#frm_submit").html('[STRING_LOGIN_SUBMITTED] '+getLoader());
      $("#frm_login").on("submit",function (e) {e.preventDefault();});
  };
</script>

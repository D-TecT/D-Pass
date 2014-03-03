<div class="row">
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
    <div class="small-12 medium-8 large-6 columns panel">
        <h4>[STRING_FIRSTLOGIN_TITLE]</h4>
        <p>[STRING_FIRSTLOGIN_EXPLAIN]</p>
        <form id="frm_changepw" method="POST" action="index.php">
        <input type="hidden" name="action" value="changepw">    
        <input type="hidden" name="ajax" value="1"> 
        <div class="row">
            <div class="small-12 columns">
                <label>[STRING_CHANGEPW_OLDPW]
                    <input type="password" name="po" placeholder="[STRING_CHANGEPW_ENTER_OLDPW]" />
                </label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label>[STRING_CHANGEPW_NEWPW]
                    <input type="password" name="pn1" placeholder="[STRING_CHANGEPW_ENTER_NEWPW]" />
                </label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label>[STRING_CHANGEPW_NEWPW2]
                    <input type="password" name="pn2" placeholder="[STRING_CHANGEPW_ENTER_NEWPW2]" />
                </label>
            </div>
        </div>
        <a id="frm_submit" onclick="$('#frm_changepw').submit();" class="button expand radius">[STRING_CHANGEPW_FIRSTLOGIN_SUBMIT]</a>
        </form>
    </div>
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
</div>
<script>
  $("#frm_changepw").on("submit",changepw);
  function changepw(e) {
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
                $("#frm_changepw").on("submit",changepw); 
                $("#frm_submit").html('[STRING_CHANGEPW_FIRSTLOGIN_SUBMIT]');
             }
         },
         error: function(jqXHR, textStatus, errorThrown) {
             $("#frm_changepw").on("submit",changepw); 
             $("#frm_submit").html('[STRING_CHANGEPW_FIRSTLOGIN_SUBMIT]');
             showAlert('[STRING_ERROR_UNEXPECTED]');
         }
      });  
      
      e.preventDefault();
      $("#frm_changepw").off("submit",changepw);
      $("#frm_submit").html('[STRING_CHANGEPW_WAIT] '+getLoader());
      $("#frm_changepw").on("submit",function (e) {e.preventDefault();});
  }
</script>
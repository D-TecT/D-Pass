<div class="hide-for-small">&nbsp;</div>
<noscript>
  <div class="show-for-small">&nbsp;</div>
  <div class="row">
      <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
      <div class="small-12 medium-8 large-6 columns">
          <div data-alert class="alert-box alert"><? print STRING_ERROR_NOSCRIPT; ?></div>
      </div>
      <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
  </div>
</noscript>
<div class="row">
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
    <div class="small-12 medium-8 large-6 columns contentdiv">
        <h4><? echo STRING_LOGIN_TITLE; ?></h4>
        <form id="frm_login" method="POST" action="<? echo SCRIPT_NAME; ?>">
        <div class="row">
            <div class="small-12 columns">
                <label><? echo STRING_LOGIN_USERNAME; ?>
                    <input type="text" name="u" placeholder="<? echo STRING_LOGIN_ENTER_USERNAME; ?>" />
                </label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label><? echo STRING_LOGIN_PASSWORD; ?>
                    <input type="password" name="p" placeholder="<? echo STRING_LOGIN_ENTER_PASSWORD; ?>" />
                </label>
            </div>
        </div>
        <a onclick="login();" class="button expand radius"><? echo STRING_LOGIN_SUBMIT; ?></a>
        </form>
    </div>
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
</div>
<script>
  function login() {
    $('#frm_login').submit();    
  }
</script>

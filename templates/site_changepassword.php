<div class="hide-for-small">&nbsp;</div>
<div class="row">
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
    <div class="small-12 medium-8 large-6 columns contentdiv">
        <h4><? echo STRING_CHANGEPW_TITLE; ?></h4>
        <? if (Session::getUserpriv()==False) print "<p>".STRING_CHANGEPW_FIRSTLOGIN."</p>"; ?>
        <form id="frm_changepw" method="POST" action="<? echo SCRIPT_NAME; ?>">
        <div class="row">
            <div class="small-12 columns">
                <label><? echo STRING_CHANGEPW_OLDPW; ?>
                    <input type="password" <? if (Error::getErrorField('po')) print 'class="error"'; ?> name="po" placeholder="<? echo STRING_CHANGEPW_ENTER_OLDPW; ?>" />
                    <? if (Error::getErrorField('po')) print '<small class="error">'.Error::getErrorField('po').'</small>'; ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label><? echo STRING_CHANGEPW_NEWPW; ?>
                    <input type="password" <? if (Error::getErrorField('pn1')) print 'class="error"'; ?> name="pn1" placeholder="<? echo STRING_CHANGEPW_ENTER_NEWPW; ?>" />
                    <? if (Error::getErrorField('pn1')) print '<small class="error">'.Error::getErrorField('pn1').'</small>'; ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <label><? echo STRING_CHANGEPW_NEWPW2; ?>
                    <input type="password" <? if (Error::getErrorField('pn2')) print 'class="error"'; ?> name="pn2" placeholder="<? echo STRING_CHANGEPW_ENTER_NEWPW2; ?>" />
                    <? if (Error::getErrorField('pn2')) print '<small class="error">'.Error::getErrorField('pn2').'</small>'; ?>
                </label>
            </div>
        </div>
        <a id="frm_changepw_bt" onclick="changepw()" class="button expand radius"><? if (Session::getUserpriv()==False) echo STRING_CHANGEPW_FIRSTLOGIN_SUBMIT; else echo STRING_CHANGEPW_SUBMIT; ?></a>
        </form>
    </div>
    <div class="hide-for-small medium-2 large-3 columns">&nbsp;</div>
</div>
<script>
  function changepw() {
      $('#frm_changepw_bt').html('<? echo STRING_CHANGEPW_WAIT; ?>');
      $('#frm_changepw_bt').addClass('disabled');
      $('#frm_changepw_bt').prop('onclick',null);
      $('#frm_changepw').submit();    
  }
</script>
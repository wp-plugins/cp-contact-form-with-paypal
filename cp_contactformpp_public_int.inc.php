<?php if ( !defined('CP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; } ?>
</p><link href="<?php echo plugins_url('css/stylepublic.css', __FILE__); ?>" type="text/css" rel="stylesheet" /><link href="<?php echo plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
<script type="text/javascript">
 function doValidate(form)
 {
    $dexQuery = jQuery.noConflict();
    document.cp_contactformpp_pform.cp_ref_page.value = document.location;
    <?php if (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha) != 'false') { ?> if ($dexQuery("#hdcaptcha_cp_contact_form_paypal_post").val() == '')
    {
        alert('<?php _e('Please enter the captcha verification code.'); ?>');
        return false;
    }
    var result = $dexQuery.ajax({
        type: "GET",
        url: "<?php echo cp_contactformpp_get_site_url(); ?>?hdcaptcha_cp_contact_form_paypal_post="+$dexQuery("#hdcaptcha_cp_contact_form_paypal_post").val(),
        async: false
    }).responseText;
    if (result == "captchafailed")
    {
        $dexQuery("#captchaimg").attr('src', $dexQuery("#captchaimg").attr('src')+'&'+Date());
        alert('<?php _e('Incorrect captcha code. Please try again.'); ?>');
        return false;
    }
    else <?php } ?>    
        return true;
 }
</script>
<form class="cpp_form" name="cp_contactformpp_pform" id="cp_contactformpp_pform" action="<?php get_site_url(); ?>" method="post"  onsubmit="return doValidate(this);"><input type="hidden" name="cp_contactformpp_pform_process" value="1" /><input type="hidden" name="cp_contactformpp_id" value="<?php echo CP_CONTACTFORMPP_ID; ?>" /><input type="hidden" name="cp_ref_page" value="<?php esc_attr(cp_contactformpp_get_FULL_site_url); ?>" /><input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace("\r","",str_replace("\n","",esc_attr(cp_contactformpp_cleanJSON(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure))))); ?>" />
    <div id="fbuilder">
        <div id="formheader"></div>
        <div id="fieldlist"></div>
    </div>
<div id="cpcaptchalayer">
<?php if (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha) != 'false') { ?>
  Please enter the security code:<br />  
  <img src="<?php echo cp_contactformpp_get_site_url().'/?cp_contactformpp=captcha&width='.cp_contactformpp_get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width).'&height='.cp_contactformpp_get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height).'&letter_count='.cp_contactformpp_get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars).'&min_size='.cp_contactformpp_get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size).'&max_size='.cp_contactformpp_get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size).'&noise='.cp_contactformpp_get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise).'&noiselength='.cp_contactformpp_get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length).'&bcolor='.cp_contactformpp_get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background).'&border='.cp_contactformpp_get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border).'&font='.cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font); ?>"  id="captchaimg" alt="security code" border="0"  />
  <br />
  Security Code (lowercase letters):<br />
  <div class="dfield">
  <input type="text" size="20" name="hdcaptcha_cp_contact_form_paypal_post" id="hdcaptcha_cp_contact_form_paypal_post" value="" />
  <div class="error message" id="hdcaptcha_error" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"></div>
  </div>
  <br />
<?php } ?>
</div>
<div id="cp_subbtn"><?php _e("Submit"); ?></div>
</form>
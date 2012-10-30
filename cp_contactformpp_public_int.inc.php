<?php

if ( !defined('CP_AUTH_INCLUDE') )
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;
if (defined('CP_CONTACTFORMPP_ID'))
    $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." WHERE id=".CP_CONTACTFORMPP_ID );
else
    $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );

define ('CP_CONTACTFORMPP_ID',$myrows[0]->id);

?>
</p>
<link href="<?php echo plugins_url('css/stylepublic.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__); ?>" type="text/css" rel="stylesheet" />

<script type="text/javascript">
           $contactFormPPQuery = jQuery.noConflict();
           $contactFormPPQuery(document).ready(function() {
         	   var f = $contactFormPPQuery("#fbuilder").fbuilderCFPP({pub:true,messages: {
	                	required: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required)); ?>',
	                	email: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email)); ?>',
	                	datemmddyyyy: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy)); ?>',
	                	dateddmmyyyy: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy)); ?>',
	                	number: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number)); ?>',
	                	digits: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits)); ?>',
	                	max: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max)); ?>',
	                	min: '<?php echo str_replace("'","\\'",cp_contactformpp_get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min)); ?>'
	                }});
               f.fBuild.loadData("form_structure");
                $contactFormPPQuery("#cp_contactformpp_pform").validate({
			        //ignore: "",
			        errorElement: "div",
			        errorPlacement: function(e, element) {
                        if (element.hasClass('group')){
                            element = element.parent().siblings(":last");
                            //element = element.siblings(":last");
                        }
                        //else
                        //    e.insertAfter(element);
                        offset = element.offset();
                        e.insertBefore(element)
                        e.addClass('message');  // add a class to the wrapper
                        e.css('position', 'absolute');
                        e.css('left',0 );
                        ////e.css('left', offset.left );//+ element.outerWidth()
                        //e.css('top', offset.top+element.outerHeight()+0);
                        e.css('top',element.outerHeight());
                    }/**,
                    submitHandler: function(form) {
                        $contactFormPPQuery("#cp_contactformpp_subbtn").attr("disabled", "disabled");
                        $contactFormPPQuery("#cp_contactformpp_subbtn_animation").show();
                        $contactFormPPQuery.post('<?php echo cp_contactformpp_get_site_url(); ?>/', $contactFormPPQuery("#cp_contactformpp_pform").serialize(),  function(data) {
                            if (data == "captchafailed")
                            {
                                 $contactFormPPQuery("#cp_contactformpp_subbtn").removeAttr("disabled");
                                 $contactFormPPQuery("#cp_contactformpp_subbtn_animation").hide();
                                 $contactFormPPQuery("#hdcaptcha_error").html("<?php echo esc_attr(cp_contactformpp_get_option('cv_text_enter_valid_captcha', CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha)); ?>");
                                 $contactFormPPQuery("#hdcaptcha_error").css('top',$contactFormPPQuery("#hdcaptcha").outerHeight());
                                 $contactFormPPQuery("#hdcaptcha_error").css("display","inline");
                                 $contactFormPPQuery("#captchaimg").attr('src', $contactFormPPQuery("#captchaimg").attr('src')+'&'+Date());
                            }
                            else
                                document.location.href='<?php echo cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page); ?>';
                        });
                        return false;
                    }*/
                });
           });
 function doValidate(form)
 {
    document.cp_contactformpp_pform.cp_ref_page.value = document.location;
    <?php if (cp_contactformpp_get_option('dexcv_enable_captcha', TDE_APP_DEFAULT_dexcv_enable_captcha) != 'false') { ?> if (form.hdcaptcha_cp_contact_form_paypal_post.value == '')
    {
        alert('<?php _e('Please enter the captcha verification code.'); ?>');
        return false;
    }
    // check captcha
    $dexQuery = jQuery.noConflict();
    var result = $dexQuery.ajax({
        type: "GET",
        url: "<?php echo cp_contactformpp_get_site_url(); ?>?hdcaptcha_cp_contact_form_paypal_post="+form.hdcaptcha_cp_contact_form_paypal_post.value,
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

<form name="cp_contactformpp_pform" id="cp_contactformpp_pform" action="<?php get_site_url(); ?>" method="post"  onsubmit="return doValidate(this);">
  <input type="hidden" name="cp_contactformpp_pform_process" value="1" />
  <input type="hidden" name="cp_contactformpp_id" value="<?php echo CP_CONTACTFORMPP_ID; ?>" />
  <input type="hidden" name="cp_ref_page" value="<?php esc_attr(cp_contactformpp_get_FULL_site_url); ?>" />

  <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo esc_attr(cp_contactformpp_cleanJSON(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure))); ?>" />


    <div id="fbuilder">
        <div id="formheader"></div>
        <div id="fieldlist"></div>
    </div>



<?php
     $codes = $wpdb->get_results( 'SELECT * FROM '.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME.' WHERE `form_id`='.CP_CONTACTFORMPP_ID);
     if (count($codes))
     {
?>
         <?php _e('Coupon code (optional)'); ?>:<br />
         <input type="text" name="couponcode" value=""><br />
<?php } ?>
  <br />

<?php if (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha) != 'false') { ?>
  Please enter the security code:<br />
  <img src="<?php echo plugins_url('/captcha/captcha.php?width='.cp_contactformpp_get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width).'&height='.cp_contactformpp_get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height).'&letter_count='.cp_contactformpp_get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars).'&min_size='.cp_contactformpp_get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size).'&max_size='.cp_contactformpp_get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size).'&noise='.cp_contactformpp_get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise).'&noiselength='.cp_contactformpp_get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length).'&bcolor='.cp_contactformpp_get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background).'&border='.cp_contactformpp_get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border).'&font='.cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font), __FILE__); ?>"  id="captchaimg" alt="security code" border="0"  />
  <br />
  Security Code (lowercase letters):<br />
  <div class="dfield">
  <input type="text" size="20" name="hdcaptcha_cp_contact_form_paypal_post" id="hdcaptcha_cp_contact_form_paypal_post" value="" />
  <div class="error message" id="hdcaptcha_error" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"></div>
  </div>
  <br />
<?php } ?>





<input type="submit" class="submit" name="cp_contactformpp_subbtn" id="cp_contactformpp_subbtn" value="<?php _e("Submit"); ?>">

<div style="display:none" id="cp_contactformpp_subbtn_animation" style="background:#ffffff;width:18;height:18;padding:1px;">
 <img src="<?php echo plugins_url('/images/loading.gif', __FILE__); ?>" width="16" height="16" alt="loading" />
</div>


</form>








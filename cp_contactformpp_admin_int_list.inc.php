<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;
$message = "";
if (isset($_GET['a']) && $_GET['a'] == '1')
{
    $wpdb->insert( $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, array( 
                                      'form_name' => stripcslashes($_GET["name"]),

                                      'form_structure' => get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure),

                                      'fp_from_email' => get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails),
                                      'fp_subject' => get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page),
                                      'fp_message' => get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message),

                                      'cu_enable_copy_to_user' => get_option('cu_enable_copy_to_user', CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field),
                                      'cu_subject' => get_option('cu_subject', CP_CONTACTFORMPP_DEFAULT_cu_subject),
                                      'cu_message' => get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message),

                                      'vs_use_validation' => get_option('vs_use_validation', CP_CONTACTFORMPP_DEFAULT_vs_use_validation),
                                      'vs_text_is_required' => get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number),
                                      'vs_text_digits' => get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits),
                                      'vs_text_max' => get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max),
                                      'vs_text_min' => get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min),
                                      
                                      'enable_paypal' => get_option('enable_paypal', CP_CONTACTFORMPP_DEFAULT_ENABLE_PAYPAL),
                                      'paypal_email' => get_option('paypal_email', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL),
                                      'request_cost' => get_option('request_cost', CP_CONTACTFORMPP_DEFAULT_COST),
                                      'paypal_product_name' => get_option('paypal_product_name',CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME ),
                                      'currency' => get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY),
                                      'paypal_language' => get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE),                                         

                                      'cv_enable_captcha' => get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha),
                                      'cv_width' => get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width),
                                      'cv_height' => get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height),
                                      'cv_chars' => get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars),
                                      'cv_font' => get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font),
                                      'cv_min_font_size' => get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size),
                                      'cv_noise' => get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise),
                                      'cv_noise_length' => get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length),
                                      'cv_background' => get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background),
                                      'cv_border' => get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => get_option('cv_text_enter_valid_captcha', CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );   
    
    $message = "Item added";
} 
else if (isset($_GET['u']) && $_GET['u'] != '')
{
    $wpdb->query('UPDATE `'.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE.'` SET form_name="'.$wpdb->escape($_GET["name"]).'" WHERE id='.$_GET['u']);           
    $message = "Item updated";        
}
else if (isset($_GET['d']) && $_GET['d'] != '')
{
    $wpdb->query('DELETE FROM `'.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE.'` WHERE id='.$_GET['d']);       
    $message = "Item deleted";
}


if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".$message."</strong></p></div>";

?>
<div class="wrap">
<h2>CP Contact Form with Paypal</h2>

<script type="text/javascript">
 function cp_addItem()
 {
    var calname = document.getElementById("cp_itemname").value;
    document.location = 'options-general.php?page=cp_contact_form_paypal&a=1&r='+Math.random()+'&name='+encodeURIComponent(calname);       
 }
 
 function cp_updateItem(id)
 {
    var calname = document.getElementById("calname_"+id).value;    
    document.location = 'options-general.php?page=cp_contact_form_paypal&u='+id+'&r='+Math.random()+'&name='+encodeURIComponent(calname);    
 }
 
 function cp_manageSettings(id)
 {
    document.location = 'options-general.php?page=cp_contact_form_paypal&cal='+id+'&r='+Math.random();
 }
 
 function cp_viewMessages(id)
 {
    document.location = 'options-general.php?page=cp_contact_form_paypal&cal='+id+'&list=1&r='+Math.random();
 } 
 
 function cp_BookingsList(id)
 {
    document.location = 'options-general.php?page=cp_contact_form_paypal&cal='+id+'&list=1&r='+Math.random();
 }
 
 function cp_deleteItem(id)
 {
    if (confirm('Are you sure that you want to delete this item?'))
    {        
        document.location = 'options-general.php?page=cp_contact_form_paypal&d='+id+'&r='+Math.random();
    }
 }
 
</script>


<div id="normal-sortables" class="meta-box-sortables">


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form List / Items List</span></h3>
  <div class="inside">
  
  
  <table cellspacing="10"> 
   <tr>
    <th align="left">ID</th><th align="left">Form Name</th><th align="left">&nbsp; &nbsp; Options</th><th align="left">Shorttag for Pages and Posts</th>
   </tr> 
<?php  

  $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );                                                                     
  foreach ($myrows as $item)         
  {
?>
   <tr> 
    <td nowrap><?php echo $item->id; ?></td>
    <td nowrap><input type="text" name="calname_<?php echo $item->id; ?>" id="calname_<?php echo $item->id; ?>" value="<?php echo esc_attr($item->form_name); ?>" /></td>          
    
    <td nowrap>&nbsp; &nbsp; 
                             <input type="button" name="calupdate_<?php echo $item->id; ?>" value="Update" onclick="cp_updateItem(<?php echo $item->id; ?>);" /> &nbsp; 
                             <input type="button" name="calmanage_<?php echo $item->id; ?>" value="Settings" onclick="cp_manageSettings(<?php echo $item->id; ?>);" /> &nbsp;                              
                             <input type="button" name="calmanage_<?php echo $item->id; ?>" value="Messages" onclick="cp_viewMessages(<?php echo $item->id; ?>);" /> &nbsp;                              
                             <input type="button" name="caldelete_<?php echo $item->id; ?>" value="Delete" onclick="cp_deleteItem(<?php echo $item->id; ?>);" />                             
    </td>
    <td nowrap>[CP_CONTACT_FORM_PAYPAL form="<?php echo $item->id; ?>"]</td>          
   </tr>
<?php  
   } 
?>   
     
  </table> 
    
    
   
  </div>    
 </div> 
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>New Form</span></h3>
  <div class="inside"> 
   
    <form name="additem">
      Item Name:<br />
      <input type="text" name="cp_itemname" id="cp_itemname"  value="" /> <input type="button" onclick="cp_addItem();" name="gobtn" value="Add" />
      <br /><br />      
    </form>

  </div>    
 </div>


  
</div> 


[<a href="http://wordpress.dwbooster.com/contact-us" target="_blank">Request Custom Modifications</a>] | [<a href="http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal" target="_blank">Help</a>]
</form>
</div>















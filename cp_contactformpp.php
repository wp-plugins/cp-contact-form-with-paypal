<?php
/*
Plugin Name: CP Contact Form with Paypal
Plugin URI: http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal
Description: Inserts a contact form into your website and let you connect it to a Paypal payment.
Version: 1.01
Author: CodePeople.net
Author URI: http://codepeople.net
License: GPL
*/


/* initialization / install / uninstall functions */


// CP Contact Form with Paypal constants

define('CP_CONTACTFORMPP_DEFAULT_CURRENCY_SYMBOL','$');

define('CP_CONTACTFORMPP_DEFAULT_form_structure', '[[{"name":"email","index":0,"title":"Email","ftype":"femail","userhelp":"","csslayout":"","required":true,"predefined":"","size":"medium"},{"name":"subject","index":1,"title":"Subject","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium"},{"name":"message","index":2,"size":"large","required":true,"title":"Message","ftype":"ftextarea","userhelp":"","csslayout":"","predefined":""}],[{"title":"Contact Form","description":"","formlayout":"top_aligned"}]]');

define('CP_CONTACTFORMPP_DEFAULT_fp_subject', 'Contact from the blog...');
define('CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info', 'true');
define('CP_CONTACTFORMPP_DEFAULT_fp_return_page', get_site_url());
define('CP_CONTACTFORMPP_DEFAULT_fp_message', "The following contact message has been sent:\n\n<%INFO%>\n\n");

define('CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user', 'true');
define('CP_CONTACTFORMPP_DEFAULT_cu_user_email_field', '');
define('CP_CONTACTFORMPP_DEFAULT_cu_subject', 'Confirmation: Message received...');
define('CP_CONTACTFORMPP_DEFAULT_cu_message', "Thank you for your message. We will reply you as soon as possible.\n\nThis is a copy of the data sent:\n\n<%INFO%>\n\nBest Regards.");
define('CP_CONTACTFORMPP_DEFAULT_email_format','text');

define('CP_CONTACTFORMPP_DEFAULT_vs_use_validation', 'true');

define('CP_CONTACTFORMPP_DEFAULT_vs_text_is_required', 'This field is required.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with this format(mm/dd/yyyy)');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with this format(dd/mm/yyyy)');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');


define('CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha', 'true');
define('CP_CONTACTFORMPP_DEFAULT_cv_width', '180');
define('CP_CONTACTFORMPP_DEFAULT_cv_height', '60');
define('CP_CONTACTFORMPP_DEFAULT_cv_chars', '5');
define('CP_CONTACTFORMPP_DEFAULT_cv_font', 'font-1.ttf');
define('CP_CONTACTFORMPP_DEFAULT_cv_min_font_size', '25');
define('CP_CONTACTFORMPP_DEFAULT_cv_max_font_size', '35');
define('CP_CONTACTFORMPP_DEFAULT_cv_noise', '200');
define('CP_CONTACTFORMPP_DEFAULT_cv_noise_length', '4');
define('CP_CONTACTFORMPP_DEFAULT_cv_background', 'ffffff');
define('CP_CONTACTFORMPP_DEFAULT_cv_border', '000000');
define('CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha', 'Please enter a valid captcha code.');


define('CP_CONTACTFORMPP_DEFAULT_ENABLE_PAYPAL', 1);
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE', 'production');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT', '0');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_IDENTIFY_PRICES', '0');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_ZERO_PAYMENT', '0');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL','put_your@email_here.com');
define('CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME','Reservation');
define('CP_CONTACTFORMPP_DEFAULT_COST','25');
define('CP_CONTACTFORMPP_DEFAULT_CURRENCY','USD');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE','EN');


// database
define('CP_CONTACTFORMPP_FORMS_TABLE', 'cp_contact_form_paypal_settings');

define('CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME_NO_PREFIX', "cp_contact_form_paypal_discount_codes");
define('CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME', @$wpdb->prefix ."cp_contact_form_paypal_discount_codes");

define('CP_CONTACTFORMPP_POSTS_TABLE_NAME_NO_PREFIX', "cp_contact_form_paypal_posts");
define('CP_CONTACTFORMPP_POSTS_TABLE_NAME', @$wpdb->prefix ."cp_contact_form_paypal_posts");


// end CP Contact Form with Paypal constants


// code initialization, hooks
// -----------------------------------------

register_activation_hook(__FILE__,'cp_contactformpp_install');

add_action( 'init', 'cp_contact_form_paypal_check_posted_data', 11 );


if ( is_admin() ) {
    add_action('media_buttons', 'set_cp_contactformpp_insert_button', 100);
    add_action('admin_enqueue_scripts', 'set_cp_contactformpp_insert_adminScripts', 1);
    add_action('admin_menu', 'cp_contactformpp_admin_menu');    

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_".$plugin, 'cp_contactformpp_customAdjustmentsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_contactformpp_settingsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_contactformpp_helpLink');

    function cp_contactformpp_admin_menu() {
        add_options_page('CP Contact Form with Paypal Options', 'CP Contact Form with Paypal', 'manage_options', 'cp_contact_form_paypal', 'cp_contactformpp_html_post_page' );
    }
} else { // if not admin    
    add_shortcode( 'CP_CONTACT_FORM_PAYPAL', 'cp_contactformpp_filter_content' );
}

// functions
//------------------------------------------

function cp_contactformpp_install($networkwide)  {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
	                $old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_cp_contactformpp_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_cp_contactformpp_install();
}

function _cp_contactformpp_install() {
    global $wpdb;
    
    define('CP_CONTACTFORMPP_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
    define('CP_CONTACTFORMPP_DEFAULT_fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_from_email);
 
    $table_name = $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE;

    $sql = "CREATE TABLE ".$wpdb->prefix.CP_CONTACTFORMPP_POSTS_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         formid INT NOT NULL,
         time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         ipaddr VARCHAR(32) DEFAULT '' NOT NULL,
         notifyto VARCHAR(250) DEFAULT '' NOT NULL,
         data text,
         paypal_post text,
         paid INT DEFAULT 0 NOT NULL,
         UNIQUE KEY id (id)
         );";
    $wpdb->query($sql);

    $sql = "CREATE TABLE ".$wpdb->prefix.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         form_id mediumint(9) NOT NULL DEFAULT 1,
         code VARCHAR(250) DEFAULT '' NOT NULL,
         discount VARCHAR(250) DEFAULT '' NOT NULL,
         expires datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,       
         availability int(10) unsigned NOT NULL DEFAULT 0,
         used int(10) unsigned NOT NULL DEFAULT 0,
         UNIQUE KEY id (id)
         );";             
    $wpdb->query($sql); 


    $sql = "CREATE TABLE $table_name (
         id mediumint(9) NOT NULL AUTO_INCREMENT,

         form_name VARCHAR(250) DEFAULT '' NOT NULL,

         form_structure text,

         fp_from_email VARCHAR(250) DEFAULT '' NOT NULL,
         fp_destination_emails text,
         fp_subject VARCHAR(250) DEFAULT '' NOT NULL,
         fp_inc_additional_info VARCHAR(10) DEFAULT '' NOT NULL,
         fp_return_page VARCHAR(250) DEFAULT '' NOT NULL,
         fp_message text,
         fp_emailformat VARCHAR(10) DEFAULT '' NOT NULL,         

         cu_enable_copy_to_user VARCHAR(10) DEFAULT '' NOT NULL,
         cu_user_email_field VARCHAR(250) DEFAULT '' NOT NULL,
         cu_subject VARCHAR(250) DEFAULT '' NOT NULL,
         cu_message text,
         cp_emailformat VARCHAR(10) DEFAULT '' NOT NULL,         

         vs_use_validation VARCHAR(10) DEFAULT '' NOT NULL,
         vs_text_is_required VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_is_email VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_datemmddyyyy VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_dateddmmyyyy VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_number VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_digits VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_max VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_min VARCHAR(250) DEFAULT '' NOT NULL,
         
         enable_paypal varchar(10) DEFAULT '' NOT NULL,
         paypal_email varchar(255) DEFAULT '' NOT NULL ,
         request_cost varchar(255) DEFAULT '' NOT NULL ,
         paypal_product_name varchar(255) DEFAULT '' NOT NULL,
         currency varchar(10) DEFAULT '' NOT NULL,
         paypal_language varchar(10) DEFAULT '' NOT NULL,
         paypal_mode varchar(20) DEFAULT '' NOT NULL,
         paypal_recurrent varchar(20) DEFAULT '' NOT NULL ,
         paypal_identify_prices varchar(20) DEFAULT '' NOT NULL ,         
         paypal_zero_payment varchar(10) DEFAULT '' NOT NULL ,
         
         cv_enable_captcha VARCHAR(20) DEFAULT '' NOT NULL,
         cv_width VARCHAR(20) DEFAULT '' NOT NULL,
         cv_height VARCHAR(20) DEFAULT '' NOT NULL,
         cv_chars VARCHAR(20) DEFAULT '' NOT NULL,
         cv_font VARCHAR(20) DEFAULT '' NOT NULL,
         cv_min_font_size VARCHAR(20) DEFAULT '' NOT NULL,
         cv_max_font_size VARCHAR(20) DEFAULT '' NOT NULL,
         cv_noise VARCHAR(20) DEFAULT '' NOT NULL,
         cv_noise_length VARCHAR(20) DEFAULT '' NOT NULL,
         cv_background VARCHAR(20) DEFAULT '' NOT NULL,
         cv_border VARCHAR(20) DEFAULT '' NOT NULL,
         cv_text_enter_valid_captcha VARCHAR(200) DEFAULT '' NOT NULL,

         UNIQUE KEY id (id)
         );";
    $wpdb->query($sql);

    $count = $wpdb->get_var(  "SELECT COUNT(id) FROM ".$table_name  );
    if (!$count)
    {                
        $wpdb->insert( $table_name, array( 'id' => 1,
                                      'form_name' => 'Form 1',

                                      'form_structure' => cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure),

                                      'fp_from_email' => cp_contactformpp_get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => cp_contactformpp_get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails),
                                      'fp_subject' => cp_contactformpp_get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page),
                                      'fp_message' => cp_contactformpp_get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message),
                                      'fp_emailformat' => cp_contactformpp_get_option('fp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format),

                                      'cu_enable_copy_to_user' => cp_contactformpp_get_option('cu_enable_copy_to_user', CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field),
                                      'cu_subject' => cp_contactformpp_get_option('cu_subject', CP_CONTACTFORMPP_DEFAULT_cu_subject),
                                      'cu_message' => cp_contactformpp_get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message),
                                      'cp_emailformat' => cp_contactformpp_get_option('cp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format),

                                      'vs_use_validation' => cp_contactformpp_get_option('vs_use_validation', CP_CONTACTFORMPP_DEFAULT_vs_use_validation),
                                      'vs_text_is_required' => cp_contactformpp_get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => cp_contactformpp_get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => cp_contactformpp_get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => cp_contactformpp_get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => cp_contactformpp_get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number),
                                      'vs_text_digits' => cp_contactformpp_get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits),
                                      'vs_text_max' => cp_contactformpp_get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max),
                                      'vs_text_min' => cp_contactformpp_get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min),
                                      
                                      'enable_paypal' => cp_contactformpp_get_option('enable_paypal', CP_CONTACTFORMPP_DEFAULT_ENABLE_PAYPAL),
                                      'paypal_email' => cp_contactformpp_get_option('paypal_email', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL),
                                      'request_cost' => cp_contactformpp_get_option('request_cost', CP_CONTACTFORMPP_DEFAULT_COST),
                                      'paypal_product_name' => cp_contactformpp_get_option('paypal_product_name', CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME),
                                      'currency' => cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY),
                                      'paypal_language' => cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE),                                      
                                      'paypal_mode' => cp_contactformpp_get_option('paypal_mode', CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE),
                                      'paypal_recurrent' => cp_contactformpp_get_option('paypal_recurrent', CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT),
                                      'paypal_identify_prices' => cp_contactformpp_get_option('paypal_identify_prices', CP_CONTACTFORMPP_DEFAULT_PAYPAL_IDENTIFY_PRICES),
                                      'paypal_zero_payment' => cp_contactformpp_get_option('paypal_zero_payment', CP_CONTACTFORMPP_DEFAULT_PAYPAL_ZERO_PAYMENT),
                                      
                                      'cv_enable_captcha' => cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha),
                                      'cv_width' => cp_contactformpp_get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width),
                                      'cv_height' => cp_contactformpp_get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height),
                                      'cv_chars' => cp_contactformpp_get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars),
                                      'cv_font' => cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font),
                                      'cv_min_font_size' => cp_contactformpp_get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => cp_contactformpp_get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size),
                                      'cv_noise' => cp_contactformpp_get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise),
                                      'cv_noise_length' => cp_contactformpp_get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length),
                                      'cv_background' => cp_contactformpp_get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background),
                                      'cv_border' => cp_contactformpp_get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => cp_contactformpp_get_option('cv_text_enter_valid_captcha', CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );     
    }    
}

/* Filter for placing the maps into the contents */


function cp_contactformpp_filter_content($atts) {
    global $wpdb;
    extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );
    if ($id != '')
        define ('CP_CONTACTFORMPP_ID',$id);
    ob_start();
    cp_contactformpp_get_public_form();
    $buffered_contents = ob_get_contents();
    ob_end_clean();
    return $buffered_contents;
}


function cp_contactformpp_get_public_form() {
    global $wpdb;
    define('CP_AUTH_INCLUDE', true);

    if (defined('CP_CONTACTFORMPP_ID'))
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." WHERE id=".CP_CONTACTFORMPP_ID );
    else
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );

    wp_deregister_script('query-stringify');
    wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));

    wp_deregister_script('cp_contactformpp_validate_script');
    wp_register_script('cp_contactformpp_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));

    wp_enqueue_script( 'cp_contactformpp_buikder_script',
    plugins_url('/js/fbuilder.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","query-stringify","cp_contactformpp_validate_script"), false, true );

    define ('CP_CONTACTFORMPP_ID',$myrows[0]->id);
    wp_localize_script('cp_contactformpp_buikder_script', 'cp_contactformpp_fbuilder_config', array('obj'  	=>
    '{"pub":true,"messages": {
    	                	"required": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required)).'",
    	                	"email": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email)).'",
    	                	"datemmddyyyy": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy)).'",
    	                	"dateddmmyyyy": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy)).'",
    	                	"number": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number)).'",
    	                	"digits": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits)).'",
    	                	"max": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max)).'",
    	                	"min": "'.str_replace(array('"', "'"),array('\\"', "\\'"),cp_contactformpp_get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min)).'"
    	                }}'
    ));
    $codes = $wpdb->get_results( 'SELECT * FROM '.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME.' WHERE `form_id`='.CP_CONTACTFORMPP_ID);
    @include dirname( __FILE__ ) . '/cp_contactformpp_public_int.inc.php';
}


function cp_contactformpp_settingsLink($links) {
    $settings_link = '<a href="options-general.php?page=cp_contact_form_paypal">'.__('Settings').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}


function cp_contactformpp_helpLink($links) {
    $help_link = '<a href="http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal">'.__('Help').'</a>';
	array_unshift($links, $help_link);
	return $links;
}


function cp_contactformpp_customAdjustmentsLink($links) {
    $customAdjustments_link = '<a href="http://wordpress.dwbooster.com/contact-us">'.__('Request custom changes').'</a>';
	array_unshift($links, $customAdjustments_link);
	return $links;
}


function set_cp_contactformpp_insert_button() {
    print '<a href="javascript:cp_contactformpp_insertForm();" title="'.__('Insert CP Contact Form with Paypal').'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert CP Contact Form with Paypal').'" /></a>';
}


function cp_contactformpp_html_post_page() {
    if (isset($_GET["cal"]) && $_GET["cal"] != '')
    {
        if (isset($_GET["list"]) && $_GET["list"] == '1')
            @include_once dirname( __FILE__ ) . '/cp_contactformpp_admin_int_message_list.inc.php';
        else
            @include_once dirname( __FILE__ ) . '/cp_contactformpp_admin_int.php';
    }    
    else
        @include_once dirname( __FILE__ ) . '/cp_contactformpp_admin_int_list.inc.php';        
}


function set_cp_contactformpp_insert_adminScripts($hook) {
    if (isset($_GET["page"]) && $_GET["page"] == "cp_contact_form_paypal")
    {    
        wp_deregister_script('query-stringify');
        wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
        wp_enqueue_script( 'cp_contactformpp_buikder_script', plugins_url('/js/fbuilder.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker","query-stringify") );
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    }

    if( 'post.php' != $hook  && 'post-new.php' != $hook )
        return;
    wp_enqueue_script( 'cp_contactformpp_script', plugins_url('/cp_contactformpp_scripts.js', __FILE__) );
}

function cp_contactformpp_get_site_url()
{
    $url = parse_url(get_site_url());
    $url = rtrim($url["path"],"/");    
    return $url;
}

function cp_contactformpp_get_FULL_site_url()
{
    $url = parse_url(get_site_url());
    $url = rtrim($url["path"],"/");
    $pos = strpos($url, "://");    
    if ($pos === false)
        $url = 'http://'.$_SERVER["HTTP_HOST"].$url;
    return $url;
}

function cp_contactformpp_cleanJSON($str)
{
    $str = str_replace('&qquot;','"',$str);
    $str = str_replace('	',' ',$str);
    return $str;
}


function cp_contactformpp_load_discount_codes() {
    global $wpdb;
    
    if ( ! current_user_can('edit_pages') ) // prevent loading coupons from outside admin area    
    {
        echo 'No enough privilegies to load this content.';
        exit;
    }
    
    if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',$_GET["dex_item"]);    
        
    if (isset($_GET["add"]) && $_GET["add"] == "1")       
        $wpdb->insert( CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME, array('form_id' => CP_CONTACTFORMPP_ID,
                                                                         'code' => $_GET["code"],
                                                                         'discount' => $_GET["discount"],
                                                                         'availability' => $_GET["discounttype"],
                                                                         'expires' => $_GET["expires"],
                                                                         ));     
                                                                       
    if (isset($_GET["delete"]) && $_GET["delete"] == "1")       
        $wpdb->query( $wpdb->prepare( "DELETE FROM ".CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME." WHERE id = %d", $_GET["code"] ));
    
    $codes = $wpdb->get_results( 'SELECT * FROM '.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME.' WHERE `form_id`='.CP_CONTACTFORMPP_ID); 
    if (count ($codes))
    {
        echo '<table>';
        echo '<tr>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Cupon Code</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Discount</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Type</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Valid until</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Options</th>';
        echo '</tr>';
        foreach ($codes as $value)
        {
           echo '<tr>';
           echo '<td>'.$value->code.'</td>';
           echo '<td>'.$value->discount.'</td>';
           echo '<td>'.($value->availability==1?"Fixed Value":"Percent").'</td>';
           echo '<td>'.substr($value->expires,0,10).'</td>';
           echo '<td>[<a href="javascript:dex_delete_coupon('.$value->id.')">Delete</a>]</td>';              
           echo '</tr>';
        }
        echo '</table>';    
    }
    else
        echo 'No discount codes listed for this form yet.';
    exit;    
}


/* hook for checking posted data for the admin area */



function cp_contact_form_paypal_check_posted_data() {
    
    global $wpdb;
    
    if(isset($_GET) && array_key_exists('cp_contact_form_paypal_post',$_GET)) {
        if ($_GET["cp_contact_form_paypal_post"] == 'loadcoupons')   
            cp_contactformpp_load_discount_codes();    
    }    
        
        
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_contactformpp_post_options'] ) && is_admin() )
    {
        cp_contactformpp_save_options();
        return;
    }    

	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_contactformpp_pform_process'] ) )
	    if ( 'GET' != $_SERVER['REQUEST_METHOD'] || !isset( $_GET['hdcaptcha_cp_contact_form_paypal_post'] ) )
		    return;

    if (isset($_POST["cp_contactformpp_id"])) define("CP_CONTACTFORMPP_ID",$_POST["cp_contactformpp_id"]);

    @session_start();
    if (!isset($_GET['hdcaptcha_cp_contact_form_paypal_post']) || $_GET['hdcaptcha_cp_contact_form_paypal_post'] == '') $_GET['hdcaptcha_cp_contact_form_paypal_post'] = $_POST['hdcaptcha_cp_contact_form_paypal_post'];
    if ( 
           (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha) != 'false') &&              
           ( ($_GET['hdcaptcha_cp_contact_form_paypal_post'] != $_SESSION['rand_code']) ||
             ($_SESSION['rand_code'] == '')
           )     
       )
    {
        echo 'captchafailed';
        exit;
    }
    
	// if this isn't the real post (it was the captcha verification) then echo ok and exit
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_contactformpp_pform_process'] ) )
	{
	    echo 'ok';
        exit;
	}    
	
	
	// get price and discounts
    $price = cp_contactformpp_get_option('request_cost', CP_CONTACTFORMPP_DEFAULT_COST);
 
    $discount_note = "";
    $coupon = false;


    // get form info
    //---------------------------
    $form_data = json_decode(cp_contactformpp_cleanJSON(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure)));
    $fields = array();
    foreach ($form_data[0] as $item)
        $fields[$item->name] = $item->title;


    // grab posted data
    //---------------------------

    $buffer = "";
    foreach ($_POST as $item => $value)
        if (isset($fields[$item]))
        {
            $buffer .= $fields[$item] . ": ". (is_array($value)?(implode(", ",$value)):($value)) . "\n\n";
            $params[$item] = $value;
        }
    $buffer_A = $buffer;
    
    // insert into database
    //---------------------------
    $to = cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field);
    $rows_affected = $wpdb->insert( CP_CONTACTFORMPP_POSTS_TABLE_NAME, array( 'formid' => CP_CONTACTFORMPP_ID,
                                                                        'time' => current_time('mysql'),
                                                                        'ipaddr' => $_SERVER['REMOTE_ADDR'],
                                                                        'notifyto' => $_POST[$to],
                                                                        'paypal_post' => serialize($params),
                                                                        'data' =>$buffer_A .($coupon?"\n\nCoupon code:".$coupon->code.$discount_note:"")                                                                        
                                                                         ) );
    if (!$rows_affected)
    {
        echo 'Error saving data! Please try again.';
        echo '<br /><br />Error debug information: '.mysql_error();
        exit;
    }
    
    $myrows = $wpdb->get_results( "SELECT MAX(id) as max_id FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME );
    
    
 	// save data here
    $item_number = $myrows[0]->max_id;

    
?>
<html>
<head><title>Redirecting to Paypal...</title></head>
<body>
<form action="https://www.paypal.com/cgi-bin/webscr" name="ppform3" method="post">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="business" value="<?php echo cp_contactformpp_get_option('paypal_email', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL); ?>" />
<input type="hidden" name="item_name" value="<?php echo cp_contactformpp_get_option('paypal_product_name', CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME).($_POST["services"]?": ".trim($services_formatted[1]):"").$discount_note; ?>" />
<input type="hidden" name="item_number" value="<?php echo $item_number; ?>" />
<input type="hidden" name="amount" value="<?php echo $price; ?>" />
<input type="hidden" name="page_style" value="Primary" />
<input type="hidden" name="no_shipping" value="1" />
<input type="hidden" name="return" value="<?php echo cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page); ?>">
<input type="hidden" name="cancel_return" value="<?php echo $_POST["cp_ref_page"]; ?>" />
<input type="hidden" name="no_note" value="1" />
<input type="hidden" name="currency_code" value="<?php echo strtoupper(cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY)); ?>" />
<input type="hidden" name="lc" value="<?php echo cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE); ?>" />
<input type="hidden" name="bn" value="PP-BuyNowBF" />
<input type="hidden" name="notify_url" value="<?php echo cp_contactformpp_get_FULL_site_url(); ?>/?cp_contactformpp_ipncheck=1&itemnumber=<?php echo $item_number; ?>" />
<input type="hidden" name="ipn_test" value="1" />
<input class="pbutton" type="hidden" value="Buy Now" /></div>
</form>
<script type="text/javascript">
document.ppform3.submit();
</script>
</body>
</html>
<?php
  exit();
   
}    
    

add_action( 'init', 'cp_contactformpp_check_IPN_verification', 11 );

function cp_contactformpp_check_IPN_verification() {

    global $wpdb;

	if ( ! isset( $_GET['cp_contactformpp_ipncheck'] ) || $_GET['cp_contactformpp_ipncheck'] != '1' ||  ! isset( $_GET["itemnumber"] ) )
		return;

    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    $payment_type = $_POST['payment_type'];


	if ($payment_status != 'Completed' && $payment_type != 'echeck')
	    return;

	if ($payment_type == 'echeck' && $payment_status != 'Pending')
	    return;

	$str = '';    
    if ($_POST["first_name"]) $str .= 'Buyer: '.$_POST["first_name"]." ".$_POST["last_name"]."\n";	    
    if ($_POST["payer_email"]) $str .= 'Payer email: '.$_POST["payer_email"]."\n";
	if ($_POST["residence_country"]) $str .= 'Country code: '.$_POST["residence_country"]."\n";
	if ($_POST["payer_status"]) $str .= 'Payer status: '.$_POST["payer_status"]."\n";
	if ($_POST["protection_eligibility"]) $str .= 'Protection eligibility: '.$_POST["protection_eligibility"]."\n";
	
	if ($_POST["item_name"]) $str .= 'Item: '.$_POST["item_name"]."\n";
	if ($_POST["payment_gross"]) 
	     $str .= 'Payment: '.$_POST["payment_gross"]." ".$_POST["mc_currency"]." (Fee: ".$_POST["payment_fee"].")"."\n";
	else if ($_POST["mc_gross"]) 
	     $str .= 'Payment: '.$_POST["mc_gross"]." ".$_POST["mc_currency"]." (Fee: ".$_POST["mc_fee"].")"."\n";
	if ($_POST["payment_date"]) $str .= 'Payment date: '.$_POST["payment_date"];
	if ($_POST["payment_type"]) $str .= 'Payment type/status: '.$_POST["payment_type"]."/".$_POST["payment_status"]."\n";
	if ($_POST["business"]) $str .= 'Business: '.$_POST["business"]."\n";
	if ($_POST["receiver_email"]) $str .= 'Receiver email: '.$_POST["receiver_email"]."\n";	    
      
    $myrows = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE id=".$_GET['itemnumber'] );
    $params = unserialize($myrows[0]->paypal_post);      
    
    $wpdb->query("UPDATE ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." SET paid=1,paypal_post='".$wpdb->escape($str)."' WHERE id=".$_GET['itemnumber']); 
    cp_contactformpp_process_ready_to_go_reservation($_GET["itemnumber"], $payer_email, $params);

    echo 'OK';

    exit();

}    
    
    
function cp_contactformpp_process_ready_to_go_reservation($itemnumber, $payer_email = "", $params = array())
{    

   global $wpdb;

   $myrows = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE id=".$itemnumber );

   $mycalendarrows = $wpdb->get_results( 'SELECT * FROM '. $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE .' WHERE `id`='.$myrows[0]->formid);

   if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',$myrows[0]->formid);
        
    $buffer_A = $myrows[0]->data;
    $buffer = $buffer_A;
    
    if ('true' == cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info))
    {
        $buffer .="ADDITIONAL INFORMATION\n"
              ."*********************************\n"
              ."IP: ".$myrows[0]->ipaddr."\n"              
              ."Server Time:  ".date("Y-m-d H:i:s")."\n";
    }    

    // 1- Send email
    //---------------------------
    $message = str_replace('<%INFO%>',$buffer,cp_contactformpp_get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message));
    foreach ($params as $item => $value)        
    {
        $message = str_replace('<%'.$item.'%>',(is_array($value)?(implode(", ",$value)):($value)),$message);    
        if (strpos($item,"_link"))
            $attachments[] = $value;
    }      
    $subject = cp_contactformpp_get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject);
    $from = cp_contactformpp_get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email);
    $to = explode(",",cp_contactformpp_get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails));

    foreach ($to as $item)
        if (trim($item) != '')
        {
            wp_mail(trim($item), $subject, $message,
                "From: \"$from\" <".$from.">\r\n".
                "Content-Type: text/plain; charset=utf-8\n".
                "X-Mailer: PHP/" . phpversion());
        }

    // 2- Send copy to user
    //---------------------------
    $to = cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field);    
    $_POST[$to] = $myrows[0]->notifyto;
    if ((trim($_POST[$to]) != '' || $payer_email != '') && 'true' == cp_contactformpp_get_option('cu_enable_copy_to_user', CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user))
    {
        $message = str_replace('<%INFO%>',$buffer_A,cp_contactformpp_get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message));
        foreach ($params as $item => $value)        
            $message = str_replace('<%'.$item.'%>',(is_array($value)?(implode(", ",$value)):($value)),$message);        
        $subject = cp_contactformpp_get_option('cu_subject', CP_CONTACTFORMPP_DEFAULT_cu_subject);
        if ($_POST[$to] != '')  
            wp_mail(trim($_POST[$to]), $subject, $message,
                    "From: \"$from\" <".$from.">\r\n".
                    "Content-Type: text/plain; charset=utf-8\n".
                    "X-Mailer: PHP/" . phpversion());
        if ($_POST[$to] != $payer_email && $payer_email != '')  
            wp_mail(trim($payer_email), $subject, $message,
                    "From: \"$from\" <".$from.">\r\n".
                    "Content-Type: text/plain; charset=utf-8\n".
                    "X-Mailer: PHP/" . phpversion());      
    }

}


function cp_contactformpp_save_options() 
{
    global $wpdb;
    if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',$_POST["cp_contactformpp_id"]);
    

    foreach ($_POST as $item => $value)    
        $_POST[$item] = stripcslashes($value);

    $data = array(
                  'form_structure' => $_POST['form_structure'],

                  'fp_from_email' => $_POST['fp_from_email'],
                  'fp_destination_emails' => $_POST['fp_destination_emails'],
                  'fp_subject' => $_POST['fp_subject'],
                  'fp_inc_additional_info' => $_POST['fp_inc_additional_info'],
                  'fp_return_page' => $_POST['fp_return_page'],
                  'fp_message' => $_POST['fp_message'],

                  'cu_enable_copy_to_user' => $_POST['cu_enable_copy_to_user'],
                  'cu_user_email_field' => $_POST['cu_user_email_field'],
                  'cu_subject' => $_POST['cu_subject'],
                  'cu_message' => $_POST['cu_message'],
                  
                  'enable_paypal' => 1,
                  'paypal_email' => $_POST["paypal_email"],
                  'request_cost' => $_POST["request_cost"],
                  'paypal_product_name' => $_POST["paypal_product_name"],
                  'currency' => $_POST["currency"],
                  'paypal_language' => $_POST["paypal_language"],
                  

                  'vs_use_validation' => $_POST['vs_use_validation'],
                  'vs_text_is_required' => $_POST['vs_text_is_required'],
                  'vs_text_is_email' => $_POST['vs_text_is_email'],
                  'vs_text_datemmddyyyy' => $_POST['vs_text_datemmddyyyy'],
                  'vs_text_dateddmmyyyy' => $_POST['vs_text_dateddmmyyyy'],
                  'vs_text_number' => $_POST['vs_text_number'],
                  'vs_text_digits' => $_POST['vs_text_digits'],
                  'vs_text_max' => $_POST['vs_text_max'],
                  'vs_text_min' => $_POST['vs_text_min'],

                  'cv_enable_captcha' => $_POST['cv_enable_captcha'],
                  'cv_width' => $_POST['cv_width'],
                  'cv_height' => $_POST['cv_height'],
                  'cv_chars' => $_POST['cv_chars'],
                  'cv_font' => $_POST['cv_font'],
                  'cv_min_font_size' => $_POST['cv_min_font_size'],
                  'cv_max_font_size' => $_POST['cv_max_font_size'],
                  'cv_noise' => $_POST['cv_noise'],
                  'cv_noise_length' => $_POST['cv_noise_length'],
                  'cv_background' => $_POST['cv_background'],
                  'cv_border' => $_POST['cv_border'],
                  'cv_text_enter_valid_captcha' => $_POST['cv_text_enter_valid_captcha']
	);
    $wpdb->update ( $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, $data, array( 'id' => CP_CONTACTFORMPP_ID ));    
    
}

// cp_contactformpp_get_option:
$cp_contactformpp_option_buffered_item = false;
$cp_contactformpp_option_buffered_id = -1;

function cp_contactformpp_get_option ($field, $default_value)
{
    if (!defined("CP_CONTACTFORMPP_ID"))
        define ("CP_CONTACTFORMPP_ID", 1);
    global $wpdb, $cp_contactformpp_option_buffered_item, $cp_contactformpp_option_buffered_id;
    if ($cp_contactformpp_option_buffered_id == CP_CONTACTFORMPP_ID)
        $value = $cp_contactformpp_option_buffered_item->$field;
    else
    {
       $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." WHERE id=".CP_CONTACTFORMPP_ID );
       $value = $myrows[0]->$field;       
       $cp_contactformpp_option_buffered_item = $myrows[0];
       $cp_contactformpp_option_buffered_id  = CP_CONTACTFORMPP_ID;
    }
    if ($value == '' && $cp_contactformpp_option_buffered_item->form_structure == '')
        $value = $default_value;    
    return $value;
}

?>
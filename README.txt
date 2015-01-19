=== CP Contact Form with Paypal ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal
Tags: contact form,contact,form,paypal,payment,post,mail,email,forms,form to email,plugin,paypal button,page,paypal payment,paypal donation
Requires at least: 3.0.5
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

With CP Contact Form with Paypal you can insert a contact form into a WordPress website and connect it to a PayPal payment.

== Description ==

With CP Contact Form with Paypal you can insert a **contact form** into a WordPress website and connect it to a PayPal payment.

Once the user has filled the PayPal contact form fields and click the submit button the posted data is saved into the WordPress database and the user is automatically redirected to PayPal to complete a payment. After completed the PayPal payment, the website administrator (the email indicated from the settings) will receive an email with the form data and the user will receive a confirmation/thank you email.

Both the paid and unpaid requests sent from the contact form will appear in the WordPress settings area with the mark of "Paid" or "Not Paid" so you can check all the details and contact the user if needed.

This WordPress plugin is useful for different types of contact forms, booking forms, consultation services, payments for joining events, ect...

= Features: =

* Supports many **contact forms** into the same WP website, each one with its own prices and settings.
* Allows checking the **messages** for both paid and un-paid submissions sent from the contact form.
* You can **customize the notification email** details, including from address, subject and content with the contact form fields.
* The website administrator receives an **email notification** of the paid contact message.
* The customer receives a **"thank you - confirmation" message**.
* **Easy setup** of the PayPal payment, basically just indicate the price and email linked to the PayPal account. There are optional fields for language and currency settings.
* Export the contact form messages to CSV/Excel
* Support PayPal taxes configuration
* Optionally request address at PayPal (useful for the delivery of tangible items)
* Includes optional **captcha** verification as part of the contact form.

= How it can be used =

These are some possible scenarios where this plugin is useful:

* Contact form linked to a PayPal payment
* As a PayPal button
* For accepting donations through PayPal (leave a zero amount in the payment amount)
* Support request forms or paid assistance contact forms
* For receiving product orders, purchases, bookings and reservations.
* For automatic delivering of information after payment (put the information into the auto-reply message)
* ... any other use involving contact forms and PayPal payments

= Pro Features: =

The following features aren't part of the free version. The following features are present only in the pro version

* Visual form builder: The free version includes a classic predefined form. If you need a different form you will need the pro version.
* Recurrent payments, work without PayPal, discount codes and dynamic/open prices.

If you are interested in a version with the pro features you can get it here: http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal

= Language Support =

The Contact Form with PayPal plugin is compatible with all charsets. The troubleshoot area contains options to change the encoding of the plugin database tables if needed.

Translations are supported through PO/MO files located in the Contact Form with PayPal plugin folder "languages".

The following translations are already included in the plugin:

* English
* Afrikaans (af)
* Albanian (sq)
* Arabic (ar)
* Armenian (hy_AM)
* Azerbaijani (az)
* Basque (eu)
* Belarusian (be_BY)
* Bosnian (bs_BA)
* Bulgarian (bg_BG)
* Catalan (ca)
* Central Kurdish (ckb)
* Chinese (China zh_CN)
* Chinese (Taiwan zh_TW)
* Croatian (hr)
* Czech (cs_CZ)
* Danish (da_DK)
* Dutch (nl_NL)
* Esperanto (eo_EO)
* Estonian (et)
* Finnish (fi)
* French (fr_FR)
* Galician (gl_ES)
* Georgian (ka_GE)
* German (de_DE)
* Greek (el)
* Gujarati (gu_IN)
* Hebrew (he_IL)
* Hindi (hi_IN)
* Hungarian (hu_HU)
* Indian Bengali (bn_IN)
* Indonesian (id_ID)
* Irish (ga_IE)
* Italian (it_IT)
* Japanese (ja)
* Korean (ko_KR)
* Latvian (lv)
* Lithuanian (lt_LT)
* Macedonian (mk_MK)
* Malay (ms_MY)
* Malayalam (ml_IN)
* Maltese (mt_MT)
* Norwegian (nb_NO)
* Persian (fa_IR)
* Polish (pl_PL)
* Portuguese Brazil(pt_BR)
* Portuguese (pt_PT)
* Punjabi (pa_IN)
* Russian (ru_RU)
* Romanian (ro_RO)
* Serbian (sr_RS)
* Slovak (sk_SK)
* Slovene (sl_SI)
* Spanish (es_ES)
* Swedish (sv_SE)
* Tagalog (tl)
* Tamil (ta)
* Thai (th)
* Turkish (tr_TR)
* Ukrainian (uk)
* Vietnamese (vi)


== Installation ==

To install CP Contact Form with PayPal, follow these steps:

1.	Download and unzip the CP Contact Form with PayPal plugin
2.	Upload the entire cp-contact-form-with-paypal/ directory to the /wp-content/plugins/ directory
3.	Activate the CP Contact Form with PayPal plugin through the Plugins menu in WordPress
4.	Configure the PayPal contact form settings at the administration menu >> Settings >> CP Contact Form with PayPal
5.	To insert the PayPal contact form into some content or post use the icon that will appear when editing contents

== Frequently Asked Questions ==

= Q: What means each field in the PayPal contact form settings area? =

A: The product's page contains detailed information about each contact form field and customization:

http://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal

= Q: Where can I publish the PayPal form with the PayPal button? =

A: You can publish the PayPal contact forms / PayPal button into pages and posts. Other versions of the plugin also allow publishing the PayPal form as a widget.

= Q: The PayPal payment has been received but the status of the Message isn't being set to Paid. What happens? =

A:  First check if you are testing the PayPal form on a local website or in an online website. Note you should test this feature into an online website (local websites cannot receive PayPal IPN connections).

After that initial verification, please check if the IPN notifications are enabled at your PayPal account. Check also the IPN logs at your PayPal account to confirm if are being received.

= Q: I'm not receiving the emails after PayPal payment. =

A: Please check if the messages are marked as "paid" or "not paid" in the contact form messages page.

If the contact form messages are marked as paid then the problem is that your WordPress isn't delivering the emails. You should setup the WordPress to deliver the emails according to your mail server settings. You may have to ask to your web hosting support about the requirements to send emails from WordPress/PHP with their hosting service.

On the other hand if the contact form messages aren't marked as "paid" then the PayPal IPN connection isn't being received. Read the previous FAQ entry for information and solution.

= Q: How can I customize the style of the PayPal button? =

A: The PayPal button is located at the end of the file "cp_contactformpp_public_int.inc.php". It's a classic submit button, you can change it to any other button that submits the PayPal form.

= Q: How can I have an actual PayPal button as the submit button for the form instead of the default grey button? =

A: At the end of the file "cp_contactformpp_public_int.inc.php" replace this:

        <?php _e($button_label); ?>

... by this:

        <img src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" />

You may also want to change the background color of the button with the CSS style class="pbSubmit".

= Q: How can I add specific fields into the contact form message? =

A: There is a tag named %INFO% that is replaced with all the information posted from the form, however you can use also optional tags for specific fields into the contact form.

For doing that, click the desired contact form field into the contact form builder and in the settings box for that field there is a read-only setting named "Field tag for the message (optional):". Copy & paste that tag into the message text and after the form submission (after clicking the PayPal button and receiving the PayPal payment) that tag will be replaced with the text entered in the form field.

= Q: Can I use this with PayPal Personal accounts =

A: Yes, you can use it with PayPal Personal accounts and also with PayPal Premium and PayPal Business accounts.

= Q: Can I accept credit card payments through PayPal directly without forcing the users to create a PayPal account? =

A: That depends of the type of your PayPal account and its status. In most cases PayPal Business accounts and PayPal premium accounts allow accepting payments from users that don't have PayPal accounts. In this case after clicking the PayPal button the PayPal page will appear with two options "Login at your PayPal account" and "Pay directly without having to register". The title of the options at PayPal may vary.

= Q: I'm having problems with non-latin characters in the PayPal contact form. =

A: New: Use the "throubleshoot area" to change the character encoding. If you prefer to do that manually , in most cases the problem is located in the database table collation/encoding. The solution is to change the table encoding to UTF-8. You can do that from the PHPMyAdmin provided by your hosting service.

For example, if your WordPress database table prefix is "wp_" (the default one) then run these queries (will update only the PayPal contact form tables):

    alter table wp_cp_contact_form_paypal_discount_codes convert to character set utf8 collate utf8_unicode_ci;
    alter table wp_cp_contact_form_paypal_settings convert to character set utf8 collate utf8_unicode_ci;
    alter table wp_cp_contact_form_paypal_posts convert to character set utf8 collate utf8_unicode_ci;

If you don't know how to do that, contact our support service and we will help you.

= Q: The contact form doesn't appear in the public website. Solution? =

A: In the "throubleshoot area" (located below the list of forms in the settings area) change the "Script load method" from "Classic" to "Direct".

= Q: How can I duplicate a form and its settings? =

A: Use the "Clone" button located in the contact form's list. That button will duplicate the contact form structure and all its settings.

= Q: How to setup the CP Contact Form with PayPal to accept a PayPal donation? =

A: To accept a PayPal donation (an open donation amount) just put a zero (0) on the "Request Cost" settings field. That way after filling the contact form clicking the PayPal button (contact form submit button) the PayPal payment page will appear letting the user to enter the amount to pay.

= Q: How to show a company name instead the email address at the PayPal payment page? =

A: To show a company name instead the email address at the PayPal payment page (after the contact form submission) you have to use a PayPal Standard Business account. Note that in Personal and Premier Standard accounts the email is shown instead the company name since there is no company name in that case.

Note also that if you are testing the contact form in the SandBox mode then the email may be shown instead the name of the production account.


== Other Notes ==

**Requesting address at PayPal:** If you are selling tangible items and you need to request the customer address at PayPal you can enable that option into the settings field "Request address at PayPal" available separately for each contact form.

**Taxes at PayPal:** You can indicate the taxes to charge at PayPal over the "request cost" as a percent into the settings field "Taxes (percent)". Each contact form can have a different taxes setting.

**Edit submit button label:** You can easily edit the submit button label into each contact form settings. The **class="cp_subbtn"** can be used to modify the button styles. The styles can be applied into any of the CSS files of your theme or into the CSS file "cp-contact-form-with-paypal\css\stylepublic.css". For further modifications the submit button is located at the end of the file "cp_contactformpp_public_int.inc.php".

**Use a specific field from the form for the payment amount:** If a field is selected in this settings field, any price in the selected field will be added to the above request cost. Use this field for example for having an open donation amount. This field is more useful in the pro version since it supports adding more fields to the contact form.

**Button to change status to paid:** The messages list contains a button to change the status of the "Not paid" contact form messages to "Paid". This is mainly for administrative purposes.

**Export data to CSV/Excel:** The messages list contains an option to export the contact messages received from the contact form to a CSV/Excel file. This way you can export the email address and other data from the contact messages to other applications or manage the data in Excel. The filters in the message list apply also to the exported CSV/Excel file.


== Screenshots ==

1. PayPal Contact Forms List
2. PayPal Contact Form Settings
3. Inserting a PayPal contact form into a page
4. Sample PayPal contact form

== Changelog ==

= 1.0 =
* First stable version released.
* More configuration options added.

= 1.0.1 =
* Compatible with latest WordPress versions
* Speed improvements, the contact form loads faster
* Improved validation options for the contact form fields
* New email content editing feature and interface changes
* New tooltip scripts
* Fixed bug related to discount codes
* Fixed conflict with captcha image generation.
* New feature to get the logged in user information into the notification email
* Update to CSS styles for minimizing the CSS conflicts.
* Improvements to CSS styles.
* Fixes problem with backslash when saving the contact form settings.
* PayPal Sandbox option added
* Added language support through MO/PO files

= 1.1.2 =
* Compatible with the latest WP versions
* Better interface and access to the plugin options
* Captcha image works better in different server environments
* New translations added
* Minor bug fixes

Important note: If you are using the Professional version don't update via the WP dashboard but using your personal update link. Contact us if you need further information: http://wordpress.dwbooster.com/support

== Upgrade Notice ==

Very Important note: If you are using the Professional version don't update via the WP dashboard but using your personal update link. Contact us if you need further information: http://wordpress.dwbooster.com/support

= 1.1.2 =
* Compatible with the latest WP versions
* Better interface and access to the plugin options
* Captcha image works better in different server environments
* New translations added
* Minor bug fixes

Important note: If you are using the Professional version don't update via the WP dashboard but using your personal update link. Contact us if you need further information: http://wordpress.dwbooster.com/support
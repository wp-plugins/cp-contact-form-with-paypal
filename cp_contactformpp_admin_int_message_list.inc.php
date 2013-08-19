<?php

if ( !is_admin() )
{
    echo 'Direct access not allowed.';
    exit;
}

if (!defined('CP_CONTACTFORMPP_ID'))
    define ('CP_CONTACTFORMPP_ID',intval($_GET["cal"]));

global $wpdb;
$myform = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE .' WHERE id='.CP_CONTACTFORMPP_ID);


$current_page = intval($_GET["p"]);
if (!$current_page) $current_page = 1;
$records_per_page = 50;                                                                                  

$cond = '';
if ($_GET["search"] != '') $cond .= " AND (data like '%".esc_sql($_GET["search"])."%' OR paypal_post LIKE '%".esc_sql($_GET["search"])."%')";
if ($_GET["dfrom"] != '') $cond .= " AND (`time` >= '".esc_sql($_GET["dfrom"])."')";
if ($_GET["dto"] != '') $cond .= " AND (`time` <= '".esc_sql($_GET["dto"])." 23:59:59')";


$events = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE formid=".CP_CONTACTFORMPP_ID.$cond." ORDER BY `time` DESC" );
$total_pages = ceil(count($events) / $records_per_page);

?>
<div class="wrap">
<h2>CP Contact Form with Paypal - Message List</h2>

<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=cp_contact_form_paypal';">


<div id="normal-sortables" class="meta-box-sortables">
 <hr />
 <h3>This message list is from: <?php echo $myform[0]->form_name; ?></h3>
</div>


<form action="admin.php" method="get">
 <input type="hidden" name="page" value="cp_contact_form_paypal" />
 <input type="hidden" name="cal" value="<?php echo CP_CONTACTFORMPP_ID; ?>" />
 <input type="hidden" name="list" value="1" />
 Search for: <input type="text" name="search" value="<?php echo esc_attr($_GET["search"]); ?>" /> &nbsp; &nbsp; &nbsp; 
 From: <input type="text" id="dfrom" name="dfrom" value="<?php echo esc_attr($_GET["dfrom"]); ?>" /> &nbsp; &nbsp; &nbsp; 
 To: <input type="text" id="dto" name="dto" value="<?php echo esc_attr($_GET["dto"]); ?>" /> &nbsp; &nbsp; &nbsp; 
 <span class="submit"><input type="submit" name="ds" value="Filter" /></span>
</form>

<br />
                             
<?php


echo paginate_links(  array(
    'base'         => 'admin.php?page=cp_contact_form_paypal&cal='.CP_CONTACTFORMPP_ID.'&list=1%_%&dfrom='.urlencode($_GET["dfrom"]).'&dto='.urlencode($_GET["dto"]).'&search='.urlencode($_GET["search"]),
    'format'       => '&p=%#%',
    'total'        => $total_pages,
    'current'      => $current_page,
    'show_all'     => False,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => True,
    'prev_text'    => __('&laquo; Previous'),
    'next_text'    => __('Next &raquo;'),
    'type'         => 'plain',
    'add_args'     => False
    ) );

?>

<div id="dex_printable_contents">
<table class="wp-list-table widefat fixed pages" cellspacing="0">
	<thead>
	<tr>
	  <th style="padding-left:7px;font-weight:bold;">Date</th>
	  <th style="padding-left:7px;font-weight:bold;">Email</th>
	  <th style="padding-left:7px;font-weight:bold;">Message</th>
	  <th style="padding-left:7px;font-weight:bold;">Payment Info</th>	  
	</tr>
	</thead>
	<tbody id="the-list">
	 <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
	  <tr class='<?php if (!($i%2)) { ?>alternate <?php } ?>author-self status-draft format-default iedit' valign="top">
		<td><?php echo substr($events[$i]->time,0,16); ?></td>
		<td><?php echo $events[$i]->notifyto; ?></td>
		<td><?php echo str_replace("\n","<br />",$events[$i]->data); ?></td>
		<td>
		    <?php if ($events[$i]->paid) echo '<span style="color:#00aa00;font-weight:bold">'.__("Paid").'</span><hr />'.str_replace("\n","<br />",$events[$i]->paypal_post); else echo '<span style="color:#ff0000;font-weight:bold">'.__("Not Paid").'</span>'; ?>
		    
		</td>
      </tr>
     <?php } ?>
	</tbody>
</table>
</div>

<p class="submit"><input type="button" name="pbutton" value="Print" onclick="do_dexapp_print();" /></p>

</div>


<script type="text/javascript">
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
      w.print();
      w.close();    
 }
 
 var $j = jQuery.noConflict();
 $j(function() {
 	$j("#dfrom").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 	$j("#dto").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 });
 
</script>















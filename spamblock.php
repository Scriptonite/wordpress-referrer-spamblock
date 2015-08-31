<?php
/*
Plugin Name: Simple Spam Block
Description: Simple referrer spam blocker that redirects spam links back to their own site.  
Version: 1.0
Author Innovative Solutions
Author URI: http://whereyoursolutionis.com 
*/

function initial_spammers_load() {
$known=array('buttons-for-website.com',
'best-seo-offer.com',
'buy-cheap-online.info',
'4webmasters.org',
'googlsucks.com',
'buttons-for-your-website.com',
'free-social-buttons.com',
'guardlink.org',
'100dollars-seo.com',
'event-tracking.com',
'best-seo-offer.com',
'localhometown.com',
'Get-Free-Traffic-Now.com',
'semalt.com',
'semaltmedia.com',
'trafficmonetize.com',
'free-social-buttons.com',
'darodar.com.com',
'free-social-buttons.com',
'success-seo.com',
'event-tracking.com',
'chinese-amezon.com',
'buttons-for-website.com',
'Get-Free-Traffic-Now.com',
'e-buyeasy.com',
'videos-for-your-business.com',
'erot.co',
'local.com',
'success-seo.com',
'c3.ezanga.com'
);
update_option('wp_spammers',$known);
}
register_activation_hook( __FILE__, 'initial_spammers_load' );
 


 
add_action('init','IncomingSpammerCheck');

function IncomingSpammerCheck(){
$incoming = $_SERVER['HTTP_REFERER'];
$spammers = get_option('wp_spammers');

foreach($spammers as $spam){
	if(strpos($incoming,$spam) !==FALSE){
	$linkback = parse_url($incoming);	
	header('location:'.$linkback['host']);	
	}
}	
return;	
	
}


add_action( 'admin_menu', 'spammer_options' );

function spammer_options() {
	add_options_page( 'Block Spam Referrers', 'Spam Referrer', 'manage_options', 'set-spam-options-page', 'spammer_options_page' );
}

function spammer_options_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
if(isset($_POST['spams'])){
update_option('wp_spammers',$_POST['spams']);
}	
	
wp_enqueue_script('jquery');
	echo '<div class="wrap">'; 

?>
<script>
function AddItemEnd(){
newspam = jQuery('#newspambottom').val();	
jQuery('#spammertable tr:last').after('<tr><td><input type="hidden" name="spams[]" value="'+newspam+'"/>'+newspam+'</td><td><a href="" onclick="jQuery(this).closest(\'tr\').remove();">Remove</a></td></tr>');	
newspam = jQuery('#newspambottom').val('');	
	
}
function AddItemTop(){
newspam = jQuery('#newspamtop').val();	
jQuery('#spammertable tr:first').before('<tr><td><input type="hidden" name="spams[]" value="'+newspam+'"/>'+newspam+'</td><td><a href="" onclick="jQuery(this).closest(\'tr\').remove();">Remove</a></td></tr>');	
newspam = jQuery('#newspamtop').val('');	
	
}

</script> 
<h2>Spam Referrer Management</h2>
Add Spammer: <input type="text" id="newspamtop" width="200px" />
<a href="javascript:;" onclick="AddItemTop();"><button  >Add to List</button></a>
<?php	
$spammers = get_option('wp_spammers');

echo '<form action="options-general.php?page=set-spam-options-page" method="post"><input type="submit" value="Save Spam List" /><br /><table class="widefat" id="spammertable">'; 
foreach($spammers as $spam){
echo '<tr><td><input type="hidden" name="spams[]" value="'.$spam.'"/>'.$spam.'</td><td><a href="" onclick="jQuery(this).closest(\'tr\').remove();">Remove</a></td></tr>';	
}	

echo '</table><br /><input type="submit" value="Save Spam List" /></form>';
	
?>

Add Spammer: <input type="text" id="newspambottom" width="200px" />
<a href="javascript:;" onclick="AddItemEnd();"><button  >Add to List</button></a>
<?php
	echo '</div>';
}


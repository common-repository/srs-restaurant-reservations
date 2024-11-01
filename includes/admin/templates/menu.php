<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function call_main_menu(){
	?>
    <a href="<?php echo admin_url('admin.php?page=srs-reservations'); ?>" class="<?php echo $_GET['page']=='srs-reservations'?'active':'not-active' ?>">Dashboard</a>
    <a href="<?php echo admin_url('admin.php?page=srs-reservations-settings'); ?>" class="<?php echo $_GET['page']=='srs-reservations-settings'?'active':'not-active' ?>">Settings</a>
	<?php 
}

?>
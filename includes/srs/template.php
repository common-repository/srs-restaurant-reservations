<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_shortcode('srs_reservations', 'srs_reservations_shortcode');
function srs_reservations_shortcode(){
    $srs_frontend = new srs_frontEnd_ops();
    ob_start();
    ?>
        <div class="srs-frontend-wrapper">
            <div class="srs-frontend">
                <?php $srs_frontend->add_shortcode(); ?>
            </div>
        </div>
    <?php
    return ob_get_clean();
}
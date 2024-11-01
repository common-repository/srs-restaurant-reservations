<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function call_admin_homepage(){
//    global $srs_reservations;
//    $srs_reservations->setSelectedDate_init();

    // Confirm Reservation
    if($_GET['confirm_reservation']){
        $confirm_reservation_id = sanitize_text_field( $_GET['confirm_reservation'] );
        $confirmed = reservations_ops::confirm_reservation($confirm_reservation_id);
        if(false !== $confirmed){
//            echo "Reservation Confirmed";
            srs_reservations::save_action_message('Reservation Confirmed!');
        }
    }

    // Delete Reservation
    if($_GET['delete_reservation']){
        $delete_reservation_id = sanitize_text_field( $_GET['delete_reservation'] );
        $deleted = reservations_ops::delete_reservation($delete_reservation_id);
        if(false !== $deleted){
//            echo "Reservation Deleted";
            srs_reservations::save_action_message('Reservation Deleted');
        }
    }

    // Intercept if make-reservation is requested
    // Get postdata and feed to reservation form
    if($_GET['edit_reservation']){
        $edit_reservation_id = sanitize_text_field( $_GET['edit_reservation'] );
        $postData = reservations_ops::get_reservation_by_id($edit_reservation_id);
        srs_reservations::srs_debug($postData, 'Edit reservation - retrieved data', 'a');
        $postData = reservations_ops::retrieved_reservation_data_to_make_reservation_postData($postData);
        srs_reservations::srs_debug($postData, 'Edit reservation - retrieved data - after conversion to postData format', 'a');
    }else {
        $postData = reservations_ops::srs_make_reservation();
        srs_reservations::srs_debug($postData, 'Make reservation Post data', 'a');
        if($postData == "success"){
            $postData = array(
                'id'=>'',
                'sex'=>'',
                'lastname'=>'',
                'firstbame'=>'',
                'phone'=>'',
                'email'=>'',
                'house'=>'',
                'zip'=>'',
                'town'=>'',
                'street'=>'',
                'city'=>'',
                'date'=>'',
                'time'=>'',
                'duration'=>'',
                'no-people'=>'',
                'comments'=>'',
                'type'=>'',
                'employee-code'=>''
            );
        }
    }
    srs_reservations::$reservations = reservations_ops::get_reservations_by_date('')
	?>
	<div id="srs-main">
        <div class="srs-main-menu">
            <?php call_main_menu()?>
        </div>
		<div class="srs-container">
			<div class="srs-header">
				<?php srs_add_header() ?>	
			</div>

            <div class="srs-body-wrapper">
                <div class="srs-body">
                    <?php srs_make_reservation($postData) ?>
                </div>

                <div class="srs-body">
                    <?php srs_list_reservations() ?>
                </div>
            </div>
<!--            <div class="srs-error-messages">-->
<!--                <h3>Debug Messages</h3>-->
<!--                --><?php
//                    foreach(srs_reservations::$error_message as $error_message) {
//                        ?><!--<div class="srs-error-message">-->
<!--                        <h4>--><?php //echo $error_message['event'] ?><!--</h4>--><?php
//                        if ($error_message['type'] == 'a'){ ?>
<!--                            <pre>--><?php //print_r($error_message['message']); ?><!--</pre>--><?php
//                        }else {
//                            echo $error_message['message'];
//                        }?><!--</div>--><?php
//                    }
//                ?>
<!--            </div>-->
		</div>
		
		
	</div>
	<?php 
}

?>
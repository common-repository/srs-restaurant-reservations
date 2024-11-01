<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function srs_list_reservations(){
    $reservations = srs_reservations::$reservations;
    $page_url_query = remove_query_arg(array('edit_reservation', 'confirm_reservation', 'delete_reservation'));
	?>
    <div class="srs-list-reservations">
        <div class="top">
            <h3>Reservations</h3>
        </div>

        <div class="filters">
            <form method="post" action="">
                <div><h4>Filter</h4></div>
                <div>Date From <input type="text" name="srsListReservationsFilterFromDate" id="srsListReservationsFilterFromDate" value="<?php echo $_SESSION['srsListReservationsFilterFromDate']!=''?$_SESSION['srsListReservationsFilterFromDate']:srs_reservations::$selectedDate; ?>"> to <input type="text" name="srsListReservationsFilterToDate" id="srsListReservationsFilterToDate" value="<?php echo $_SESSION['srsListReservationsFilterToDate']!=''?$_SESSION['srsListReservationsFilterToDate']:srs_reservations::$selectedDate; ?>"></div>
                <div><input type="submit" name="srs-set-filter" id="srs-set-filter" class="button" value="Filter"></div>
                <div><input type="submit" name="srs-reset-filter" id="srs-reset-filter" class="button" value="Reset"></div>
            </form>
        </div>

        <div class="srs-list-reservations-header">
            <div class="date">Date</div>
            <div class="time">Time</div>
            <div class="name">Name</div>
            <div class="people">People</div>
            <div class="table">Table</div>
            <div class="comment">Comment</div>
            <div class="admin">Manage</div>
        </div>

        <div class="srs-list-reservations-type-heading">
            <h3>Pending</h3>
        </div>
        <div class="srs-list-reservations-body">
            <?php
            foreach($reservations as $reservation){
                if($reservation['res_status'] == 'pending'){ ?>
                    <div class="srs-list-reservations-row">
                        <div class="date"><?php echo $reservation['res_date'] ?></div>
                        <div class="time"><?php echo $reservation['res_time'] ?></div>
                        <div class="name"><?php echo $reservation['cst_first_name'] ?> <?php echo $reservation['cst_last_name'] ?></div>
                        <div class="people"><?php echo $reservation['res_no_guests'] ?></div>
                        <div class="table"><?php echo $reservation['res_assigned_table'] ?></div>
                        <div class="comment"><?php echo $reservation['res_comment'] ?></div>
                        <div class="admin">
                            <a href="<?php echo add_query_arg(array('edit_reservation'=>$reservation['res_id']), $page_url_query) ?>">edit</a>
                            <a href="<?php echo add_query_arg(array('confirm_reservation'=>$reservation['res_id']), $page_url_query) ?>">Confirm</a>
                            <a href="<?php echo add_query_arg(array('delete_reservation'=>$reservation['res_id']), $page_url_query) ?>">Delete</a>
                        </div>


                    </div>

            <?php }
            }?>
        </div>

        <div class="srs-list-reservations-type-heading">
            <h3>Confirmed</h3>
        </div>
        <div class="srs-list-reservations-body">
            <?php
            foreach($reservations as $reservation){
                if($reservation['res_status'] == 'confirmed'){ ?>
                    <div class="srs-list-reservations-row">
                        <div class="date"><?php echo $reservation['res_date'] ?></div>
                        <div class="time"><?php echo $reservation['res_time'] ?></div>
                        <div class="name"><?php echo $reservation['cst_first_name'] ?> <?php echo $reservation['cst_last_name'] ?></div>
                        <div class="people"><?php echo $reservation['res_no_guests'] ?></div>
                        <div class="table"><?php echo $reservation['res_assigned_table'] ?></div>
                        <div class="comment"><?php echo $reservation['res_comment'] ?></div>
                        <div class="admin">
                            <a href="<?php echo add_query_arg(array('edit_reservation'=>$reservation['res_id']), $page_url_query) ?>">edit</a>
                            <a href="<?php echo add_query_arg(array('delete_reservation'=>$reservation['res_id']), $page_url_query) ?>">Delete</a>
                        </div>

                    </div>

                <?php }
            }?>
        </div>

        <div class="srs-list-reservations-type-heading">
            <h3>Deleted</h3>
        </div>
        <div class="srs-list-reservations-body">
            <?php
            foreach($reservations as $reservation){
                if($reservation['res_status'] == 'deleted'){ ?>
                    <div class="srs-list-reservations-row">
                        <div class="date"><?php echo $reservation['res_date'] ?></div>
                        <div class="time"><?php echo $reservation['res_time'] ?></div>
                        <div class="name"><?php echo $reservation['cst_first_name'] ?> <?php echo $reservation['cst_last_name'] ?></div>
                        <div class="people"><?php echo $reservation['res_no_guests'] ?></div>
                        <div class="table"><?php echo $reservation['res_assigned_table'] ?></div>
                        <div class="comment"><?php echo $reservation['res_comment'] ?></div>
                        <div class="admin"><a href="<?php echo add_query_arg(array('edit_reservation'=>$reservation['res_id']), $page_url_query) ?>">edit</a></div>

                    </div>

                <?php }
            }?>
        </div>

    </div>
	<?php
}

?>
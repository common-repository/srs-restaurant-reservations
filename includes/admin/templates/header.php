<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function srs_add_header(){
	?>
		<div class="srs-calender">
            <?php srs_calendar::showCalendar(srs_reservations::$selectedDate); ?>
		</div>
        <div class="srs-selected-date">
            <div class="selected-date-title">Selected Day</div>
            <div class="DayOfWeek">
                <div class="prev-day"><a href="<?php echo add_query_arg( array('selected_date'=>srs_calendar::$selectedPrevDay) ) ?>"><</a></div>
                <div>
                    <?php echo srs_calendar::getDayOfWeek(srs_reservations::$selectedDate); ?>
                </div>
                <div class="next-day"><a href="<?php echo add_query_arg( array('selected_date'=>srs_calendar::$selectedNextDay) ) ?>">></a></div>
            </div>
            <div class="Date"><?php echo srs_reservations::$selectedDate; ?></div>
            <div class="total-reservations-title">Reservations</div>
            <div class="total-reservation"><?php echo reservations_ops::get_reservations_by_date_count(srs_reservations::$selectedDate, array('confirmed')); ?></div>
        </div>
        <div class="srs-graph">
            <?php srs_graph::showGraph() ?>
            <div class="srs-graph-container" id="srs-graph-container"></div>
        </div>

	<?php 
}
?>
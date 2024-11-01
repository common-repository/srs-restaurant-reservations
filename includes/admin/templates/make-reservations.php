<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function srs_make_reservation($postData){
    $settingsData = reservations_ops::srs_get_settings(); ?>
    <div class="action-message"><?php echo srs_reservations::$action_message ?></div>
    <div class="srs-make-reservation">
        <div class="top">
            <?php if($_GET['edit_reservation']){ ?>
            <h3>Update Reservation</h3>
            <?php }else{ ?>
            <h3>Create a New Reservation</h3>
            <?php } ?>
        </div>

        <div class="srs-make-reservation-form">
            <form id="make-reservation-form" method="post" action="<?php echo home_url(); ?>/wp-admin/admin.php?page=srs-reservations">
                <input name="make-reservation[source]" type="hidden" value="backend">
                <div class="left">
                    <div class="form-row">
                        <label for="make-reservation[id]">Reservation #</label>
                        <div class="field-div">
                            <input type="text" disabled name="make-reservation-id" value="<?php echo $postData['id'] ?>">
                            <input type="hidden" name="make-reservation[id]" value="<?php echo $postData['id'] ?>">
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[sex]">Sex</label>
                        <div class="field-div radio">
                            <?php srs_frontEnd_ops::get_formField_sex(srs_reservations::$selectedDate, array('sex'=>$postData['sex']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[firstname]">First Name</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_firstname(srs_reservations::$selectedDate, array('firstname'=>$postData['firstname']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[lastname]">Last Name</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_lastname(srs_reservations::$selectedDate, array('lastname'=>$postData['lastname']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[phone]">Phone</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_phone(srs_reservations::$selectedDate, array('phone'=>$postData['phone']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[email]">Email</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_email(srs_reservations::$selectedDate, array('email'=>$postData['email']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[house]">House/Street</label>
                        <div class="field-div">
                            <input type="text" name="make-reservation[house]" id="make-reservation-house" class="make-reservation-house" value="<?php echo $postData['house'] ?>">
                            <input type="text" name="make-reservation[street]" id="make-reservation-street" class="make-reservation-street" value="<?php echo $postData['street'] ?>">
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

<!--                        <div class="form-row">-->
<!--                            <label for="make-reservation[street]">Street</label>-->
<!--                            <div class="field-div">-->
<!--                                -->
<!--                                <div class="clr"></div>-->
<!--                            </div>-->
<!--                            <div class="clr"></div>-->
<!--                        </div>-->

                    <div class="form-row">
                        <label for="make-reservation[town]">Town</label>
                        <div class="field-div">
                            <input type="text" name="make-reservation[town]" class="make-reservation-town" value="<?php echo $postData['town'] ?>">
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation-[city]">City</label>
                        <div class="field-div">
                            <input type="text" name="make-reservation[city]" class="make-reservation-city" value="<?php echo $postData['city'] ?>">
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[zip]">Zip/Post</label>
                        <div class="field-div">
                            <input type="text" name="make-reservation[zip]" class="make-reservation-zipcode" value="<?php echo $postData['zip'] ?>">
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>

                <div class="right">
                    <?php if($_GET['edit_reservation']){ ?>
                        <div class="form-row">
                            <label for="make-reservation[time]">Status</label>
                            <div class="field-div">
                                <select name="make-reservation[status]">
                                    <option value="pending" <?php echo $postData['status']=='pending'?'selected':'' ?>>Pending</option>
                                    <option value="confirmed" <?php echo $postData['status']=='confirmed'?'selected':'' ?>>Confirmed</option>
                                    <option value="deleted" <?php echo $postData['status']=='deleted'?'selected':'' ?>>Deleted</option>
                                </select>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                    <?php } ?>

                    <div class="form-row">
                        <label for="make-reservation[date]">Date</label>
                        <div class="field-div"><?php global $srs_reservations ?>
                            <input type="text" disabled name="make-reservation[date-disabled]" value="<?php echo isset($postData)&&$postData['date']!=''?$postData['date']:srs_reservations::$selectedDate ?>">
                            <input type="hidden"  name="make-reservation[date]" value="<?php echo isset($postData)&&$postData['date']!=''?$postData['date']:srs_reservations::$selectedDate ?>">
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[time]">Time</label>
                        <div class="field-div">
                            <?php srs_calendar::srs_time(srs_reservations::$selectedDate, array('time'=>$postData['time'])); ?>
                            </select>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row">
                        <label for="make-reservation[no-people]">Number of people</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_people(srs_reservations::$selectedDate, array('no-people'=>$postData['no-people']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>


                    <div class="form-row">
                        <label for="make-reservation[type]">Type</label>
                        <div class="field-div">
                            <select name="make-reservation[type]">
                                <option <?php echo $postData['type']=='phone'?'selected':'' ?> value="phone">Phone</option>
                                <option <?php echo $postData['type']=='walkin'?'selected':'' ?> value="walkin">Walk-in</option>
                                <option <?php echo $postData['type']=='internet'?'selected':'' ?> value="internet">Internet</option>
                            </select>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

<!--                    <div class="form-row">-->
<!--                        <label for="make-reservation-assign-table">Table Number</label>-->
<!--                        <div class="field-div">-->
<!---->
<!--                            <div class="clr"></div>-->
<!--                        </div>-->
<!--                        <div class="clr"></div>-->
<!--                    </div>-->

                    <div class="form-row">
                        <label for="make-reservation[duration]">Length of stay</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_duration(srs_reservations::$selectedDate, array('duration'=>$postData['duration']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

<!--                    <div class="form-row">-->
<!--                        <label for="make-reservation-confirm-email">Email Confirmation to Customer</label>-->
<!--                        <div class="field-div email-confirmation">-->
<!--                            <input type="checkbox" name="make-reservation-confirm-email" checked>-->
<!--                            <div class="clr"></div>-->
<!--                        </div>-->
<!--                        <div class="clr"></div>-->
<!--                    </div>-->

                    <div class="form-row">
                        <label for="make-reservation[employee-code]">Employee Code</label>
                        <div class="field-div">
                            <input type="text" name="make-reservation[employee-code]" class="make-reservation-employee-code" value="<?php echo $postData['employee-code'] ?>">
<!--                            <div class="employee-code-confirmation">-->
<!--                                <label for="make-reservation[employee-code-confirmation]">Confirm</label>-->
<!--                                <div>-->
<!--                                    <input type="checkbox" name="make-reservation-employee-code-confirmation">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="clr"></div>-->
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row form-row-comments">
                        <label for="make-reservation[comments]">Comments</label>
                        <div class="field-div">
                            <?php srs_frontEnd_ops::get_formField_comments(srs_reservations::$selectedDate, array('comments'=>$postData['comments']))?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="form-row-create-button">
                        <label for="make-reservation-make-reservation-save"></label>
                        <div class="field-div">
                            <?php if($_GET['edit_reservation']){ ?>
                            <input type="button" name="make-reservation-form-save" id="make-reservation-form-save" class="button button-primary" value="Update Reservation">
                            <a class="button button-secondary" href="<?php echo remove_query_arg(array('edit_reservation', 'delete_reservation')) ?>">Cancel</a>
                            <?php }else{ ?>
                            <input type="button" name="make-reservation-form-save" id="make-reservation-form-save" class="button button-primary" value="Create Reservation">
                            <?php } ?>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class="clr"></div>

                <input type="hidden" name="reservation-form-posted" value="true">
            </form>
        </div>
    </div>
	<?php
}

?>
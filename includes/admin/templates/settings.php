<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
function call_admin_settings(){
    if($_POST['srs-reservations-settings-save']){
        $settingsData = reservations_ops::srs_save_settings($_POST['srs-reservations-settings']);
    }else{
        $settingsData = reservations_ops::srs_get_settings();
//        print_r($settingsData);
    }//

	?>
	<div id="srs-main">
        <div class="srs-main-menu">
            <?php call_main_menu()?>
        </div>
		<div class="srs-container">
            <div class="srs-body-wrapper">
                <div class="srs-body">
                    <div class="srs-reservations-settings-page">
                        <form name="srs-reservations-settings" method="post" action="">
                            <div class="top">
                                <h3>Settings</h3>
                            </div>

                            <div class="form-row">
                                <div class="label-div">
                                    <label for="srs-reservations-settings[opening-time]">Timings</label>
                                </div>
                                <div class="field-div">
                                    <select name="srs-reservations-settings[opening-time]">
                                        <?php
                                        for($hours=0;$hours<=23;$hours++){
                                            $mins = 0;
                                            if($hours<10){
                                                $hours = "0$hours";
                                            }
                                            while ($mins<=45){
                                                if($mins<10){
                                                    $mins = "0$mins";
                                                }
                                                ?>
                                                <option <?php echo $settingsData['opening-time']=="$hours:$mins"?'selected':'' ?> value = '<?php echo "$hours:$mins" ?>'><?php echo "$hours:$mins" ?></option>
                                                <?php $mins+=15 ;
                                            }
                                        }
                                        ?>
                                    </select> to
                                    <select name="srs-reservations-settings[closing-time]">
                                        <?php
                                        for($hours=0;$hours<=23;$hours++){
                                            $mins = 0;
                                            if($hours<10){
                                                $hours = "0$hours";
                                            }
                                            while ($mins<=45){
                                                if($mins<10){
                                                    $mins = "0$mins";
                                                }
                                                ?>
                                                <option <?php echo $settingsData['closing-time']=="$hours:$mins"?'selected':'' ?> value = '<?php echo "$hours:$mins" ?>'><?php echo "$hours:$mins" ?></option>
                                                <?php $mins+=15 ;
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span class="">Opening and Closing times</span>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr"></div>
                            </div>

                            <div class="form-row">
                                <div class="label-div">
                                    <label for="srs-reservations-settings[max-guests]">Max Guests</label>
                                </div>
                                <div class="field-div">
<!--                                    <input type="text" name="srs-reservations-settings[max-guests]" value="--><?php //echo $settingsData!=''?$settingsData['max-guests']:'' ?><!--">-->
                                    <select name="srs-reservations-settings[max-guests]">
                                        <?php for($x=1;$x<=100;$x++){ ?>
                                            <option value="<?php echo $x ?>" <?php echo $settingsData['max-guests']==$x?'selected':'' ?>><?php echo $x ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="">Maximum number of people allowed for booking</span>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr"></div>
                            </div>

                            <div class="form-row">
                                <div class="label-div">
                                    <label for="srs-reservations-settings[max-reservation-duration]">Max Duration</label>
                                </div>
                                <div class="field-div">
<!--                                    <input type="text" name="srs-reservations-settings[max-reservation-duration]" value="--><?php //echo $settingsData!=''?$settingsData['max-reservation-duration']:'' ?><!--">-->
                                    <select name="srs-reservations-settings[max-reservation-duration]">
                                        <?php for($x=1;$x<49;$x++){
                                            $hours = intval(intval($x*30)/60);
                                            $hours = $hours<10?'0'.$hours:$hours;
                                            $hours = $hours. 'h ';
                                            $mins = intval(intval($x*30)%60);
                                            $mins = $mins>0?$mins.'m':'00m';
                                            ?>
                                            <option value="<?php echo $x ?>" <?php echo $settingsData['max-reservation-duration']==$x?'selected':'' ?>><?php echo  $hours.$mins ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="">Maximum length of stay (duration of booking)</span>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr"></div>
                            </div>

<!--                            <div class="form-row">-->
<!--                                <div class="label-div">-->
<!--                                    <label for="srs-reservations-settings[max-reservation-duration]">Working Days</label>-->
<!--                                </div>-->
<!--                                <div class="field-div checkbox">-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][monday]" value="true" --><?php ////echo $settingsData['working-days']['monday']=='true'?'checked':'' ?><!-- >-->
<!--                                    <span class="">Monday</span>-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][tuesday]" value="true" --><?php ////echo $settingsData['working-days']['tuesday']=='true'?'checked':'' ?><!-->
<!--                                    <span class="">Tuesday</span>-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][wednesday]" value="true"  --><?php ////echo $settingsData['working-days']['wednesday']=='true'?'checked':'' ?><!-- >-->
<!--                                    <span class="">Wednesday</span>-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][thursday]" value="true" --><?php ////echo $settingsData['working-days']['thursday']=='true'?'checked':'' ?><!-- >-->
<!--                                    <span class="">Thursday</span>-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][friday]" value="true" --><?php ////echo $settingsData['working-days']['friday']=='true'?'checked':'' ?><!-- >-->
<!--                                    <span class="">Friday</span>-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][saturday]" value="true" --><?php ////echo $settingsData['working-days']['saturday']=='true'?'checked':'' ?><!-- >-->
<!--                                    <span class="">Saturday</span>-->
<!--                                    <input type="checkbox" name="srs-reservations-settings[working-days][sunday]" value="true" --><?php ////echo $settingsData['working-days']['sunday']=='true'?'checked':'' ?><!-- >-->
<!--                                    <span class="">Sunday</span>-->
<!--                                </div>-->
<!--                                <div class="clr"></div>-->
<!--                            </div>-->

<!--                            <div class="settings-subheading">-->
<!--                                <h3>Email Settings</h3>-->
<!--                            </div>-->
<!---->
<!--                            <div class="form-row">-->
<!--                                <div class="label-div">-->
<!--                                    <label for="srs-reservations-settings[email-footer]">Email Header</label>-->
<!--                                </div>-->
<!--                                <div class="field-div">-->
<!--                                    <textarea name="srs-reservations-settings[email-header]" cols="50" rows="5">--><?php ////echo $settingsData!=''?$settingsData['email-header']:'Hello' ?><!--</textarea>-->
<!--                                    <div class="">Message that is included at the top each email sent to Customers</div>-->
<!--                                    <div class="clr"></div>-->
<!--                                </div>-->
<!--                                <div class="clr"></div>-->
<!--                            </div>-->
<!---->
<!--                            <div class="form-row">-->
<!--                                <div class="label-div">-->
<!--                                    <label for="srs-reservations-settings[email-footer]">Email Footer</label>-->
<!--                                </div>-->
<!--                                <div class="field-div">-->
<!--                                    <textarea name="srs-reservations-settings[email-footer]" cols="50" rows="5">--><?php ////echo $settingsData!=''?$settingsData['email-footer']:'Thanks' ?><!--</textarea>-->
<!--                                    <div class="">Message that is included at the bottom each email sent to Customers</div>-->
<!--                                    <div class="clr"></div>-->
<!--                                </div>-->
<!--                                <div class="clr"></div>-->
<!--                            </div>-->


                            <p class="submit-button">
                                <input class="button-primary" name="srs-reservations-settings-save" type="submit" value="Save All Changes">
                            </p>

                        </form>
                    </div>
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
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

final class srs_frontEnd_ops{
    public $current_step;

    public function __construct(){
        if(isset($_POST['srs-next-3'])) {
            $this->current_step = 3;
        }elseif(isset($_POST['srs-next-2'])){
           $this->current_step = 2;
        }else{
            $this->current_step = 1;
        }
    }

    public function add_shortcode(){

        if(isset($_GET['reservation-created']) && $_GET['reservation-created']=='success'){?>
            <span>Reservation Created! <br>
<!--                A confirmation email has been sent to the email address you provided.-->
            </span>
            <span><a href="<?php echo remove_query_arg(array('reservation-created')); ?>">Click Here</a> to go back and make another reservation.</span>
            <?php
//            exit;
        }else{
            // Store the $_POST values from the submitted form to a session variable
            if(isset($_POST['make-reservation'])){
                if (isset($_POST['srs-next-2'])) {
                    $_SESSION['make-reservation']['date'] = srs_reservations::$selectedDate;
                }

//            print_r($_POST['make-reservation']);
//            if( isset($_POST['make-reservation']['sex']) ){
//                echo 'lol';
//                $_POST['make-reservation'] = array('sex' => $_POST['make-reservation']['sex']) + $_POST['make-reservation'];
//            }
                foreach($_POST['make-reservation'] as $key=>$value){
                    $_SESSION['make-reservation'][$key] = $value;
                }
            }

            // When second form is filled and posted, takes values stored in session for all the form fields from both
            // forms and set them to the $_POST array for processing
            if(isset($_POST['make-reservation-form-save'])){
                foreach($_SESSION['make-reservation'] as $key=>$value){
                    $_POST['make-reservation'][$key] = $value;
                }

                $_POST['make-reservation']['type'] = 'internet';
                $_POST['make-reservation']['status'] = 'pending';
                $_POST['make-reservation']['employee-code'] = '99999';
                // Process the submitted form
                $postData = reservations_ops::srs_make_reservation();

                if($postData == 'success'){
                    ?>
                    <script type="text/javascript">
                        window.location = "<?php echo add_query_arg(array('reservation-created'=>'success')); ?>";
                    </script>
                    <?php
                    exit;
                }else{
                    $serverValidationErrorMessage =  'There was a validation error on the server side';
                    $_POST['srs-next-3']=true;
                }
            }

            if(!isset($_POST['make-reservation-form-save']) || $serverValidationErrorMessage) {
                // Display forms depending on steps
                ?>
                <div class="srs-frontend-container">
                    <div class="srs-frontend-title">
                        <h2>SRS Reservations</h2>
                    </div>
                    <div class="srs-frontend-step">
                        <h3><?php echo "Step " . $this->current_step . "/4" ?></h3>
                        <div class="error-messages"><?php echo isset($serverValidationErrorMessage)?$serverValidationErrorMessage:'' ?></div>
                    </div>
                    <?php if (isset($_POST['srs-next-3'])) {
                        echo "<h4>Confirmation</h4>";
                        echo "<strong>Reservation information</strong><br>";

                        $_SESSION['make-reservation'] = array('sex' => $_SESSION['make-reservation']['sex']) + $_SESSION['make-reservation'];

                        foreach ($_SESSION['make-reservation'] as $key => $value) {
                            if ($key == 'sex') {
                                if($value=='1'){
                                    $name = 'Mr';
                                }elseif($value=='2'){
                                    $name = 'Ms';
                                }else{
                                    $name = '';
                                }
                            } elseif ($key == 'firstname') {
                                $name = $name==''?$value:"$name. $value";
                            } elseif ($key == 'lastname') {
                                echo "<br><strong>Customer Information</strong><br>";
                                echo $name = "$name $value<br>";
                            } elseif ($key == 'status' || $key == 'type') {
                                continue;
                            } elseif($key == 'duration' ) {
                                $key = ucfirst($key);
                                echo "$key: $value hrs<br>";
                            } elseif($key == 'no-people' ) {
                                echo "Party of: $value people<br>";
                            } else {
                                $key = ucfirst($key);
                                echo "$key: $value <br>";
                            }
                        }?>
                        <form method="post" action="" style="margin-top: 20px;">
                            <input type="hidden" name="reservation-form-posted" value="true">
                            <input type="submit" name="srs-next-2" id="srs-next-2" class="button" value="Back">
                            <input type="submit" name="make-reservation-form-save" id="make-reservation-form-save"
                                   class="button button-primary" value="Complete Reservation">
                        </form>
                    <?php
                    } elseif (isset($_POST['srs-next-2'])) {
                        ?>
                        <h4>Customer Information</h4>
                        <form method="post" action="" id="srs-reservation-2">
                            <div class="srs-frontend-sex">
                                <?php srs_frontEnd_ops::get_formField_sex(srs_reservations::$selectedDate, array('sex' => isset($_SESSION['make-reservation']['sex'])?$_SESSION['make-reservation']['sex']:'')) ?>
                            </div>
                            <div class="srs-frontend-firstname">
                                <?php srs_frontEnd_ops::get_formField_firstname(srs_reservations::$selectedDate, array('firstname' => isset($_SESSION['make-reservation']['firstname'])?$_SESSION['make-reservation']['firstname']:'')) ?>
                            </div>
                            <div class="srs-frontend-lasttname">
                                <?php srs_frontEnd_ops::get_formField_lastname(srs_reservations::$selectedDate, array('lastname' => isset($_SESSION['make-reservation']['lastname'])?$_SESSION['make-reservation']['lastname']:'')) ?>
                            </div>
                            <div class="srs-frontend-email">
                                <?php srs_frontEnd_ops::get_formField_email(srs_reservations::$selectedDate, array('email' => isset($_SESSION['make-reservation']['email'])?$_SESSION['make-reservation']['email']:'')) ?>
                            </div>
                            <div class="srs-frontend-phone">
                                <?php srs_frontEnd_ops::get_formField_phone(srs_reservations::$selectedDate, array('phone' => isset($_SESSION['make-reservation']['phone'])?$_SESSION['make-reservation']['phone']:'')) ?>
                            </div>
                            <div class="srs-frontend-comments">
                                <?php srs_frontEnd_ops::get_formField_comments(srs_reservations::$selectedDate, array('comments' => isset($_SESSION['make-reservation']['comments'])?$_SESSION['make-reservation']['comments']:'')) ?>
                            </div>
                            <input type="submit" name="srs-next-1" id="srs-next-1" class="button" value="Back">
                            <input type="submit" name="srs-next-3" id="srs-next-3" class="button button-primary"
                                   value="Next">
                        </form>
                    <?php } else { ?>
                        <h4>Reservation Information</h4>
                        <form method="post" action="" id="srs-reservation-1">
                            <div class="srs-frontend-calendar">
                                <?php srs_calendar::showCalendar(srs_reservations::$selectedDate, array('date' => isset($_SESSION['make-reservation']['date'])?$_SESSION['make-reservation']['date']:'')); ?>
                            </div>
                            <div class="srs-frontend-time">
                                <label>Time</label>
                                <?php srs_calendar::srs_time(srs_reservations::$selectedDate, array('time' => isset($_SESSION['make-reservation']['time'])?$_SESSION['make-reservation']['time']:'')); ?>
                            </div>
                            <div class="srs-frontend-duration">
                                <label>Duration</label>
                                <?php srs_frontEnd_ops::get_formField_duration(srs_reservations::$selectedDate, array('duration' => isset($_SESSION['make-reservation']['duration'])?$_SESSION['make-reservation']['duration']:'')) ?>
                            </div>
                            <div class="srs-frontend-people">
                                <label>No. of Guests</label>
                                <?php srs_frontEnd_ops::get_formField_people(srs_reservations::$selectedDate, array('no-people' => isset($_SESSION['make-reservation']['no-people'])?$_SESSION['make-reservation']['no-people']:'')) ?>
                            </div>
                            <input type="submit" name="srs-next-2" id="srs-next-2" class="button button-primary"
                                   value="Next">
                        </form>
                    <?php } ?>
                </div>
            <?php
            }
        }

    }

    public static function get_formField_duration($date, $postData){
        ?>
        <select name="make-reservation[duration]" class="make-reservation-duration">
            <?php for($x=1;$x<=srs_reservations::$settingsData['max-reservation-duration'];$x++){
                $hours = intval(intval($x*30)/60);
                $hours = $hours<10?'0'.$hours:$hours;
                $hours_string = $hours. 'h ';
                $mins = intval(intval($x*30)%60);
                $mins = $mins==0?'00':$mins;
                $mins_string = $mins>0?$mins.'m':'00m';
                ?>
                <option value="<?php echo "$hours:$mins" ?>" <?php echo  $postData['duration']=="$hours:$mins"?'selected':'' ?>><?php echo  $hours_string.$mins_string ?></option>
            <?php } ?>
        </select>
    <?php
    }

    public static function get_formField_people($date, $postData){
        ?>
        <select name="make-reservation[no-people]" class="make-reservation-no-people">
            <?php
            for($noPeople = 1; $noPeople <= srs_reservations::$settingsData['max-guests']; $noPeople++) {
                ?>
                <option <?php echo $postData['no-people']==$noPeople?'selected':'' ?> value="<?php echo $noPeople ?>"><?php echo $noPeople ?> </option>
            <?php
            }
            ?>
        </select>
    <?php
    }

    public static function get_formField_sex($date, $postData){
        ?>
            <input type="radio" name="make-reservation[sex]" value="1" <?php echo $postData['sex']==1?'checked':'' ?> >
            <span>Mr</span>

            <input type="radio" name="make-reservation[sex]" value="2" <?php echo $postData['sex']==2?'checked':'' ?> >
            <span>Ms</span>
    <?php
    }

    public static function get_formField_firstname($date, $postData){
        ?>
        <input type="text" name="make-reservation[firstname]" value="<?php echo $postData['firstname'] ?>" class="make-reservation-firstname">
    <?php
    }

    public static function get_formField_lastname($date, $postData){
        ?>
        <input type="text" name="make-reservation[lastname]" value="<?php echo $postData['lastname'] ?>" class="make-reservation-lastname">
    <?php
    }

    public static function get_formField_email($date, $postData){
        ?>
        <input type="text" name="make-reservation[email]" value="<?php echo $postData['email'] ?>" class="make-reservation-email">
    <?php
    }

    public static function get_formField_phone($date, $postData){
        ?>
            <input type="text" name="make-reservation[phone]" value="<?php echo $postData['phone'] ?>" class="make-reservation-phone">
    <?php
    }

    public static function get_formField_comments($date, $postData){
        ?>
            <textarea name="make-reservation[comments]" class="make-reservation-comments"><?php echo $postData['comments'] ?></textarea>
    <?php
    }
}
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class srs_calendar{
    public static $selectedYear;
    public static $selectedMonth;
    public static $selectedMonth_digit;
    public static $selectedMonthDays;
    public static $selectedFirstDayOfMonth;
    public static $selectedNextMonth;
    public static $selectedPrevMonth;
    public static $selectedNextDay;
    public static $selectedPrevDay;

    public static function init($date){
        self::$selectedMonth            = self::getMonth($date);
        self::$selectedMonth_digit      = self::getMonth_digit($date);
        self::$selectedMonthDays        = self::getDaysInMonth($date);
        self::$selectedYear             = self::getYear($date);
        self::$selectedFirstDayOfMonth  = self::getFirstDayOfMonth($date);
        self::$selectedNextMonth        = substr(self::getNextMonth($date),0,7).'-01';
        self::$selectedPrevMonth        = substr(self::getPrevMonth($date),0,7).'-01';
        self::$selectedNextDay          = self::getNextDay($date);
        self::$selectedPrevDay          = self::getPrevDay($date);
    }

    public static function showCalendar($date, $postData=''){
        $page_url_query = remove_query_arg(array('edit_reservation', 'confirm_reservation', 'delete_reservation'));
        self::init($date);
        ?>
        <div class="srs-calendar-container">
            <div class="res_calendar_header">
                <div class="prev-btn">
                    <a href="<?php echo add_query_arg( array('selected_date'=>self::$selectedPrevMonth) )?>"><</a>
                </div>
                <div class="srs-calendar-month">
                    <?php echo self::$selectedMonth . " " . self::$selectedYear ?>
                </div>
                <div class="next-btn">
                    <a href="<?php echo add_query_arg( array('selected_date'=>self::$selectedNextMonth) )?>">></a>
                </div>
            </div>

            <div class="res_calendar_day_name">
                <div>Mo</div>
                <div>Tu</div>
                <div>We</div>
                <div>Th</div>
                <div>Fr</div>
                <div>Sa</div>
                <div>Su</div>
            </div>

            <div class="res_calendar_day_numbers">
                <?php
                $day = 1;
                $currentDay = 0;

                for($day;$day<=42;$day++){
                    if( $day == self::$selectedFirstDayOfMonth || ($day == 7 && self::$selectedFirstDayOfMonth == 0 )){
                        $startPrinting = 1;
                    }
                    if( isset($startPrinting) ) {
                        if ($startPrinting == 1) {
                            $currentDay++;
                        }
                        if ($startPrinting == 1 && $currentDay > self::$selectedMonthDays) {
                            $startPrinting = 0;
                        }
                        $currentDate = self::$selectedYear . "-" . self::$selectedMonth_digit . "-" . $currentDay;
                        ?>
                        <div class="<?php echo $currentDay==date('d',strtotime(srs_reservations::$selectedDate))?'active-date':'' ?>" data-selected-date="<?php echo $currentDay==date('d',strtotime(srs_reservations::$selectedDate))?srs_reservations::$selectedDate:'' ?>">
                            <a href="<?php echo add_query_arg(array('selected_date' => $currentDate), $page_url_query) ?>"> <?php echo $startPrinting == 1 ? $currentDay : ' ' ?></a>
                        </div>
                    <?php
                    }else{
                        ?>
                        <div>
                            <?php echo ' ' ?>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

    public static function srs_time($date, $postData){
        ?>
        <select name="make-reservation[time]" class="make-reservation-time">
            <?php
            $print_option = false;
            for($hours=0;$hours<=23;$hours++){
                $mins = 0;
                if($hours<10){
                    $hours = "0$hours";
                }
                while ($mins<=45){
                    if($mins<10){
                        $mins = "0$mins";
                    }
                    if(srs_reservations::$settingsData['opening-time']==="$hours:$mins"){
                        $print_option = true;
                    }elseif(srs_reservations::$settingsData['closing-time']==="$hours:$mins"){
                        $print_option = false; ?>
                        <option <?php echo $postData['time']=="$hours:$mins"?'selected':'' ?> value = '<?php echo "$hours:$mins" ?>'><?php echo "$hours:$mins" ?></option>
                        <?php
                    }
                    if($print_option){ ?>
                        <option <?php echo $postData['time']=="$hours:$mins"?'selected':'' ?> value = '<?php echo "$hours:$mins" ?>'><?php echo "$hours:$mins" ?></option>
                    <?php }
                    $mins+=15 ;
                }
            }
            ?>
        </select>
    <?php
    }


    public static function getMonth($date){
        return date('F', strtotime($date));
    }

    public static function getMonth_digit($date){
        return date('m', strtotime($date));
    }

    public static function getYear($date){
        return date('Y', strtotime($date));
    }

    public static function getDaysInMonth($date){
        return date('t', strtotime($date));
    }

    public static function getFirstDayOfMonth($date){
        srs_reservations::srs_debug(date('w', strtotime(substr($date,0,7).'-01')),"First day of month");
        return date('w', strtotime(substr($date,0,7).'-01'));
    }

    public static function getDayOfWeek($date){
        return date('l', strtotime($date));
    }

    public static function getNextMonth($date){
        srs_reservations::srs_debug(date('Y-m-d', strtotime($date."+1 month")), "Next Month");
        return date('Y-m-d', strtotime($date."+1 month"));
    }

    public static function getPrevMonth($date){
        return date('Y-m-d', strtotime($date."-1 month"));
    }

    public static function getNextDay($date){
        srs_reservations::srs_debug(date('Y-m-d', strtotime($date."+1 days")), "Next Day");
        return date('Y-m-d', strtotime($date."+1 days"));
    }

    public static function getPrevDay($date){
        return date('Y-m-d', strtotime($date."-1 days"));
    }
}
?>
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

final class reservations_ops{
    /**
     * Checks if a reservation form is submitted and creates a new reservation in the database
     */
    public static function srs_make_reservation()
    {
        global $wpdb;

        if ($_POST['reservation-form-posted']=='true') {
            $makeReservationPost = $_POST['make-reservation'];
            $validatedMakeReservationPost = self::srs_input_validate($makeReservationPost);

            $validatedMakeReservationPostData = $validatedMakeReservationPost['postData'];
            $validatedMakeReservationPostData_errorMessages = $validatedMakeReservationPost['errorMessages'];

            if( sizeof( $validatedMakeReservationPostData_errorMessages ) > 0 ){
                $validationErrorMessage =  "SERVER SIDE VALIDATION - There were errors! <br>";
                foreach($validatedMakeReservationPostData_errorMessages as $key => $value){
                    $validationErrorMessage .= "$key: $value <br>";
                }

                srs_reservations::save_action_message($validationErrorMessage);
                srs_reservations::srs_debug($makeReservationPost);
                // Returns $_POST['make-reservation'] data to repopulate the reservation form
                return $makeReservationPost;
            }else{
                srs_reservations::srs_debug($validatedMakeReservationPostData, '', "a");
//                srs_reservations::srs_debug($validatedMakeReservationPostData_errorMessages, '', "a");
                if($validatedMakeReservationPostData['source'] == 'backend'){
                    if( isset($validatedMakeReservationPostData['id']) && $validatedMakeReservationPostData['id']!='' ){
                        $reservation_status = $validatedMakeReservationPostData['status'];
                    }else{
                        $reservation_status = 'confirmed';
                    }
                }else{
                    $reservation_status = 'pending';
                }

                if( isset($validatedMakeReservationPostData['id']) && $validatedMakeReservationPostData['id']!='' ){
                    $wpdb->update(SRS_CUSTOMERS_TABLE, array(
                            'cst_type'              => $validatedMakeReservationPostData['sex'],
                            'cst_last_name'         => $validatedMakeReservationPostData['lastname'],
                            'cst_first_name'        => $validatedMakeReservationPostData['firstname'],
                            'cst_mobile_phone'      => $validatedMakeReservationPostData['phone'],
                            'cst_email'             => $validatedMakeReservationPostData['email'],
                            'cst_house'             => $validatedMakeReservationPostData['house'],
                            'cst_address_zip'       => $validatedMakeReservationPostData['zip'],
                            'cst_address_town'      => $validatedMakeReservationPostData['town'],
                            'cst_address_street'    => $validatedMakeReservationPostData['street'],
                            'cst_address_city'      => $validatedMakeReservationPostData['city']
                        ), array(
                            'cst_id'                => $validatedMakeReservationPostData['id']
                        ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        )
                    );

                    $reservation_updated = $wpdb->update(SRS_RESERVATIONS_TABLE, array(
                            'res_date'                  => $validatedMakeReservationPostData['date'],
                            'res_time'                  => $validatedMakeReservationPostData['time'],
                            'res_stay_duration'         => $validatedMakeReservationPostData['duration'],
                            'res_no_guests'             => $validatedMakeReservationPostData['no-people'],
                            'res_comment'               => $validatedMakeReservationPostData['comments'],
                            'res_source'                => $validatedMakeReservationPostData['type'],
                            'res_assigned_table'        => '',
                            'res_contraction_usr_id'    => $validatedMakeReservationPostData['employee-code'],
                             'res_status'                => $reservation_status
                        ), array(
                            'res_id'                    => $validatedMakeReservationPostData['id']
                        ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                        )
                    );

                    if ($reservation_updated != false) {
//                        echo "Reservation Updated Successfully!";
                        srs_reservations::save_action_message('Reservation Updated Successfully!');
                    }
                }else {
                    $wpdb->insert(SRS_CUSTOMERS_TABLE, array(
                            'cst_type' => $validatedMakeReservationPostData['sex'],
                            'cst_last_name' => $validatedMakeReservationPostData['lastname'],
                            'cst_first_name' => $validatedMakeReservationPostData['firstname'],
                            'cst_mobile_phone' => $validatedMakeReservationPostData['phone'],
                            'cst_email' => $validatedMakeReservationPostData['email'],
                            'cst_house' => $validatedMakeReservationPostData['house'],
                            'cst_address_zip' => $validatedMakeReservationPostData['zip'],
                            'cst_address_town' => $validatedMakeReservationPostData['town'],
                            'cst_address_street' => $validatedMakeReservationPostData['street'],
                            'cst_address_city' => $validatedMakeReservationPostData['city']
                        ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        )
                    );
                    $customer_id = $wpdb->insert_id;

                    $reservation_created = $wpdb->insert(SRS_RESERVATIONS_TABLE, array(
                            'res_date' => $validatedMakeReservationPostData['date'],
                            'res_time' => $validatedMakeReservationPostData['time'],
                            'res_stay_duration' => $validatedMakeReservationPostData['duration'],
                            'res_no_guests' => $validatedMakeReservationPostData['no-people'],
                            'res_comment' => $validatedMakeReservationPostData['comments'],
                            'res_source' => $validatedMakeReservationPostData['type'],
                            'res_assigned_table' => '',
                            'res_contraction_usr_id' => $validatedMakeReservationPostData['employee-code'],
                            'res_status'                => $reservation_status,
                            'res_customer_id' => $customer_id
                        ), array(
                            '%s',
                            '%s',
                            '%s',
                            '%d',
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                        )
                    );
                    $reservation_id = $wpdb->insert_id;

                    if ($reservation_created) {
//                        echo "Reservation Created Successfully!";
                        srs_reservations::save_action_message('Reservation Created Successfully!');
                        // For frontend reservation form
                        unset($_SESSION['make-reservation']);
                        return 'success';
                    }else{
//                        echo 'Reservation Not Created Successfully!' . $reservation_created;
                        srs_reservations::save_action_message('Reservation Not Created Successfully!');
                        return false;
                    }

                    // Returns true if reservation is created successfully

                }
            }
        }
    }

    /**
     * @param $postData
     * @return array
     * Validate Input for the reservation creation forms
     */
    public static function srs_input_validate($postData){
        $errorMessages = array();
        foreach($postData as $field=>$value) {
            $value = sanitize_text_field($value);

            // Check to see if the required fields have been filled
            if ( in_array( $field, array( 'lastname', 'phone' ) ) ){
                if( $value == "" ){
                    $errorMessages[$field] = 'This field is required! ';
                }
            }


            // Email validation
            if ($field == 'phone') {
                if (preg_match('/[^\-0-9\s?]/i', $value)) {
                    if( $errorMessages[$field] == '' )
                        $errorMessages[$field] = 'Only Digits, Hyphens and Spaces allowed!';
                }
                // Email validation
            }elseif ($field == 'email') {
                if( $value != '' ){
                    if (is_email($value)) {
                        $value = sanitize_email($value);
                    }
                    else {
                    //  if( $errorMessages['email'] == '' )
                        $errorMessages['email'] = 'Invalid Email address!';
                    }
                }
                // Check to see for characters that are not allowed
            } elseif ($field == 'comments') {
                if (preg_match('/[^a-z_\-0-9\s\.\,]/i', $value)) {
                    if( $errorMessages[$field] == '' )
                        $errorMessages[$field] = 'Only Alphabets, Digits, Hyphens and Underscores allowed!';
                }
            } elseif ($field != 'time' && $field != 'duration') {
                if (preg_match('/[^a-z_\-0-9\s]/i', $value)) {
                    if( $errorMessages[$field] == '' )
                    $errorMessages[$field] = 'Only Alphabets, Digits, Hyphens and Underscores allowed!';
                }
            }
        }

        return $validatePostData = array( 'postData' => $postData, 'errorMessages' => $errorMessages);
    }

    /**
     * @param $date
     * @return array - List of reservations for the selected date range
     */
    public static function get_reservations_by_date( $date, $status='' )
    {
        global $srs_reservations;
        if (!isset($date) || $date == '') {
            $date = $srs_reservations->selectedDate();
        }

        global $wpdb;
        $customers_table = SRS_CUSTOMERS_TABLE;
        $reservations_table = SRS_RESERVATIONS_TABLE;

        if ( !session_id() ) {
            session_start();
        }

        if ($status == ''){
            $status = "'pending', 'confirmed', 'deleted'";
        }

        // Reset filter
        if( isset($_POST['srs-reset-filter']) ){
            $_SESSION['srsListReservationsFilterFromDate']  = '';
            $_SESSION['srsListReservationsFilterToDate']    = '';
        }

        // Set filter
        if( isset($_POST['srs-set-filter']) ){
            $date_start = $_POST['srsListReservationsFilterFromDate'];
            $_SESSION['srsListReservationsFilterFromDate'] = $date_start;
            $date_end = $_POST['srsListReservationsFilterToDate'];
            $_SESSION['srsListReservationsFilterToDate'] = $date_end;
            $sql    = "select * from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date between '$date_start' and '$date_end' and $reservations_table.res_status in($status) ;";
            $sql2   = "select $reservations_table.res_time, sum($reservations_table.res_no_guests) as total from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date between '$date_start' and '$date_end' and $reservations_table.res_status = 'pending' GROUP  by $reservations_table.res_time;";
            $sql3   = "select $reservations_table.res_time, sum($reservations_table.res_no_guests) as total from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date between '$date_start' and '$date_end' and $reservations_table.res_status = 'confirmed' GROUP  by $reservations_table.res_time;";
        // Use filter if already set
        } elseif( ($_SESSION['srsListReservationsFilterFromDate']!='' && $_SESSION['srsListReservationsFilterToDate']!='') ){
            $date_start = $_SESSION['srsListReservationsFilterFromDate'];
            $date_end = $_SESSION['srsListReservationsFilterToDate'];
            $sql = "select * from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date between '$date_start' and '$date_end' and $reservations_table.res_status in($status);";
        } else {
            $sql    = "select * from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date = '$date' and $reservations_table.res_status in($status); ";
            $sql2   = "select $reservations_table.res_time, sum($reservations_table.res_no_guests) as total from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date = '$date' and $reservations_table.res_status = 'pending' GROUP by $reservations_table.res_time; ";
            $sql3   = "select $reservations_table.res_time, sum($reservations_table.res_no_guests) as total from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date = '$date' and $reservations_table.res_status = 'confirmed' GROUP by $reservations_table.res_time; ";
        }



        srs_reservations::srs_debug($sql, 'List reservation SQL');
        srs_reservations::srs_debug($sql2, 'List reservation graph SQL');
        srs_reservations::$reservation_graph['pending'] = $wpdb->get_results($sql2, ARRAY_A);
        srs_reservations::$reservation_graph['confirmed'] = $wpdb->get_results($sql3, ARRAY_A);
        $reservations = $wpdb->get_results($sql, ARRAY_A);
        srs_reservations::srs_debug($reservations, 'List reservation sql result','a');

        return $reservations;
    }

    /**
     * @param $date
     * @return array - List of reservations for the selected date range
     */
    public static function get_reservations_by_date_count( $date, $status = array('pending', 'confirmed') )
    {
        global $srs_reservations;
        if (!isset($date) || $date == '') {
            $date = $srs_reservations->selectedDate();
        }

        $res_status = '';

        foreach($status as $value){
            $res_status .= "'$value',";
        }

        $res_status = substr($res_status,0,-1);

        global $wpdb;
        $customers_table = SRS_CUSTOMERS_TABLE;
        $reservations_table = SRS_RESERVATIONS_TABLE;

        $sql = "select count(*) as reservation_count from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_date = '$date' and $reservations_table.res_status IN ($res_status);";

        srs_reservations::srs_debug($sql, 'SQL - Total Count reservations for the selected date');
        $reservations_count = $wpdb->get_var($sql);
        srs_reservations::srs_debug($reservations_count, 'Total Count reservations for the selected date', 'a');

        return $reservations_count;
    }

    public static function get_reservation_by_id( $reservation_id ){
        global $wpdb;
        global $srs_reservations;

        $customers_table = SRS_CUSTOMERS_TABLE;
        $reservations_table = SRS_RESERVATIONS_TABLE;

        $sql = "select * from $customers_table join $reservations_table on $customers_table.cst_id = $reservations_table.res_customer_id where $reservations_table.res_id = $reservation_id;";
        srs_reservations::srs_debug($sql, 'Get reservation by id SQL');
        return $reservation_data = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);

    }

    public static function retrieved_reservation_data_to_make_reservation_postData($reservtion_data){
        return array(
            'sex'     =>  $reservtion_data[0]['cst_type'],
            'firstname'     =>  $reservtion_data[0]['cst_first_name'],
            'lastname'      =>  $reservtion_data[0]['cst_last_name'],
            'phone'         =>  $reservtion_data[0]['cst_mobile_phone'],
            'email'         =>  $reservtion_data[0]['cst_email'],
            'house'         =>  $reservtion_data[0]['cst_house'],
            'street'        =>  $reservtion_data[0]['cst_address_street'],
            'town'          =>  $reservtion_data[0]['cst_address_town'],
            'city'          =>  $reservtion_data[0]['cst_address_city'],
            'zip'           =>  $reservtion_data[0]['cst_address_zip'],
            'date'          =>  $reservtion_data[0]['res_date'],
            'time'          =>  substr($reservtion_data[0]['res_time'], 0, 5),
            'no-people'     =>  $reservtion_data[0]['res_no_guests'],
            'type'          =>  $reservtion_data[0]['res_source'],
            'duration'      =>  substr($reservtion_data[0]['res_stay_duration'], 0, 5),
            'employee-code' =>  $reservtion_data[0]['res_contraction_usr_id'],
            'comments'      =>  $reservtion_data[0]['res_comment'],
            'id'            =>  $reservtion_data[0]['res_id'],
            'status'            =>  $reservtion_data[0]['res_status'],
        );
    }

    /*
     * Confirm reservation from the backend
     */
    public static function confirm_reservation($reservation_id){
        global $wpdb;
        $reservations_table = SRS_RESERVATIONS_TABLE;

        return $wpdb->update(
            $reservations_table,
            array('res_status'=>'confirmed'),
            array('res_id'=>$reservation_id)
        );
    }

    /*
     * Delete reservation from the backend
     */
    public static function delete_reservation($reservation_id){
        global $wpdb;
        $reservations_table = SRS_RESERVATIONS_TABLE;

        return $wpdb->update(
            $reservations_table,
            array('res_status'=>'deleted'),
            array('res_id'=>$reservation_id)
        );
    }

    /**
     * Save Reservations Settings
     */
    public static function srs_save_settings($settingsData){
        srs_reservations::srs_debug($settingsData, 'settings data', 'a');
        // Sanitize the input values
        foreach($settingsData as $key=>$value){
            if($key=='working-days'){
                foreach($value as $workingDay){
                    $workingDay = sanitize_text_field($workingDay).'\n';
                }
            }else{
                $value = sanitize_text_field($value).'\n';
            }
        }

        update_option('srs_reservations_settings', $settingsData);

        return $settingsData;
    }

    /**
     * Save Reservations Settings
     */
    public static function srs_get_settings(){
        return get_option('srs_reservations_settings');
    }
}
?>
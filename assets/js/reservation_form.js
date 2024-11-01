/**
 * Created by Dell on 12/28/2014.
 */

var step = 1;
var error = 0;
var errorMessage = '';
var reservation_date, reservation_time, reservation_duration, reservation_guests,
    reservation_firstname, reservation_lastname, reservation_email, reservation_phone, reservation_comments;

function srs_validate_1(){

    var dateReg     = /^[\d]{4}-[\d]{2}-[\d]{1,2}$/;
    var timeReg     = /^[\d]{2}:[\d]{2}$/;
    var durationReg = /^[\d]{2}:[\d]{2}$/;
    var guestReg    = /^[\d]+$/;

    // Clean error messages
    errorMessage = '';

    // Date
    reservation_date = jQuery(".srs-frontend .active-date").attr('data-selected-date');
    if( !dateReg.test( reservation_date ) || reservation_date == ''){
        error = 1;
        errorMessage += '- Date: missing or incorrect format <br>';
    }else{
        var selectedDate = new Date( Date.parse(reservation_date) );
        // console.log(selectedDate);
        if(selectedDate == 'Invalid Date'){
            error = 1;
            errorMessage += '- Date: Invalid date or format <br>';
        }
    }

    // Time
    reservation_time = jQuery(".srs-frontend .make-reservation-time").val();
    console.log(reservation_time);
    if( !timeReg.test( reservation_time ) || reservation_time == ''){
        error = 1;
        errorMessage += '- Time: missing or incorrect format <br>';
    }else{
        var selectedTime = new Date( Date.parse(reservation_date +' '+ reservation_time) );
        console.log(selectedTime);
        if(selectedTime == 'Invalid Date'){
            error = 1;
            errorMessage += '- Time: Invalid time or format <br>';
        }
    }

    // Duration
    reservation_duration = jQuery(".srs-frontend .make-reservation-duration").val();
    console.log(reservation_duration);
    if( !durationReg.test( reservation_duration ) || reservation_duration == ''){
        error = 1;
        errorMessage += '- Duration: missing or incorrect format <br>';
    }else{
        var selectedDuration = new Date( Date.parse(reservation_date +' '+ reservation_duration) );
        console.log(selectedDuration);
        if(selectedDuration == 'Invalid Date'){
            error = 1;
            errorMessage += '- Duration: Invalid time or format <br>';
        }
    }

    // Number of guests
    reservation_guests = jQuery(".srs-frontend .make-reservation-no-people").val();
    console.log(reservation_guests);
    if( !guestReg.test( reservation_guests ) || reservation_guests == ''){
        error = 1;
        errorMessage += '- No. of Guests: missing or invalid <br>';
    }


    if(error==0){
        return true;
    }else{
        console.log(errorMessage);
        return false;
    }
}

function srs_validate_2(){
    var emailReg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    var nameReg = /^[a-zA-Z\d\s]+$/;
    var phoneReg = /^[\d\s]+$/;
    var commentsReg = /^[a-zA-Z\d\s.,]+$/;

    // Clean error messages
    errorMessage = '';
    error = 0;

    // First Name
    reservation_firstname = jQuery(".srs-frontend .make-reservation-firstname").val();
    if( !nameReg.test( reservation_firstname ) || reservation_firstname == '' || reservation_firstname == 'First Name'){
        error = 1;
        errorMessage += '- First Name: missing or incorrect format *Alphabets and Digits only <br>';
    }

    // Last Name
    reservation_lastname = jQuery(".srs-frontend .make-reservation-lastname").val();
    if( !nameReg.test( reservation_lastname ) || reservation_lastname == '' || reservation_lastname == 'Last Name'){
        error = 1;
        errorMessage += '- Last Name: missing or incorrect format *Alphabets and Digits only <br>';
    }

    // Email
    reservation_email = jQuery(".srs-frontend .make-reservation-email").val();
    if( !emailReg.test( reservation_email ) || reservation_email == '' || reservation_email == 'Email'){
        error = 1;
        errorMessage += '- Email: missing or incorrect format <br>';
    }

    // Phone
    reservation_phone = jQuery(".srs-frontend .make-reservation-phone").val();
    if( !phoneReg.test( reservation_phone ) || reservation_phone == '' || reservation_phone == 'Phone'){
        error = 1;
        errorMessage += '- Phone: missing or incorrect format *Digits only <br>';
    }

    // Comments
    reservation_comments = jQuery(".srs-frontend .make-reservation-comments").val();
    if( reservation_comments == 'Comments' || reservation_comments == '' ){
        reservation_comments = '';
        jQuery(".srs-frontend .make-reservation-comments").val('')
    }else if( !commentsReg.test(reservation_comments) ){
        error = 1;
        errorMessage += '- Comments: incorrect format *Alphabets and Digits only<br>';
    }

    if(error==0){
        return true;
    }else{
        console.log(errorMessage);
        return false;
    }
}

jQuery(function(){

    function placeHolder(field_className, emptyValue){
        if(jQuery(field_className).val() == ''){
            jQuery(field_className).val(emptyValue);
            jQuery(field_className).addClass('empty');
        }
    }

    function text_focusinout(field_className, emptyValue){
        jQuery(field_className).focus(function(){
            jQuery(this).removeClass('empty');
            if(jQuery(field_className).val()==emptyValue){
                jQuery(field_className).val('');
            }
        });
        jQuery(field_className).focusout(function(){
            if(jQuery(field_className).val()==''){
                jQuery(field_className).val(emptyValue);
                jQuery(field_className).addClass('empty');
            }
        });
    }


    // Check to see if field empty
    // Apply place holder if empty
    placeHolder(".srs-frontend .make-reservation-firstname", "First Name");
    placeHolder(".srs-frontend .make-reservation-lastname","Last Name");
    placeHolder(".srs-frontend .make-reservation-email",'Email');
    placeHolder(".srs-frontend .make-reservation-phone", 'Phone');
    placeHolder(".srs-frontend .make-reservation-comments", 'Comments');

    // First Name
    text_focusinout(".srs-frontend .make-reservation-firstname", "First Name");

    // Last Name
    text_focusinout(".srs-frontend .make-reservation-lastname", "Last Name");

    // Email
    text_focusinout(".srs-frontend .make-reservation-email", "Email");

    // Phone
    text_focusinout(".srs-frontend .make-reservation-phone", "Phone");

    // Comments
    text_focusinout(".srs-frontend .make-reservation-comments", "Comments");

    // On Form1 Submit
    jQuery("#srs-reservation-1").submit(function () {

        if( srs_validate_1() ){
            return true;
        }
        console.log(reservation_date+'-'+reservation_time);
        jQuery(".srs-frontend .error-messages").html(errorMessage);
        return false;
    });

    // On Form2 Submit
    jQuery("#srs-reservation-2").submit(function () {

        if( srs_validate_2() ){
            return true;
        }
        console.log(reservation_date+'-'+reservation_time);
        jQuery(".srs-frontend .error-messages").html(errorMessage);
        return false;
    });
});
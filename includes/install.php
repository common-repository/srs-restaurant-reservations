<?php

function installNewTables() {
    installReservationTable();
    installCustomerTable();
//    installTableTable();
//    installUsersTable();
    //installReservedTablesTable();
    
//    addUsers();
}



function installReservationTable() {
    global $wpdb;

    // RESERVATION TABLE

    $tableName = $wpdb->prefix . "srs_reservations";

    $sqlCmd = "CREATE TABLE IF NOT EXISTS " . $tableName . "(
	res_id mediumint(9) UNSIGNED AUTO_INCREMENT NOT NULL,
	res_date date,
	res_time time,
	res_stay_duration time NOT NULL,
	res_no_guests smallint(6) UNSIGNED,
	res_time_of_reservation timestamp,
	res_comment text,
	res_status tinytext NOT NULL,
	res_last_status tinytext NOT NULL,
	res_source tinytext NOT NULL,
	res_assigned_table varchar(6),
	res_contraction_usr_id mediumint(5) UNSIGNED NOT NULL,
	res_customer_id mediumint(9) UNSIGNED NOT NULL,
	PRIMARY KEY (res_id)
	)DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlCmd);
}

function installCustomerTable() {
    global $wpdb;

    // CUSTOMER TABLE

    $tableName = $wpdb->prefix . "srs_customers";

    $sqlCmd = "CREATE TABLE IF NOT EXISTS " . $tableName . "(
	cst_id mediumint(9) UNSIGNED AUTO_INCREMENT NOT NULL,
	cst_type text,
	cst_last_name text NOT NULL,
	cst_first_name text,
	cst_house text,
	cst_mobile_phone text NOT NULL,
	cst_email text,
	cst_address_city text,
	cst_address_country text,
	cst_address_zip text,
	cst_address_street text,
	cst_address_town text,
	PRIMARY KEY (cst_id)
	)DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlCmd);
}

function installTableTable() {
    global $wpdb;

    // LIST OF TABLES

    $tableName = $wpdb->prefix . "srs_table";

    $sqlCmd = "CREATE TABLE IF NOT EXISTS " . $tableName . "(
	tbl_id mediumint(9) UNSIGNED AUTO_INCREMENT NOT NULL,
	tbl_number varchar(6),
	tbl_seats smallint(6) UNSIGNED,
	PRIMARY KEY (tbl_id)
	)DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlCmd);
}

function installUsersTable() {
    global $wpdb;

    // LIST OF TABLES

    $tableName = $wpdb->prefix . "srs_users";

    $sqlCmd = "CREATE TABLE IF NOT EXISTS " . $tableName . "(
	usr_id int(11) UNSIGNED AUTO_INCREMENT NOT NULL,
	usr_function text DEFAULT NULL,
	usr_contraction text DEFAULT NULL,
	usr_wpuser_id int(11) DEFAULT NULL,
	PRIMARY KEY (usr_id)
	)DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlCmd);
}
/*
function addUsers() {
    global $wpdb;
    global $blog_id;
    $childSiteUsersTable = $wpdb->prefix . "hans_users";
    $wpUsersTable = $wpdb->base_prefix."users";
    $wpUsersTableMeta = $wpdb->base_prefix."usermeta";
    
	$sqlCmd = "INSERT INTO ". $childSiteUsersTable." (usr_wpuser_id, usr_function) 
	SELECT  " . $wpUsersTableMeta . ".user_id, 1 FROM  " . $wpUsersTableMeta . " 
		WHERE  (" . $wpUsersTableMeta . ".meta_key like 'primary_blog' 
		AND " . $wpUsersTableMeta . ".meta_value like '".$blog_id."' 
		AND ".$wpUsersTableMeta.".user_id
			not in(
			SELECT usr_wpuser_id FROM ".$childSiteUsersTable."
			)
		) 
	or 
		(" . $wpUsersTableMeta . ".meta_key = 'wp_user_level' 
		AND " . $wpUsersTableMeta . ".meta_value = 10
		AND ".$wpUsersTableMeta.".user_id
			not in(
			SELECT usr_wpuser_id FROM ".$childSiteUsersTable."
			)
		)";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlCmd);
}

function installReservedTablesTable() { 
    global $wpdb;

    // LIST OF TABLES

    $tableName = $wpdb->prefix . "hans_reserved_tables_data";

    $sqlCmd = "CREATE TABLE IF NOT EXISTS " . $tableName . "(
	rst_id int UNSIGNED AUTO_INCREMENT NOT NULL,
	rst_table_number int UNSIGNED NOT NULL,
	rst_date date NOT NULL,
	rst_start_time time NOT NULL,
	rst_finish_time time NOT NULL,
	PRIMARY KEY (rst_id)
	)DEFAULT CHARSET=utf8;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlCmd);
}*/

?>

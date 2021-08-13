<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
global $wpdb;
$table_name = $wpdb->prefix . "eol_and_esol_table";
$main_sql_create = "CREATE TABLE $table_name (
          Id int NOT NULL AUTO_INCREMENT,
		  ModelName varchar(255) NOT NULL,
          Date varchar(255) DEFAULT '',
          Manufacturer varchar(255),
          Category varchar(255), 
          ModelNumber varchar(255),
          SupportStatus varchar(255),
          PRIMARY KEY (Id)
		) $charset_collate;";
maybe_create_table($table_name, $main_sql_create);



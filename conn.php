<?php 

$conn = pg_connect("host=localhost dbname=event_management user=postgres password=mycatalist");
if (!$conn) {
    die("Error in connection: " . pg_last_error());
}


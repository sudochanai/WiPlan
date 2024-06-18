<?php 

$conn = pg_connect("host=localhost dbname=event_management user=postgres password=password1212");
if (!$conn) {
    die("Error in connection: " . pg_last_error());
}


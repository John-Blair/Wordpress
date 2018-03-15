<?php
/*
 * Follow me on Twitter: @HertogJanR
 * Please donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4EFPSXA623NZA
 */

$connectionInfo = array( "UID" => "wordpress", "PWD" => "Passw0rd", "Database" => "wordpress" );
$link = sqlsrv_connect( ".", $connectionInfo );
if( $link ) {
     echo "Connection established.<br />";
} else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true ) );
}
$sql = "SELECT table_name FROM information_schema.tables";

$stmt = sqlsrv_query( $link, $sql );
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {
  echo $row['table_name']."<br />";
}

if( $stmt === false ) {
  die( print_r( sqlsrv_errors(), true));
}
?>
<?php
if($_GET['napalmauth'] == "logout"){
	$username = $napalmauth->user_name();
	$napalmlog->add("logged out",1,$username);
}

echo("Publ");
?>

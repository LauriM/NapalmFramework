<?php
$render_admin = true;
$status = $napalmauth->user_auth_status();
$username = $napalmauth->user_name();

$lasthit   = $napalmdata->getdata($username,"lasthit");
$lastlogin = $napalmdata->getdata($username,"lastlogin");
$napalmdata->setdata($username,"lasthit",time());

if($_GET['napalmauth'] == "login"){
	$napalmdata->setdata($username,"lastlogin",time());
	$napalmlog->add("logged in",1,$username);
}

include("priv/preprocess.php");
//########
echo("Private");
?>

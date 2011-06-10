<?php
session_start();

include("../config.php");
include("../napalm/database.php");
include("../napalm/functions.php");
include("../napalm/napalmauth.class.php");
include("../napalm/napalmdata.class.php");
include("../napalm/napalmlog.class.php");
include("../include/debug.class.php");

$napalmauth = new NapalmAuth();
$napalmauth->init();

$napalmdata = new NapalmData();
$napalmlog = new NapalmLog();

if(api_enable == false){
	die("API DISABLED!");
}

$status = $napalmauth->user_process();
$username = $napalmauth->user_name();

if($status !== 1){
	//User is not logged in via cookies/ajax, check for POST/GET authentication
    $status = $napalmauth->api_auth();
	$username = $napalmauth->user_name();
}

if($status == 1){
	$is_admin = $napalmdata->getdata($username,"is_admin");

	if($is_admin == true){
		$auth_ok = true;
		include("engine.php");
	}else{
		echo("autherror");
	}
}else{
    echo("autherror");
}
?>

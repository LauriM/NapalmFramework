<?php
session_start();
include("config.php");
include("napalm/database.php");
include("napalm/napalmauth.class.php");
include("napalm/napalmdata.class.php");
include("napalm/napalmlog.class.php");
include("napalm/functions.php");
include('napalm/recaptchalib.php');

$napalmauth = new NapalmAuth();
$napalmauth->init();

$napalmdata = new NapalmData();
$napalmlog  = new NapalmLog();

$status = $napalmauth->user_process();

switch($status){
    case 0:
        include("publ/index.php");
        break;
    case 1:
        include("priv/index.php");
        break;
}
?>

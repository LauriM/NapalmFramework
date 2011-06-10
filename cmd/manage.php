#!/usr/bin/php

<?php
include("../config.php");
include("../napalm/database.php");
include("../napalm/functions.php");
include("../napalm/napalmauth.class.php");
include("../napalm/napalmdata.class.php");
include("../napalm/napalmlog.class.php");

$napalmauth = new NapalmAuth();
$napalmauth->init();

$napalmdata = new NapalmData();
$napalmlog  = new NapalmLog();

echo "#########################\n";
echo " NapalmFramework console \n";
echo "#########################\n";

$quit = false;

while($quit == false){
	$input = ReadStdin("Napalm> "); 

	switch($input){
		case "":
			break;
		case "help":
			echo("Avaivable commands:\n");
			echo("help    - Show this help\n");
			echo("users   - list users\n");
			echo("adduser - Add new user to the system\n");
            echo("log     - Start log feed\n");
			break;
        case "adduser":
            $name = ReadStdin("Name> ");
            $password = ReadStdin("Password> ");
            $result = $napalmauth->add_user($name,$password,true);
            
            if($result == 1){
                echo("User \"$name\" added!\n");    
            }else{
                echo("Error code $result\n");
            }

            break;
		case "log":
			echo("Starting log feed...");
				while($quit == false){
					$time = time();
                    $data = "TODO: add mysql query";
					echo($data);
					sleep(1);
				}
			break;
		case "exit":
			echo("Closing...\n");
			die();
			break;
		default:
			echo("Invalid input!\n");
	}
}

/* By James Zhu http://fi.php.net/manual/en/features.commandline.io-streams.php */
/* Small modifications */
function ReadStdin($prompt, $default = '') {
	while(!isset($input) ) {
		echo $prompt;
		$input = strtolower(trim(fgets(STDIN)));
		if(empty($input) && !empty($default)) {
			$input = $default;
		}
	}
	return $input;
} 
?>
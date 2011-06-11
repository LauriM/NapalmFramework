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
            echo("rmuser  - Remove user from the system\n");
            echo("dataget - Get variable\n");
            echo("log     - Start log feed\n");
			break;
        case "dataget":
            $owner    = ReadStdin("Owner> ");
            $variable = ReadStdin("Variable> ");

            $data = $napalmdata->getdata($owner,$variable);

            echo("$data\n");
            break;
        case "dataset":
            $owner    = ReadStdin("Owner> ");
            $variable = ReadStdin("Variable> ");
            $value    = ReadStdin("Value> ");

            $napalmdata->setdata($owner,$variable,$value);
            echo("Done!\n");
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
        case "rmuser":
            $name = ReadStdin("Name> ");
            $statement = $db->prepare("DELETE FROM users WHERE username = ?");
            $statement->bindParam(1,$name);
            $statement->execute();

            echo("Delete query done!\n");
            break;
        case "users":
            $statement = $db->prepare("SELECT username FROM users");
            $statement->execute();
            echo("Listing users....\n");
            
            while($row = $statement->fetch()){
                $name = $row['username'];

                echo("$name\n");
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

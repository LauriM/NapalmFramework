<?php
class NapalmAuth{
    private $user_name = "";
    private $user_pass = "";
    private $user_auth_done = 0;
	private $recaptha_enable;
	private $recaptha_public;
	private $recaptha_private;

    public function init(){
        $this->user_name = $_SESSION['username'];
        $this->user_pass = $_SESSION['password'];

		$this->recaptha_enable  = false;
		$this->recaptha_public  = "";
		$this->recaptha_private = "";
    }

    public function test(){
        echo("NapalmAuth Working!");
    }

    public function auth_status(){
        return($this->user_auth_done);
    }

	public function recaptha_enable($public,$private){
		$this->recaptha_enable  = true;
		$this->recaptha_public  = $public;
		$this->recaptha_private = $private;
	}

    public function api_auth(){
        global $db;
        $user_name = $_POST['username'];
        $user_pass = passwordhash($_POST['password']);

        if($user_name == ""){
            $user_name = $_GET['username'];
            $user_pass = passwordhash($_GET['password']);
        }

        //Check that user is in the database
        $statement = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $statement->bindParam(1,$this->user_name,PDO::PARAM_INT);
        $statement->bindParam(2,$this->user_pass,PDO::PARAM_INT);
        $statement->execute();

        if($statement->rowCount() == 1){
            $user_auth_done  = 1;
			$this->user_name = $user_name;
        }

        return $user_auth_done;
    }

    public function user_process(){
        global $db;
		//Check if user logs in/registers new account via the public pages
        if($_GET['napalmauth'] == "login"){
            $this->user_name = $_POST['username'];
            $this->user_pass = passwordhash($_POST['password']);

            $_SESSION['username'] = $this->user_name;
            $_SESSION['password'] = $this->user_pass;
        }

        //Check that user is in the database
        $statement = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $statement->bindParam(1,$this->user_name,PDO::PARAM_INT);
        $statement->bindParam(2,$this->user_pass,PDO::PARAM_INT);
        $statement->execute();

        if($statement->rowCount() == 1){
            $this->user_auth_done = 1;
        }

        //last so it wont "relog" user in after logout with the php vars
        if($_GET['napalmauth'] == "logout"){
            session_destroy();
            $this->user_auth_done= 0;
        }

        return $this->user_auth_done;
    }

	public function change_password($username,$new){
        global $db;
		//TODO: check that user exits
		//TODO: Add check for old password
		$new = passwordhash($new);

        $statement->prepare("UPDATE users SET password = ? WHERE username = ?");
        $statement->bindParam(1,$new);
        $statement->bindParam(2,$username);
        $statement->execute();
		
		return 1;
	}

    public function user_auth_status(){
        return $this->user_auth_done;
    }

    public function user_name(){
        return $this->user_name;
    }

	public function add_user($username,$password,$skip_captha = false){
        global $db;
		$captha_ok = false;

		if($this->recaptha_enable == true){
			$resp = recaptcha_check_answer ($this->recaptha_private,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);

			if($resp->is_isvalid == true){
				$captha_ok = true;
			}
		}else{
			$captha_ok = true;
		}

		if($skip_captha = true){
			$captha_ok = true;
		}

		if($captha_ok == false){
			return 6;
		}else{
            $statement = $db->prepare("SELECT * FROM users WHERE username = ?");
            $statement->bindParam(1,$username);
            $statement->execute();

			if(preg_match('/^[a-zA-Z0-9]+$/',$username) == false){
				$invalid_name = true;
			}

			if(strlen($username) < 3 OR strlen($username) > 21){
				$invalid_name = true;
			}

			if($username == "system"){
				$invalid_name = true;
			}

			if(strlen($password) < 4){
				$invalid_password = true;
			}

			if($statement->rowCount() == 0 AND $username <> "" AND $invalid_password == false AND $invalid_name == false){
				$hash = passwordhash($password);//TODO: move away from functions?

                $statement = $db->prepare("INSERT INTO users(username,password) VALUES(?,?)");
                $statement->bindParam(1,$username);
                $statement->bindParam(2,$hash);
                $statement->execute();

				$status = 1;
			}else{
				$status = 2;

				if($invalid_name == true){
					$status = 3;
				}

				if($statement->rowCount() > 0){
					$status = 4;
				}

				if($invalid_password == true){
					$status = 5;
				}
			}
		}
		return $status;
		//0 = unkown
		//1 = done
		//2 = common error(?!?)
		//3 = Invalid name
		//4 = Already exist
		//5 = Invalid password
		//6 = Captha error
	}

    public function show_login(){
        echo("<h3>Kirjautuminen!</h3>");
        echo("<form action='index.php?napalmauth=login' method='POST'>");
        echo("K&auml;ytt&auml;j&auml;nimi: <input type='text' name='username'><br/>");
        echo("Salasana: <input type='password' name='password'><br/>");
        echo("<input type='submit' value='Kirjaudu'>");
        echo("</form>");
    }


    public function show_logout(){
        echo("<a href='index.php?napalmauth=logout'>Kirjaudu ulos</a>");
    }

    public function debug(){
        echo("<hr/>");
        echo("user_name: $this->user_name<br/>");
        echo("user_pass: $this->user_pass<br/>");
        echo("user_auth_done: $this->user_auth_done<br/>");
        echo("ses_user_name: ".$_SESSION['username']."<br/>");
        echo("ses_user_pass: ".$_SESSION['password']."<br/>");
        echo("<hr/>");
    }

};
?>

<?php
//Level
// 0 = Debug
// 1 = Message 
// 2 = Notify
// 3 = Error
// 4 = Auth problem
// 5 = Critical
class NapalmLog{
    public function add($line,$level,$username = "NULL"){
        global $db;
		$time  = time();
		$ip    = $_SERVER['REMOTE_ADDR'];

        $statement = $db->prepare("INSERT INTO `log`(msg,lvl,unixtime,owner,ip) VALUES(?,?,?,?,?)");
        $statement->bindParam(1,$line);
        $statement->bindParam(2,$level);
        $statement->bindParam(3,$time);
        $statement->bindParam(4,$username);
        $statement->bindParam(5,$ip);
        $statement->execute();
    }
}

?>

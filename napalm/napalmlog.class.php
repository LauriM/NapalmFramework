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
        $line  = secure($line);
        $level = secure($level);
		$time  = time();
		$ip    = $_SERVER['REMOTE_ADDR'];
		query("INSERT INTO `log`(msg,lvl,unixtime,owner,ip) VALUES('$line','$level','$time','$username','$ip');");
    }
}

?>

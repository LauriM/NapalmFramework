<?php
class NapalmData{
    public function getdata($owner,$variable){
		global $datacache_owner;
		global $datacache_variable;
		global $datacache_value;
		global $cache_hits;

		//check cache
		for($i = 0;$i < sizeof($datacache_owner);$i++){
			if($datacache_owner[$i] == $owner && $datacache_variable[$i] == $variable){
				$cache_hits = $cache_hits + 1;
				return($datacache_value[$i]);
			}
		}

        $owner = secure($owner);
        $variable = secure($variable);
        $result = query("SELECT value FROM data WHERE owner = '$owner' AND variable = '$variable'");
        $count = mysql_num_rows($result);

        if($count == 1){
			$size = sizeof($datacache_owner);

			$datacache_owner[$size]    = $owner;
			$datacache_variable[$size] = $variable;
			$datacache_value[$size]    = mysql_result($result,0,"value");
            return(mysql_result($result,0,"value"));
        }else{
            return(0);
        }
    }

    public function setdata($owner,$variable,$value){
		global $datacache_owner;
		global $datacache_variable;
		global $datacache_value;

        $owner = secure($owner);
        $variable = secure($variable);
        $value = secure($value);

        $result = query("SELECT value FROM data WHERE owner = '$owner' AND variable = '$variable'");
        $count = mysql_num_rows($result);

        if($count == 1){
            query("UPDATE data SET value = '$value' WHERE owner = '$owner' AND variable = '$variable'");

			//Update cache
			for($i = 0;$i < sizeof($datacache_owner);$i++){
				if($datacache_owner[$i] == $owner && $datacache_variable[$i] == $variable){
					$datacache_value[$i] = $value;
				}
			}
        }else{
            query("INSERT INTO data(owner,variable,value) VALUES('$owner','$variable','$value')");

			//Add new value to cache
			$size = sizeof($datacache_owner) + 1;

			$datacache_owner[$size]    = $owner;
			$datacache_variable[$size] = $variable;
			$datacache_value[$size]    = mysql_result($result,0,"value");
        }

    }

	public function cache_hits(){
		global $cache_hits;
		echo("<p>Napalmdata cache hits: $cache_hits</p>");
	}
}

?>

<?php
class NapalmData{
    public function getdata($owner,$variable){
        global $db;
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

        $statement = $db->prepare("SELECT value FROM data WHERE owner = ? AND variable = ?");
        $statement->bindParam(1,$owner);
        $statement->bindParam(2,$variable);
        $statement->execute();

        $count = $statement->rowCount($result);

        if($count == 1){
			$size = sizeof($datacache_owner);

            $value = $statement->fetchColumn();

			$datacache_owner[$size]    = $owner;
			$datacache_variable[$size] = $variable;
			$datacache_value[$size]    = $value; 

            return($value);
        }else{
            return(0);
        }
    }

    public function setdata($owner,$variable,$value){
        global $db;
		global $datacache_owner;
		global $datacache_variable;
		global $datacache_value;

        $statement = $db->prepare("SELECT value FROM data WHERE owner = ? AND variable = ?");
        $statement->bindParam(1,$owner);
        $statement->bindParam(2,$variable);
        $statement->execute();

        $count = $statement->rowCount();

        if($count == 1){
            $statement = $db->prepare("UPDATE data SET value = ? WHERE owner = ? AND variable = ?");
            $statement->bindParam(1,$value);
            $statement->bindParam(2,$owner);
            $statement->bindParam(3,$variable);
            $statement->execute();

			//Update cache
			for($i = 0;$i < sizeof($datacache_owner);$i++){
				if($datacache_owner[$i] == $owner && $datacache_variable[$i] == $variable){
					$datacache_value[$i] = $value;
				}
			}
        }else{
            $statement = $db->prepare("INSERT INTO data(owner,variable,value) VALUES(?,?,?)");
            $statement->bindParam(1,$owner);
            $statement->bindParam(2,$variable);
            $statement->bindParam(3,$value);
            $statement->execute();

			//Add new value to cache
			$size = sizeof($datacache_owner) + 1;

			$datacache_owner[$size]    = $owner;
			$datacache_variable[$size] = $variable;
			$datacache_value[$size]    = $value; 
        }

    }

	public function cache_hits(){
		global $cache_hits;
		echo("<p>Napalmdata cache hits: $cache_hits</p>");
	}
}

?>

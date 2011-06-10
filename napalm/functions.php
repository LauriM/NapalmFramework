<?php 
function passwordhash($string){
	global $napalm_seed;
	return hash("sha1","$string$napalm_seed");
}

?>

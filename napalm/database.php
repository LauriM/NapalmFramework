<?php
$db = new PDO(NAPALM_DB_ENGINE.":host=".NAPALM_DB_SERVER.";dbname=".NAPALM_DB_DATABASE,NAPALM_DB_USERNAME,NAPALM_DB_PASSWORD);
?>
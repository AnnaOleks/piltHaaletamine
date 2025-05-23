<?php
$kasutaja="___________";
$parool="___________";
$andmebaas="___________";
$srverinimi="___________";

$yhendus=new mysqli($srverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset("utf8");
?>

<?php
/*$uid = ($config->get('t')) ? $config->get('t') : $config->u;
$team = ($config->get('t')) ? true : false;
*/
$teamID = (isset($_POST['t'])) ? $_POST['t'] : $config->u;
//$team = (isset($_POST['t'])) ? true : false;
$contest->joinTest($teamID);

<?php
 session_start();
require_once('../Controller/recipiesController.php');
$control=new RecipiesController();
$control->showProfile();

?>
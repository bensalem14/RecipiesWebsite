<?php
session_start();
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
$x->addNews();
?>
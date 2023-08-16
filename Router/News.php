<?php
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
$x->showNews($_GET['requestedNews']);
?>
<?php
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
$x->showFetes($_GET['requestedFetes']);
?>
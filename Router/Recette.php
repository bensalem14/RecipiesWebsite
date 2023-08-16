<?php
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
$x->showRecipie($_GET['requestedRecipie']);
?>
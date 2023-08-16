<?php
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if(!isset($_GET['requestedSaison']))
$x->showSaisons('Hiver');
else $x->showSaisons($_GET['requestedSaison']);
?>
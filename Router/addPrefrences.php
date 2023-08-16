<?php
session_start();
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if($x->searchPrefrence($_GET['requestedRecipie'],$_SESSION['user']['userid'])){
    $x->deletePrefrence($_GET['requestedRecipie'],$_SESSION['user']['userid']);
}else {
$x->addPrefrence($_GET['requestedRecipie'],$_SESSION['user']['userid']);
}
header('location:Recette.php?requestedRecipie='.$_GET['requestedRecipie'].'');

?>
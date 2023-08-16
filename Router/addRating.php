<?php
session_start();
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if($x->searchRating($_GET['recipie'],$_GET['user'])){
    $x->deleteRating($_GET['recipie'],$_GET['user']);
    $x->addRating($_GET['recipie'],$_GET['user'],$_GET['rating']);
    $x->changeRating($_GET['recipie']);
}else {
$x->addRating($_GET['recipie'],$_GET['user'],$_GET['rating']);
$x->changeRating($_GET['recipie']);
}
header('location:Recette.php?requestedRecipie='.$_GET['recipie'].'');



?>
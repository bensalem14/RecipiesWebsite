<?php
 session_start();
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if($_GET['op']=='valider'){
    $x->valider($_GET['titre']);
    header('location:GestionRecetteTrie.php');
}else if($_GET['op']=='supprimer'){
    $x->deleteRecette($_GET['titre']);
    $x->insert("DELETE FROM `composition` Where titre='".$_GET['titre']."'");
    $x->insert("DELETE FROM `etape` Where titre='".$_GET['titre']."'");
    header('location:GestionRecetteTrie.php');
}else if($_GET['op']=='supprimerIngredient'){
    $x->insert("DELETE FROM `ingredient` Where nom='".$_GET['titre']."'");
    $x->insert("DELETE FROM `composition` Where nom='".$_GET['titre']."'");
    header('location:Nutrition.php');
}else if($_GET['op']=='Healthy'){
    $x->insert("UPDATE ingredient SET healthy = 1 WHERE nom='".$_GET['titre']."';");
    header('location:Nutrition.php');
}

?>
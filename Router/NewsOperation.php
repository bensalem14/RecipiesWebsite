<?php
 session_start();
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
$titre=$_GET['titre'];
if($_GET['op']=='display'){
    $x->do('UPDATE recette SET display = 1 WHERE titre="'.$titre.'";');
    $x->do('UPDATE news SET display = 1 WHERE titre="'.$titre.'";');
}else if($_GET['op']=='undisplay'){
   $x->do('UPDATE recette SET display = 0 WHERE titre="'.$titre.'";');
    $x->do('UPDATE news SET display = 0 WHERE titre="'.$titre.'";');
}
header('location:AllNews.php');
?>
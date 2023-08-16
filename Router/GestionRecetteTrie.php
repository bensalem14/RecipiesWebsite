<?php
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if(!isset($_GET['requestedCategorie'])){
   $x->showCategories('all','none','asc'); 
}
else $x->showCategories("all",$_GET['requestedCriteria'],'asc');
?>
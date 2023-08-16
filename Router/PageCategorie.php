<?php
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if(!isset($_GET['requestedCategorie'])){
   $x->showCategories('plat','none','asc'); 
}
else $x->showCategories($_GET['requestedCategorie'],$_GET['requestedCriteria'],'asc');
?>
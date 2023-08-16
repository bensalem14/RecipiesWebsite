<?php
require_once('../Controller/recipiesController.php');
$control=new RecipiesController(); 
if(empty($_POST['IngredientChoice']))
$control->showIdee([]);
else $control->showIdee($_POST['IngredientChoice']);

?>
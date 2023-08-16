<?php
require_once('../Controller/recipiesController.php');
$control=new RecipiesController(); 
if(!isset($_POST['maxcalorie'])){
   
$control->showHealthyRecipies('inf');
}
else { $control->showHealthyRecipies($_POST['maxcalorie']);}

?>
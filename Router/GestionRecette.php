<?php
require_once('../Controller/recipiesController.php');
$control=new RecipiesController();
if(empty($_POST['flexRadioDefault'])){
    $saison="";
    
}else $saison=$_POST['flexRadioDefault'];

if(!isset($_POST['requestedCategorie'])){
   $x="plat";
}
else $x=$_POST['requestedCategorie'];
//echo $control->GenerateFilter($x,[$_POST['mintcuisson'],$_POST['maxtcuisson'],$_POST['mintpreparation'],$_POST['maxtpreparation'],$_POST['minttotal'],$_POST['maxttotal'],$_POST['minnotation'],$_POST['maxnotation'],$_POST['mincalorie'],$_POST['maxcalorie'],$saison]);


$control->showFilterd("all",[$_POST['mintcuisson'],$_POST['maxtcuisson'],$_POST['mintpreparation'],$_POST['maxtpreparation'],$_POST['minttotal'],$_POST['maxttotal'],$_POST['minnotation'],$_POST['maxnotation'],$_POST['mincalorie'],$_POST['maxcalorie'],$saison]);

?>
<?php
 session_start();
require_once('../Controller/recipiesController.php');
$x=new RecipiesController(); 
if($_POST['location']==='login'){
    if($x->AuthentifyAdmin($_POST['mail'],$_POST['password'])){
       $_SESSION['admin'] = "admin"; 
        header('location:AdminHome.php'); 
    }
    else if($x->AuthentifyUser($_POST['mail'],$_POST['password'])){
       
        $_SESSION['user'] = $x->getUser($_POST['mail'],$_POST['password']); 
        if($_SESSION['user']['valider'])
        header('location:index.php');
        else header('location:Login.php'); 
    }else{
      header('location:Login.php'); 
    }
}else if($_POST['location']==='signup'){
    if($x->AuthentifyUser($_POST['mail'],$_POST['password'])){
        header('location:Login.php');
    }else{
        $x->AddUser($_POST['nom'],$_POST['prenom'],$_POST['mail'],$_POST['sex'],$_POST['dateN'],$_POST['password']);
        $_SESSION['user'] = $x->getUser($_POST['mail'],$_POST['password']); 
        header('location:index.php');
    }
}else if($_POST['location']=='ajouterrecette'){
//$array= [$_POST['titre'],$_POST['categorie'],0,$_SESSION['user']['userid'],$_POST['ddescription'],$_POST['description'],$_POST['tcuisson'],$_POST['tpreparation'],$_POST['trepos'],$_POST['deficulte'],$_POST['fetes'],$_POST['ingredints'],$_POST['quantite'],$_POST['unite'],$_POST['healthy'],$_POST['etapes'],$_POST['nbcalorie']];
if(isset($_SESSION['admin'])) $valider=1; else $valider=0;
if(isset($_SESSION['user'])) $id=$_SESSION['user']['userid']; else $id=1;
if(isset($_POST['fetes']))
$fetes=join(",",$_POST['fetes']); else $fetes='';

$q="INSERT into recette (`titre`, `categorie`,`image`, `valider`, `userid`,  `ddescription`, `description`, `tpreparation`, `trepos`, `tcuisson`, `notation`, `defficulte`,  `nbCalorie`,`fetes`) VALUES ('".$_POST['titre']."','".$_POST['categorie']."','".$_POST['image']."',".$valider.",".$id.",'".$_POST['ddescription']."','".$_POST['description']."','".$_POST['tpreparation']."','".$_POST['trepos']."','".$_POST['tcuisson']."',".$_POST['notation'].",'".$_POST['deficulte']."','".$_POST['nbcalorie']."',('".$fetes."'))";
$x->insert($q); 
$ingredients=$_POST['ingredints'];

$etape=$_POST['etapes'];

for($t=0;$t<count($ingredients);$t++){
    if(isset($_POST['healthy'][$t])) $h=1; else $h=0;
    if(isset($_POST['unite'][$t])) $u=$_POST['unite'][$t]; else $u='';
    $q="INSERT into ingredient (`nom`,`healthy`) VALUES ('".$ingredients[$t]."',".$h.")";
    $x->insert($q);
    $q="INSERT into composition (`nom`,`titre`,`quantite`,`unite`) VALUES ('".$ingredients[$t]."','".$_POST['titre']."','".$_POST['quantite'][$t]."','".$u."')";
    $x->insert($q);  
}
for($t=0;$t<count($etape);$t++){
    $q="INSERT into etape (`ordre`,`titreRecette`,`description`) VALUES (".($t+1).",'".$_POST['titre']."','".$_POST['etapes'][$t]."')";
    $x->insert($q); 
}
header('location:AjouterRecette.php');
}else if($_POST['location']=='ajouternews'){
//$array= [$_POST['titre'],$_POST['categorie'],0,$_SESSION['user']['userid'],$_POST['ddescription'],$_POST['description'],$_POST['tcuisson'],$_POST['tpreparation'],$_POST['trepos'],$_POST['deficulte'],$_POST['fetes'],$_POST['ingredints'],$_POST['quantite'],$_POST['unite'],$_POST['healthy'],$_POST['etapes'],$_POST['nbcalorie']];
if($x->searchRecette($_POST['titre'])){
    $x->deleteRecette($_POST['titre']);
}
$q="INSERT into news (`titre`, `image`, `video`, `description`, `paragraph`,`publishdate`) VALUES ('".$_POST['titre']."','".$_POST['image']."','".$_POST['video']."','".$_POST['description']."','".$_POST['paragraph']."',curdate())";
$x->insert($q);
header('location:AddNews.php');
}else if($_POST['location']=='ajouteringredient'){
    if($x->search("SELECT * FROM ingredient where nom='".$_POST['nom']."'")){
      $q="DELETE  FROM ingredient WHERE nom='".$_POST['nom']."' ";
      $x->insert($q);  
    }
    $saison=join(",",$_POST['Saison']);
    $q="INSERT into ingredient (`nom`, `healthy`, `saison`, `calorie`, `protein`,`carb`,`fibre`,`leau`,`sucre`,`sodium`,`Fer`,`Potassium`,`Phosphore`,`Magnesium`,`Zinc`) VALUES ('".$_POST['nom']."',0,(''),".$_POST['Calorie'].",".$_POST['Protein'].",".$_POST['Carb'].",".$_POST['Leau'].",".$_POST['Sucre'].",".$_POST['Sodium'].",".$_POST['Fer'].",".$_POST['Potassium'].",".$_POST['Phosphore'].",".$_POST['Magnesium'].",".$_POST['Zinc'].")";
$x->insert($q);

header('location:AddNutrition.php');
}




?>
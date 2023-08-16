<?php
require_once('../Modele/recipiesModele.php');
require_once('../View/clientview.php');

class RecipiesController{
    public function getAllRecipies(){
        $modele=new RecipiesModele();
    return $modele->getAllRecipies(); 
    }
    public function getRecipies($categorie){
        $modele=new RecipiesModele();
    return $modele->getRecipies($categorie);     
    } 
     public function getAllIngredients(){
         $modele=new RecipiesModele();
    return $modele->getAllIngredients();
    }
    public function getSingleRecipie($title){
        $modele=new RecipiesModele();
    return $modele->getSingleRecipie($title);
    }
    public function getRecipieTotalTime($title){
        $modele=new RecipiesModele();
    return $modele->getRecipieTotalTime($title);
    }
    public function getSingleNews($title){
        $modele=new RecipiesModele();
        return $modele->getSingleNews($title); 
    }
     public function getRecipieSteps($title){
        $modele=new RecipiesModele();
    return $modele->getRecipieSteps($title);
    }
    public function getRecipieIngredients($title){
        $modele=new RecipiesModele();
    return $modele->getRecipieIngredients($title);
    }
    public function getRecipieIngredientsInfo($title){
        $modele=new RecipiesModele();
    return $modele->getRecipieIngredientsInfo($title);
    }
   
    function getCurrentSaison(){
        $d=date("Y/m/d");
        switch(strval($d[5].$d[6])){
            case 12:case 1:case 2: return 'Hiver';
            break;
            case 3:case 4:case 5: return 'Printemps';
            break;
            case 6:case 7:case 8: return 'Ete';
            break;
            case 9:case 10:case 11: return 'Automne';
            break;
        }
    }
    public function getFetesRecipies($fete){
    $modele=new RecipiesModele();
    $arr=$modele->getFetesRecipies();
    $result=[];
    if($fete=="tous")
    return $arr;
    else {
    foreach($arr as $x)
    {
        if(strpos($x['fetes'],$fete)!== false)
        array_push($result,$x);
        
    }
    return $result;
}}
public function getSaisonRecipies($saison){
    $model=new RecipiesModele();
    return $model->getSaisonRecipies($saison);
}
  public function addPrefrence($titre,$id){
     $model=new RecipiesModele();
    $model->addPrefrence($titre,$id);
  }
  public function searchPrefrence($titre,$id){
     $model=new RecipiesModele();
    return $model->searchPrefrence($titre,$id);
  }
  public function deleteRecette($titre){
     $model=new RecipiesModele();
    return $model->deleteRecette($titre);
  }
  public function deletePrefrence($titre,$id){
     $model=new RecipiesModele();
    $model->deletePrefrence($titre,$id);
  }
  public function addRating($titre,$id,$rate){
     $model=new RecipiesModele();
    $model->addRating($titre,$id,$rate);
  }
  public function valider($titre){
     $model=new RecipiesModele();
    $model->valider($titre);
  }
  public function addRecette($titre, $categorie, $valider, $userid, $ddescription, $description, $tpreparation, $trepos, $tcuisson, $notation,$defficulte, $fetes , $nbCalorie){
     $model=new RecipiesModele();
    return $model->addRecette($titre, $categorie, $valider, $userid, $ddescription, $description, $tpreparation, $trepos, $tcuisson, $notation,$defficulte, $fetes , $nbCalorie);
}
  public function searchRating($titre,$id){
     $model=new RecipiesModele();
    return $model->searchRating($titre,$id);
  }
  public function changeRating($titre){
    $users=$this->do("SELECT * FROM rating where titre='".$titre."'");
    echo count($users);
    $sum=0;
    foreach($users as $u){
        $sum+=$u['rating'];
    }
    $sum/=count($users);
    $users=$this->insert("UPDATE  `recette` set notation=".$sum." WHERE titre='".$titre."' ");
  }
  public function search($q){
     $model=new RecipiesModele();
    $model->search($q);
    }
  public function deleteRating($titre,$id){
     $model=new RecipiesModele();
    $model->deleteRating($titre,$id);
  }
   public function getAllNews(){
    $modele=new RecipiesModele();
    return $modele->getAllNews();
   }
   public function getSortedRecipies($categorie,$criteria,$asc){
    $modele=new RecipiesModele();
    return $modele->getSortedRecipies($categorie,$criteria,$asc);
   }
  
    public function showHome(){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->slides();
       $view->ShowRecipiesCards('desert');
       $view->ShowRecipiesCards('plat');
        $view->ShowRecipiesCards('boisson');
         $view->ShowRecipiesCards('entree');
       $view->footer();    
    }
    public function showRecipie($title){
        $view=new ClientView();
        $view->header();
        $view->navbar();
$view->showRecipie($title);
       $view->footer();    
    }
    public function showNews($title){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showNews($title);
       $view->footer();    
    }
    public function showFetes($fetes){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showFetes($fetes);
       $view->footer();    
    }
    public function showSaisons($saison){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showSaisons($saison);
       $view->footer();    
    }
    public function showAdminHome(){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showAdminCards();
       $view->footer();    
    }
    public function getRecipieCalories($title){
        $model=new RecipiesModele();
        return $model->getRecipieCalories($title);
    }
    public function GenerateFilter($categorie,$arrayOfFilters){
        if($categorie=="all"){
            $q='SELECT * FROM recette ';
        }
       else{$q='SELECT * FROM recette where valider=1 and categorie="'.$categorie.'"  ';}
        if(strlen($arrayOfFilters[0])!=0){
            $q.=' and tcuisson>="'.$arrayOfFilters[0].'" ';
        }
        if(strlen($arrayOfFilters[1])!=0){
            $q.=' and tcuisson<="'.$arrayOfFilters[1].'" ';
        }
        if(strlen($arrayOfFilters[2])!=0){
            $q.=' and tpreparation>="'.$arrayOfFilters[2].'" ';
        }
        if(strlen($arrayOfFilters[3])!=0){
            $q.=' and tpreparation<='.$arrayOfFilters[3].' ';
        }
        if(strlen($arrayOfFilters[4])!=0){
            $q.=' and ((SEC_TO_TIME(TIME_TO_SEC(tcuisson)+TIME_TO_SEC(tpreparation)))>="'.$arrayOfFilters[4].'") ';
        }
        if(strlen($arrayOfFilters[5])!=0){
            $q.=' and ((SEC_TO_TIME(TIME_TO_SEC(tcuisson)+TIME_TO_SEC(tpreparation)))<="'.$arrayOfFilters[5].'") ';
        }
        if(strlen($arrayOfFilters[6])!=0){
            $q.=' and notation>='.$arrayOfFilters[6].' ';
        }
        if(strlen($arrayOfFilters[7])!=0){
            $q.=' and notation<='.$arrayOfFilters[7].' ';
        }
        if(strlen($arrayOfFilters[8])!=0){
            $q.='and nbCalorie>='.$arrayOfFilters[8].' ';
        }
        if(strlen($arrayOfFilters[9])!=0){
            $q.='and nbCalorie<='.$arrayOfFilters[9].' ';
        }
        if(strlen($arrayOfFilters[10])!=0){
            $q.=' and titre in ( SELECT ID FROM (SELECT composition.titre as ID ,COUNT(ingredient.nom) as n FROM ingredient INNER JOIN composition on ingredient.nom=composition.nom where ingredient.saison like '.$arrayOfFilters[10].' group by composition.titre) AS A INNER JOIN (SELECT composition.titre,COUNT(ingredient.nom) as n2 FROM ingredient INNER JOIN composition on ingredient.nom=composition.nom group by composition.titre) AS B ON A.ID = B.titre where n=n2) and  valider=1 and categorie="'.$categorie.'" ';
        }
        
        $q.=';';
        return $q;
    }
    public function matcher($arr1,$arr2){
        $i=0;
        $arr=[];
        foreach($arr1 as $a){
            array_push($arr,$a['nom']);
        }
        foreach($arr2 as $ing){
            if(in_array($ing,$arr))
            $i++;
        }
        return $i*100/count($arr2);
    }
    public function SearchMatchRecipies($array){
        $recipies=$this->getAllRecipies();
        if(count($array)==0)
        return $recipies;
        else{
        $arr=[];
        foreach($recipies as $x){
            $ingredients=$this->getRecipieIngredients($x['titre']);
            if($this->matcher($ingredients,$array)>=70)
            array_push($arr,$x);            
        }
        return $arr;
    }
    }
    public function getHealthyRecipies($nbCalories){
         $model=new RecipiesModele();
        return $model->getHealthyRecipies($nbCalories);
    }
    public function AuthentifyUser($mail,$password){
        $model=new RecipiesModele();
        return $model->AuthentifyUser($mail,$password);
    }
    public function AuthentifyAdmin($nom,$password){
        $model=new RecipiesModele();
        return $model->AuthentifyAdmin($nom,$password);
    }
    public function getUser($mail,$password){
        $model=new RecipiesModele();
        return $model->getUser($mail,$password);
    }
    public function AddUser($nom,$prenom,$mail,$sex,$dateN,$password){
        $model=new RecipiesModele();
        $model->AddUser($nom,$prenom,$mail,$sex,$dateN,$password);
    }
     public function showCategories($categorie,$criteria,$asc){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showSortedCategories($categorie,$criteria,$asc);
       $view->footer();    
    }
    /*public function showGestionRecette($arrayofFilters){
     $view=new AdminView();
        $view->header();
        $view->navbar();
        $view->GestionRecette($arrayofFilters);
       $view->footer();   
    }
    public function showGestionRecetteSorted($criteria,$asc){
     $view=new AdminView();
        $view->header();
        $view->navbar();
        $view->GestionRecetteSort($criteria,$asc);
       $view->footer();   
    }*/
    public function AddNews(){
      $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->AddNews();
       $view->footer();    
    }
    public function AddNutrition(){
      $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->AddNutrition();
       $view->footer();   
    }
    public function showAllNews(){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showAllNews();
       $view->footer();    
    }
    public function showNutrition(){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showNutrition();
       $view->footer();    
    }
    public function showContact(){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showContact();
       $view->footer(); 
    }
    public function showFilterd($categorie,$q){
        $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showFilterdCategories($categorie,$q);
       $view->footer(); 
    }
    public function getUnvalidated(){
        $modele=new RecipiesModele();
        return $modele->getUnvalidated();  
    }
    public function getFilterdRecipies($Categorie,$arrayoffilters){
        $q=$this->GenerateFilter($Categorie,$arrayoffilters);
        $modele=new RecipiesModele();
        return $modele->getFilterdRecipies($q);     
    }
   public function do($q){
     $modele=new RecipiesModele();
        return $modele->do($q); 
   }
   public function insert($q){
     $modele=new RecipiesModele();
       $modele->insert($q); 
   }
    public function showIdee($array){
         $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showChoiceIngredients($array);
       $view->footer(); 
    }
     public function showHealthyRecipies($nbCalories){
         $view=new ClientView();
        $view->header();
        $view->navbar();
        $view->showHealthyRecipies($nbCalories);
       $view->footer(); 
    }
    public function showLogin(){
     $view=new ClientView();
        
        $view->header();
        $view->navbar();
        $view->showLogin();
       $view->footer(); 
       
    }
    public function showSignup(){
     $view=new ClientView();
        
        $view->header();
        $view->navbar();
        $view->showSignup();
       $view->footer(); 
       
    }
    public function showAjouterRecette(){
     $view=new ClientView();    
        $view->header();
        $view->navbar();
    $view->ajouterRecette();
    $view->footer();
    }
    public function showProfile(){
         $view=new ClientView();    
        $view->header();
        $view->navbar();
        $view->showProfile();
        $view->footer();
    }
}

?>
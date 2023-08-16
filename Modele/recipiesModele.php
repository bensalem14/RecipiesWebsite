<?php
class RecipiesModele{

 private $servername = "127.0.0.1";
private $dbname = "projet";
private $username = "root";
private $password = "";

private function cnx($servername,$dbname,$username,$password){
    $dsn="mysql:host=$servername; dbname=$dbname;";
    try {
    $conn = new PDO($dsn,$username, $password);     }
    catch(PDOException $e) {
    echo "connection failed" . $e->getMessage();}
    return $conn;
}
private function dcx(&$conn){
    $conn=null;
}

public function getRecipies($categorie){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM recette where categorie=? and valider=1;";
    $stmt = $c->prepare($q);
    $stmt->execute([$categorie]);
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getUnvalidated(){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM recette where valider=0;";
    $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    
    public function getSingleRecipie($title){
        $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM recette where titre=? and valider=1;";
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    return $stmt->fetch();
    
    }
    public function getRecipieTotalTime($title){
        $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q='SELECT * from recette where titre=? and valider=1;';
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    $x=$stmt->fetch();
    $zero=strtotime('00:00:00');
    $x=strtotime($x['tpreparation'])+strtotime($x['tcuisson'])+strtotime($x['trepos'])-3*$zero;
    $h=intval($x/3600);
    $x=$x-$h*3600;
    $m=intval($x/60);
    $x=$x-$m*60;
    $s=$x;
    return sprintf("%02d:%02d:%02d",$h,$m,$s);
    }
    public function getRecipieSteps($title)
    {
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM etape where titreRecette=? ORDER BY ordre";
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getRecipieIngredients($title)
    {
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM composition where titre=? ";
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getRecipieIngredientsInfo($title)
    {
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM ingredient left join composition on ingredient.nom=composition.nom where composition.titre=?";
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getRecipieCalories($title)
    {
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT composition.quantite,composition.unite,ingredient.calorie FROM composition INNER JOIN ingredient On composition.titre=? and composition.nom=ingredient.nom";
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    $cal=$stmt->fetchAll();
    $sum=0;
    foreach($cal as $c){
        if($c['unite']=='g' ||  $c['unite']=='ml' )
                $sum+=$c['calorie']*$c['quantite']/100;
                else $sum+=$c['calorie']*$c['quantite'];
        
    }
    return $sum;
}
public function getSingleNews($title){
      $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM news where titre=?";
    $stmt = $c->prepare($q);
    $stmt->execute([$title]);
    $this->dcx($c);
    return $stmt->fetch();
    }
    public function getAllNews(){
      $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM news ";
    $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getFetesRecipies(){
        $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM recette where fetes<>'' and valider=1";
     $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    function getAllRecipies(){
        $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM recette where valider=1 ";
     $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getSaisonRecipies($saison){
        $formatted='%'.$saison.'%';
        $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="select * from recette where valider=1 and titre in ( SELECT ID FROM (SELECT composition.titre as ID ,COUNT(ingredient.nom) as n FROM ingredient INNER JOIN composition on ingredient.nom=composition.nom where ingredient.saison like ? group by composition.titre) AS A INNER JOIN (SELECT composition.titre,COUNT(ingredient.nom) as n2 FROM ingredient INNER JOIN composition on ingredient.nom=composition.nom group by composition.titre) AS B ON A.ID = B.titre where n=n2);";
     $stmt = $c->prepare($q);
    $stmt->execute([$formatted]);
    $this->dcx($c);
    return $stmt->fetchAll();
    }
    public function getFilterdRecipies($query){
     $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q=$query;
    $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return $stmt->fetchAll();
   /* if(strlen($query[1])!=0){
        $arr1=[];
        $arr2=[];
        $saisonrec=$this->getSaisonRecipies($query[1]);
        foreach($saisonrec as $rec){
            array_push($arr1,$rec['titre']);
        }
        foreach($stmt as $rec){
            if(in_array($rec['titre'],$arr1))
            array_push($arr2,$rec);
        }
        return $rec;
    }else */
    }
    public function AuthentifyUser($mail,$password){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM `users` WHERE mail=? and password=? ;";
    $stmt = $c->prepare($q);
    $stmt->execute([$mail,$password]);
    $this->dcx($c);
    return (!empty($stmt->fetch()));
    }
    public function search($q){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return (!empty($stmt->fetch()));
    }
    public function AuthentifyAdmin($nom,$password){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM `admins` WHERE nom=? and password=? ;";
    $stmt = $c->prepare($q);
    $stmt->execute([$nom,$password]);
    $this->dcx($c);
    return (!empty($stmt->fetch()));
    }
    public function getUser($mail,$password){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM `users` WHERE mail=? and password=? ;";
    $stmt = $c->prepare($q);
    $stmt->execute([$mail,$password]);
    $this->dcx($c);
    return $stmt->fetch();
    }
    public function AddUser($nom,$prenom,$mail,$sex,$date,$password){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="INSERT  into `users` (`nom`, `prenom`, `mail`, `sex`,`dateN`, `password`) values(?,?,?,?,?,?);";
    $stmt = $c->prepare($q);
    $stmt->execute([$nom,$prenom,$mail,$sex,$date,$password]);
    $this->dcx($c);
    }
    function cmpCalorie($a,$b){
        $a1=$this->getRecipieCalories($a['titre']);
        $b1=$this->getRecipieCalories($b['titre']);
       
        return ($a1<$b1)?1:(($a1===$b1)?0:-1);
        }
        function rcmpCalorie($a,$b){
        $a1=$this->getRecipieCalories($a['titre']);
        $b1=$this->getRecipieCalories($b['titre']);
        return ($a1>$b1)?1:(($a1===$b1)?0:-1);
        }
          function cmpTtotal($a,$b){
            $zero=strtotime('00:00:00');
        $a1=strtotime($a['tpreparation'])+strtotime($a['tcuisson'])-2*$zero;
        $b1=strtotime($b['tpreparation'])+strtotime($b['tcuisson'])-2*$zero;
        return ($a1<$b1)?1:(($a1===$b1)?0:-1);
        }
        function rcmpTtotal($a,$b){
         $zero=strtotime('00:00:00');
        $a1=strtotime($a['tpreparation'])+strtotime($a['tcuisson'])-2*$zero;
        $b1=strtotime($b['tpreparation'])+strtotime($b['tcuisson'])-2*$zero;
       
        return ($a1>$b1)?1:(($a1===$b1)?0:-1);
        }
    public function getSortedRecipies($categorie,$Criteria,$asc){
        /*les temps de préparation et de 
cuisson et le temps total, la notation et le nombre de calories.*/
if(($categorie=='')||($categorie=='all')) $recipie=$this->getAllRecipies();
   else  $recipie=$this->getRecipies($categorie);
    if($Criteria==="none"){
        return $recipie;
    }
    if($Criteria==='ttotal'){
        if($asc='asc')
        usort($recipie,function($a,$b){return $this->cmpTtotal($a,$b);});
        else 
        usort($recipie,function($a,$b){return $this->rcmpTtotal($a,$b);});
        return $recipie;
    }
    else if($Criteria==='nbCalorie'){
        
        if($asc='asc')
        usort($recipie,function($a,$b){return $this->cmpCalorie($a,$b);});
        else 
        usort($recipie,function($a,$b){return $this->rcmpCalorie($a,$b);});
        return $recipie;
    }
    else if($Criteria==='notation'){
        
         if($asc='asc')
        usort($recipie,function($a,$b){$a1=$a['notation'];$b1=$b['notation'];return ($a1>$b1)?1:(($a1===$b1)?0:-1);});
        else 
        usort($recipie,function($a,$b){$a1=$a['notation'];$b1=$b['notation'];return ($a1<$b1)?1:(($a1===$b1)?0:-1);});
        return $recipie;
    }
    else if($Criteria==='tcuisson'){
      if($asc='asc')
        usort($recipie,function($a,$b){$a1=strtotime($a['tcuisson']);$b1=strtotime($b['tcuisson']);return ($a1>$b1)?1:(($a1===$b1)?0:-1);});
        else 
        usort($recipie,function($a,$b){$a1=strtotime($a['tcuisson']);$b1=strtotime($b['tcuisson']);return ($a1<$b1)?1:(($a1===$b1)?0:-1);});
        return $recipie;
    }
     else if($Criteria==='tpreparation'){
      if($asc='asc')
        usort($recipie,function($a,$b){$a1=strtotime($a['tpreparation']);$b1=strtotime($b['tpreparation']);return ($a1>$b1)?1:(($a1===$b1)?0:-1);});
        else 
        usort($recipie,function($a,$b){$a1=strtotime($a['tpreparation']);$b1=strtotime($b['tpreparation']);return ($a1<$b1)?1:(($a1===$b1)?0:-1);});
        return $recipie;
    }
}
public function getHealthyRecipies($nbCalories){
    $recipies=$this->getAllRecipies();
    $arr=[];
    if($nbCalories!=="inf"){
       foreach($recipies as $a){
        if($a['nbCalorie']>=$nbCalories)
        continue;
        $steps=$this->getRecipieSteps($a['titre']);
        foreach($steps as $s){
            if($s['healthy'] ){
                array_push($arr,$a);
                break;
            }
        }
    }
     
    }else {
         foreach($recipies as $a){
        $steps=$this->getRecipieSteps($a['titre']);
        foreach($steps as $s){
            if($s['healthy'] ){
                array_push($arr,$a);
                break;
            }
        }
    }
    }
    
    
    return $arr;
}
public function getAllIngredients(){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM ingredient";
     $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);
    return $stmt->fetchAll();
}
public function valider($titre){
     $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="UPDATE recette SET valider = 1 WHERE titre=?;";
     $stmt = $c->prepare($q);
    $stmt->execute([$titre]);
    $this->dcx($c);
}
public function addPrefrence($titre,$id){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="INSERT  into `preference` (`titre`, `id`) values(?,?);";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre,$id]);
    $this->dcx($c);   
}
public function addRating($titre,$id,$rate){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="INSERT  into `rating` (`titre`, `id`,`rating`) values(?,?,?);";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre,$id,$rate]);
    $this->dcx($c);   
}
public function do($q){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
   $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);  
    return $stmt->fetchAll();
}
public function insert($q){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
   $stmt = $c->prepare($q);
    $stmt->execute();
    $this->dcx($c);  
   
}
public function addRecette($titre, $categorie, $valider, $userid, $ddescription, $description, $tpreparation, $trepos, $tcuisson, $notation,$defficulte, $fetes , $nbCalorie){
     $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="INSERT  into `recette`  (`titre`, `categorie`, `valider`, `userid`, `ddescription`, `description`, `tpreparation`, `trepos`, `tcuisson`, `notation`, `defficulte`, `fetes` , `nbCalorie`) values ('?','?',?,?,'?','?','?','?','?',?,'?',null,?)";
    $stmt = $c->prepare($q);
    $success=$stmt->execute([$titre, $categorie, $valider, $userid, $ddescription, $description, $tpreparation, $trepos, $tcuisson, $notation,$defficulte, $fetes , $nbCalorie]);
    $this->dcx($c);  
    return  $success;
}
public function searchRecette($titre){
     $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT *  from `recette` where titre=? ";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre]);
    $this->dcx($c);
    return (!empty($stmt->fetch()));
}
public function deleteRecette($titre){
     $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="DELETE FROM `recette`  where titre=?;";
     $stmt = $c->prepare($q);
    $stmt->execute([$titre]);
    $q="DELETE FROM `composition`  where titre=?;";
     $stmt = $c->prepare($q);
    $stmt->execute([$titre]);
    $q="DELETE FROM `etape`  where titre=?;";
     $stmt = $c->prepare($q);
    $stmt->execute([$titre]);
    $this->dcx($c);   
}
public function searchRating($titre,$id){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT *  from `rating` where titre=? and id=?;";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre,$id]);
    $this->dcx($c);
    return (!empty($stmt->fetch()));
}
public function searchPrefrence($titre,$id){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT *  from `preference` where titre=? and id=?;";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre,$id]);
    $this->dcx($c);
    return (!empty($stmt->fetch()));
}
public function deleteRating($titre,$id){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="DELETE FROM `rating` where titre=? and id=?;";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre,$id]);
    $this->dcx($c);
}
public function deletePrefrence($titre,$id){
   $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="DELETE FROM `preference` where titre=? and id=?;";
    $stmt = $c->prepare($q);
    $stmt->execute([$titre,$id]);
    $this->dcx($c);
}
/*public function test(){
    $c=$this->cnx($this->servername,$this->dbname,$this->username,$this->password);
    $q="SELECT * FROM recette";
     $stmt = $c->prepare($q);
    $stmt->execute();
    $arr=$stmt->fetchAll();
    foreach($arr as $a){
    $q="UPDATE recette SET nbCalorie=? where titre=? ;";
    echo $this->getRecipieCalories($a['titre']);
    $stmt = $c->prepare($q);
     $stmt->execute([$this->getRecipieCalories($a['titre']),$a['titre']]);
    }
    $this->dcx($c);
}*/
}
/*
$x=new RecipiesModele();
echo ($x->searchPrefrence("Kalb El Louz",1))?"hello":"not";*/
?>
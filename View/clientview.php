<?php
require_once("../Controller/recipiesController.php");
class ClientView{

public function header(){
  if(!isset($_SESSION)) 
    { 
    session_start();
    }
    
echo '
 
<!Doctype html>
<html lang="en">

<head>
    <title>MaklaDz</title>
    <meta http-equiv="pragma" content="no-cache">
    <meta name="description" content="un site web pour les recettes ">
    <meta name="viewport" content="width=device-width, initial-scale=1,
            shrink-to-fit=yes">
    <link href="../styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="utf-8">
    <!--pour le bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script src="../jquery.js"></script>
<script src="../jquery-3.6.1.min.js"></script>
<script src="../scripts.js"></script>
    <!--pour les icons-->

</head>';
}
public function navbar(){
    
    $x=new RecipiesController();
echo '
<body>
    <div class="navbar1">';
    if(isset($_SESSION['user']) || isset($_SESSION['admin'])){
                echo '<a class=" btn btn-dark connectButton "  href="Loggout.php" >Logg Out</a>';
            }
            else 
    echo '
        <a class=" btn btn-dark connectButton "  href="Login.php" >Login</a>';
        echo '<img src="../media/images/logo/websitelogo.png" class="rounded" alt="www.Makla.dz">
        <div class="socialmedia">
            <a href="#" class="fa fa-facebook"></a>
            <a href="#" class="fa fa-twitter"></a>
            <a href="#" class="fa fa-instagram"></a>
            <a href="#" class="fa fa-pinterest"></a>
        </div>
    </div>';
    
    if(isset($_SESSION['admin'])){
      echo '
    <nav class="container-fluid navbar-fixed-top">
        <div class="navbar2">
            <a href="AdminHome.php">Home</a>
            <a href="AllNews.php">News</a>
            <a href="GestionRecetteTrie.php">Recettes</a>
            <a href="Nutrition.php">Nutrition</a>
            <a href="idee.php">Utilisateurs</a>
            <a href="idee.php">Paramètre</a>
       </div>
    </nav>
    <main>';
    }else{
    echo '
    <nav class="container-fluid navbar-fixed-top">
        <div class="navbar2">
            <a href="index.php">Home</a>
            <a href="AllNews.php">News</a>
            <a href="Saison.php?requestedSaison='.$x->getCurrentSaison().'">Saison</a>
            <a href="Fetes.php?requestedFetes=tous">Fetes</a>
            <a href="Healthy.php">Healthy</a>
            <a href="Nutrition.php">Nutrition</a>
            <a href="idee.php">Idées</a>
            <a href="Contact.php">Contact</a>';
            if(isset($_SESSION['user'])){
                echo '<a href="AjouterRecette.php">AjouterRecette</a>
                <a href="Profile.php">Profile</a>';
            }
       echo ' </div>
    </nav>
    <main>';}

    }
    public function showProfile(){
     echo '<center><h1> Page '.$_SESSION['user']['nom'].' '.$_SESSION['user']['prenom'].'<h1></center>';
     echo '<h2 style="margin-top:30px;margin-bottom:30px;">list des Preferences</h2>';
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
      
       $controller=new RecipiesController();
       $preference=$controller->do("SELECT * FROM preference WHERE id='".$_SESSION['user']['userid']."'");
    $arr=[];
    for($x=0;$x<count($preference);$x++){
      array_push($arr,$controller->getSingleRecipie($preference[$x]['titre']));
    }
       for($x=0;$x<count($arr);$x++){
        echo $this->card($x,$arr);     
    }
    echo ' </div></section>';
    echo '<h2 style="margin-top:30px;margin-bottom:30px;">Recette Noté</h2>';
    echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';

       $preference=$controller->do("SELECT * FROM rating WHERE id='".$_SESSION['user']['userid']."'");
    $arr=[];
    for($x=0;$x<count($preference);$x++){
      array_push($arr,$controller->getSingleRecipie($preference[$x]['titre']));
    }
       for($x=0;$x<count($arr);$x++){
        echo $this->card($x,$arr);     
    }
    echo ' </div></section>';
    echo '<h2 style="margin-top:30px;margin-bottom:30px;">Recette Ajouter</h2>';
    echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';

       $preference=$controller->do("SELECT * FROM recette WHERE userid='".$_SESSION['user']['userid']."'");
    $arr=[];
    for($x=0;$x<count($preference);$x++){
      array_push($arr,$controller->getSingleRecipie($preference[$x]['titre']));
    }
       for($x=0;$x<count($arr);$x++){
        echo $this->card($x,$arr);     
    }
    echo ' </div></section>';
    }
    public function slides()
    {   $controller=new RecipiesController();
        $news=$controller->getAllNews();
        $desert=$controller->getRecipies('desert');
       echo ' <section class="slider">
     <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">';
        echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                 aria-current="true" aria-label="Slide 1"></button>';
        for($i=1;$i<4;$i++){
            echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$i.'" 
                 aria-current="true" aria-label="Slide '.($i+1).'"></button>';
        }
        echo '</div><div class="carousel-inner">';
        echo '<div class="carousel-item active" data-bs-interval="5000">
                <img src="../'.$news[0]['image'].'" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>'.$news[0]['titre'].'</h5>
                </div>
            </div>';
        for($i=1;$i<4;$i++){
            echo '<div class="carousel-item  data-bs-interval="5000">
                <img src="../'.$desert[$i]['image'].'" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>'.$desert[$i]['titre'].'</h5>
                </div>
            </div>';
        }
        echo '  </div>
    </div>
</section>';
    }

public function card($x,$recipies){
     echo "<div class='col-sm mb-4'>
        <div class='card'>
            <img class='img-fluid' alt='100%x280' src=\"..\/".$recipies[$x]['image']."\">
            <div class='card-body'>
                <h4 class='card-title'>".$recipies[$x]['titre']."</h4>
                <p class='card-text'>".$recipies[$x]['ddescription']."</p>
                 <a href=\"Recette.php?requestedRecipie=".$recipies[$x]['titre']."\" class='ReadMore btn btn-primary'>Lire Plus</a>
                 </div>
        </div>
    </div>";
}
public function row($x,$recipies){
    echo '<tr>
      <th scope="row">'.$recipies[$x]['titre'].'</th>
      <td>'.$recipies[$x]['valider'].'</td>
      <td><a href="Operation.php?op=valider&titre='.$recipies[$x]['titre'].'" type="button" class="btn btn-success">Valider</a></td>
      <td><a href="ModifierRecette.php?titre='.$recipies[$x]['titre'].'"" type="button" class="btn btn-primary">Modifier</a></td>
      <td><a href="Operation.php?op=supprimer&titre='.$recipies[$x]['titre'].'"" type="button" style="color:white;background-color:red;" class="btn btn-btn-warning">Supprimer</a></td>
    </tr>';
}
public function Nutritionrow($x,$recipies){
    echo '<tr>
      <th scope="row">'.$recipies[$x]['nom'].'</th>
      <td>'.$recipies[$x]['healthy'].'</td>
      <td><a href="Operation.php?op=Healthy&titre='.$recipies[$x]['nom'].'" type="button" class="btn btn-success">rend Healthy</a></td>
      <td><a href="AddNutrition.php?titre='.$recipies[$x]['nom'].'"" type="button" class="btn btn-primary">Modifier</a></td>
      <td><a href="Operation.php?op=supprimerIngredient&titre='.$recipies[$x]['nom'].'"" type="button" style="color:white;background-color:red;" class="btn btn-btn-warning">Supprimer</a></td>
    </tr>';
}

public function newsrow($x,$recipies){
    echo '<tr>
      <th scope="row">'.$recipies[$x]['titre'].'</th>
      <td>'.$recipies[$x]['display'].'</td>
      <td><a href="NewsOperation.php?op=display&titre='.$recipies[$x]['titre'].'" type="button" class="btn btn-success">Ajouter a News</a></td>
      <td><a href="NewsOperation.php?op=undisplay&titre='.$recipies[$x]['titre'].'"" type="button" class="btn btn-primary">Supprimer de News</a></td>
     </tr>';
   
}
public function nutrition($x,$ingredients){
    
     echo "<div class='col-sm mb-4'>
        <div class='nutritioncard'>
    <div class=\"calories-info\">
      <div class=\"left-container\">
        <h2 class=\"bold small-text\"></h2>
        <p>".$ingredients[$x]['nom']."</p>
      </div>
      
    </div>
    <div class=\"divider medium\"></div>
    <div class=\"daily-value small-text\">
      <p class=\"bold right no-divider\">Pour 100g/ml </p>
      <div class=\"divider\"></div>
      <p><span><span class=\"bold\">Calorie</span>    ".$ingredients[$x]['calorie']."</span> </p>
      <p><span><span class=\"bold\">Protéin</span>    ".$ingredients[$x]['protein']."g</span> </p>
      <p><span><span class=\"bold\">Fibre</span>    ".$ingredients[$x]['fibre']."g</span> </p>
      <p><span><span class=\"bold\">l'eau</span>    ".$ingredients[$x]['leau']."%</span> </p>
      <p><span><span class=\"bold\">Carb</span>    ".$ingredients[$x]['carb']."g</span> </p>
      <p><span><span class=\"bold\">Sodium</span>    ".$ingredients[$x]['carb']."g</span> </p>
      <p><span><span class=\"bold\">Fer</span>    ".(1000*floatval($ingredients[$x]['Fer']))."mg</span> </p>
      <p><span><span class=\"bold\">Potassium</span>    ".(1000*floatval($ingredients[$x]['Potassium']))."mg</span> </p>
      <p><span><span class=\"bold\">Phosphore</span>    ".(1000*floatval($ingredients[$x]['Phosphore']))."mg</span> </p>
       <p><span><span class=\"bold\">Magnesium</span>    ".(1000*floatval($ingredients[$x]['Magnsium']))."mg</span> </p>
       <p><span><span class=\"bold\">Zinc</span>    ".(1000*floatval($ingredients[$x]['Magnsium']))."mg</span> </p>
       <p><span><span class=\"bold\">Saison</span>    ".$ingredients[$x]['saison']."</span> </p>
      
     
    </div>";
    if($ingredients[$x]['healthy']==="1") echo "<p id=\"ishealthy\"><span><span class=\"bold\">Healthy</span>   </span> </p>";
 echo " </div>
    </div>";
}
public function showAdminCards(){
    $control=new RecipiesController();
    echo '<center><h1> Page Admin <h1></center>';

     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-5">';
     echo "<div class='col-sm mb-4'>
     <a href=\"Nutrition.php\">
        <div class='admincard'>
            <img class='img-fluid' alt='100%x280' src=\"../media/images/logo/nutrition.png\">
            <div class='card-body'>
                <h4 class='card-title'>Gestion des Nutritions</h4>
                </div>
        </div>
        </a>
    </div>";
     echo "<div class='col-sm mb-4'>
     <a href=\"AllNews.php\">
        <div class='admincard'>
            <img class='img-fluid' alt='100%x280' src=\"..\/media/images/logo/news.jpg\">
            <div class='card-body'>
                <h4 class='card-title'>Gestion des News</h4>
                </div>
        </div>
        </a>
    </div>";
     echo "<div class='col-sm mb-4'>
     <a href=\"GestionParam.php\">
        <div class='admincard'>
            <img class='img-fluid' alt='100%x280' src=\"..\/media/images/logo/param.png\">
            <div class='card-body'>
                <h4 class='card-title'>Gestion des Paramtères</h4>
                </div>
        </div>
        </a>
    </div>";
     echo "<div class='col-sm mb-4'>
     <a href=\"GestionUser.php\">
        <div class='admincard'>
            <img class='img-fluid' alt='100%x280' src=\"..\/media/images/logo/user.png\">
            <div class='card-body'>
                <h4 class='card-title'>Gestion des utilisateurs</h4>
                </div>
        </div>
        </a>
    </div>";
     echo "<div class='col-sm mb-4'>
        <div class='admincard'>
        <a href=\"GestionRecetteTrie.php\">
            <img class='img-fluid' alt='100%x280' src=\"../media/images/logo/websitelogo.png\">
            <div class='card-body'>
                <h4 class='card-title'>Gestion des Recettes</h4>
                </div>
                </a>
        </div>
    </div>";
    echo ' </div></section>';
}
    public function ShowRecipiesCards($categorie){
        $controller=new RecipiesController();
        $recipies=$controller->getRecipies($categorie);
   
   echo '
<section class=" dessert pt-5 pb-5 ">
    <div class=" container">
        <div class="row">
            <div class="col-6">
                <h3 class="mb-3"><a href="PageCategorie.php?requestedCategorie='.$categorie.'&requestedCriteria=none">'.$categorie.'</a></h3>
            </div>
            <div class="col-6 text-right">
                <a class="btn btn-primary mb-3 mr-1" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <a class="btn btn-primary mb-3 " href="#carouselExampleIndicators2" role="button" data-slide="next">
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
            <div class="col-10">
                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row">';
                                if(count($recipies)!=0)
                                for ($x = 0; $x <4; $x++) {
                                      $this->card($x,$recipies);
                                }
                                echo' 
</div>
</div>
<div class="carousel-item">
    <div class="row">';

        
                                for ($x = 4; $x <8; $x++) {
                                     
                                   $this->card($x,$recipies);
                                }
                                echo '
</div>
</div>
<div class="carousel-item">
    <div class="row">';
                                for ($x = 8; $x <10; $x++) {
                                     $this->card($x,$recipies);
                                }
                                echo '
</div>
</div>
</div>
</div>
</div>
</div>
</section>';
}
    public function footer(){echo '
         </main>
        <footer class="bg-dark text-center text-white">
  <!-- Grid container -->
  <div class="container p-4 pb-0">
    <!-- Section: Social media -->
    <section class="mb-4">
      <!-- Facebook -->
      <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fa fa-facebook"></i></a>

      <!-- Twitter -->
      <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fa fa-twitter"></i></a>

      <!-- Instagram -->
      <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fa fa-instagram"></i ></a>

      <!-- Linkedin -->
      <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fa fa-pinterest"></i
      ></a>

      <!-- Github -->
      <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"
        ><i class="fa fa-github"></i
      ></a>
    </section>
    <!-- Section: Social media -->
  </div>
  <!-- Grid container -->

  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
    <a class="text-white" href="https://mdbootstrap.com/">MaklaDz</a>
  </div>
  <!-- Copyright -->
</footer>
       
</body>
<!--pour lenvoi des emails-->

</html>';

}
public function formatTime($str){
    return $str[0].$str[1].'hr '.$str[3].$str[4].'min '.$str[6].$str[7].'sec';
}
public function showRecipieSteps($title){
    $control=new RecipiesController();
    $steps=$control->getRecipieSteps($title);
    for($x=0;$x<count($steps);$x++)
     echo '<div class="single-instruction">
                            <header>
                                <p>step '.$x.'</p>
                                <div></div>
                            </header>
                            <p>'.$steps[$x]['description'].'
                            </p>
                        </div>';
    
}
public function showRecipieIngredients($title){
    $x=new RecipiesController();
    $ingredients=$x->getRecipieIngredients($title);
    for($x=0;$x<count($ingredients);$x++)
    echo '<p class="single-ingredient">*'.$ingredients[$x]['quantite'].' '.$ingredients[$x]['unite'].' '.$ingredients[$x]['nom'].'</p>';
}


public function showRecipie($titre){
    $control=new RecipiesController();
    $recipie=$control->getSingleRecipie($titre);
     echo '<div class="recipe-page">
                <section class="recipe-hero">
                    <img src="../'.$recipie['image'].'" class="img recipe-hero-img"/>
                    <article class="recipe-info">
                        <h1>'.$recipie['titre'].'</h1>
                        <p>'.$recipie['description'].' </p>
                        <div class="recipe-icons">
                            <article>
                                <i class="fas fa-user-friends"></i>
                                <h5>Temp Préparation</h5>
                                <p>'.$this->formatTime($recipie['tpreparation']).'</p>
                            </article>
                            <article>
                                <i class="fas fa-user-friends"></i>
                                <h5>Temp Cuisson</h5>
                                <p>'.$this->formatTime($recipie['tcuisson']).'</p>
                            </article>
                            <article>
                                <i class="fas fa-user-friends"></i>
                                <h5>Temp Repos</h5>
                                <p>'.$this->formatTime($recipie['trepos']).'</p>
                            </article>
                            <article>
                                <i class="fas fa-user-friends"></i>
                                <h5>Temp Total</h5>
                                <p>'.$this->formatTime($control->getRecipieTotalTime($recipie['titre'])).'</p>
                            </article>
                        </div>

                    </article>
                </section>
                <!-- content -->
                <section class="recipe-content">
                    <Article>
                        <h4>Nombre de Calories : '.$control->getRecipieCalories($recipie['titre']).' kcal</h4>
                        <h4>Difficulté : '.$recipie['defficulte'].'</h4>
                        <h4>Notation : '.$recipie['notation'].'/5</h4>
                        <h4>instructions</h4>';
                        $this->showRecipieSteps($recipie['titre']);
                        echo '                        
                    </article>
                    <article class="second-column">
                        <div>
                            <h4>ingredients</h4>';
                            $this->showRecipieIngredients($recipie['titre']);
                        echo '</div>';
                      if(isset($_SESSION['user'])){ echo '<a type="button" href="addPrefrences.php?requestedRecipie='.$recipie['titre'].'" class="heart btn btn-danger"><i class="fa fa-heart"> Ajouter Au Favoris</i></a>';
                    echo '<ul class="rate-area">
  <a href="addRating.php?rating=5&user='.$_SESSION['user']['userid'].'&recipie='.$recipie['titre'].'" class="fa  fa-star" id="5-star" name="rating" value="5" ><label for="5-star" title="Amazing">5 stars</label></a>
  <a href="addRating.php?rating=4&user='.$_SESSION['user']['userid'].'&recipie='.$recipie['titre'].'" class="fa fa-star" id="4-star" name="rating" value="4" ><label for="4-star" title="Good">4 stars</label></a>
  <a href="addRating.php?rating=3&user='.$_SESSION['user']['userid'].'&recipie='.$recipie['titre'].'" class="fa fa-star" id="3-star" name="rating" value="3" ><label for="3-star" title="Average">3 stars</label></a>
  <a href="addRating.php?rating=2&user='.$_SESSION['user']['userid'].'&recipie='.$recipie['titre'].'" class="fa fa-star" id="2-star" name="rating" value="2" ><label for="2-star" title="Not Good">2 stars</label></a>
  <a href="addRating.php?rating=1&user='.$_SESSION['user']['userid'].'&recipie='.$recipie['titre'].'" class="fa fa-star" id="1-star" name="rating" value="1" ><label for="1-star" title="Bad">1 star</label></a>
</ul>';}
                      echo ' </article>
                </section>
            </div>';

}
public function showNews($title){
    $control=new RecipiesController();
    $news=$control->getSingleNews($title);
    
    echo '
     <section class="mainnews">
            <section class="heading">
                <header class="hero">
                <h1 class="hero-title">'.$news['titre'].'</h1>
                    <img
                        src="../'.$news['image'].'"
                        alt="news logo"
                        loading="lazy"
                        class="newsimg hero-img"
                        />
                    
                    
                </header>
                <div class="author">
                    <p class="author-name">
                        ecrit par
                        <a href="https://freecodecamp.org" target="_blank"
                            rel="noreferrer">Admin</a>
                    </p>
                    <p class="publish-date">'.$news['publishdate'].'</p>
                </div>
            </section>
            <section class="text">
                <p class="first-paragraph">
                    '.$news['paragraph'].'
                </p>
            </section>
            <section></section>
            <section></section>';
            if(!empty($news['video'])){
            echo '<section section="video">
                <video width="1000px" height="240" controls>
                    <source src="../'.$news['video'].'" type="video/mp4">
                </video>
            </section>';}
        echo '</section>';
}
public function showFetes($fetes){
    $control=new RecipiesController();
    
    $recipies=$control->getFetesRecipies($fetes);
    echo '<center><h1> Page Fetes <h1></center>';
    echo '<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    Spécifier fetes
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="Fetes.php?requestedFetes=Mariage">Mariage</a></li>
    <li><a class="dropdown-item" href="Fetes.php?requestedFetes=Ramdan">Ramdan</a></li>
    <li><a class="dropdown-item" href="Fetes.php?requestedFetes=Achoura">Achoura</a></li>
    <li><a class="dropdown-item" href="Fetes.php?requestedFetes=Mouloud">Mouloud</a></li>
    <li><a class="dropdown-item" href="Fetes.php?requestedFetes=Aid">Aid</a></li>
    <li><a class="dropdown-item" href="Fetes.php?requestedFetes=Circoncision">Circoncision</a></li>
  </ul>
  
</div>';
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
    for($x=0;$x<count($recipies);$x++){
        echo $this->card($x,$recipies);     
    }
    echo ' </div></section>';
}
public function showAllNews(){
    $control=new RecipiesController();
    $news=$control->getAllNews();
    $recipies=$control->getAllRecipies();
     echo ' <center><h1>News</h1></center><section class="cardsdisplay ">';
    if(isset($_SESSION['admin'])){
      echo '<a type="button" href="AddNews.php" class="btn btn-secondary">Ajouter News</a>';
    
      echo '<table class="table">
  <thead>
    <tr>
      <th scope="col">Recette</th>
      <th scope="col">Afficher dans Diaporama</th>
      <th scope="col">operation 1</th>
      <th scope="col">operation 2</th>
    </tr>
  </thead>
  <tbody>';
   for($x=0;$x<count($news);$x++){
        echo $this->newsrow($x,$news);     
    }
     for($x=0;$x<count($recipies);$x++){
        echo $this->newsrow($x,$recipies);     
    }
    
    echo '</tbody>
</table>';
    }
    else {echo '<div class="row row-cols-1 row-cols-md-4">';
      for($x=0;$x<count($news);$x++){
         echo "<div class='col-sm mb-4'>
        <div class='card'>
            <img class='img-fluid' alt='100%x280' src=\"..\/".$news[$x]['image']."\">
            <div class='card-body'>
                <h4 class='card-title'>".$news[$x]['titre']."</h4>
                <p class='card-text'>".$news[$x]['description']."</p>
                 <a href=\"News.php?requestedNews=".$news[$x]['titre']."\" class='ReadMore btn btn-primary'>Lire Plus</a>
                </div>
        </div>
    </div>";}
     
    }
    echo ' </div></section>';
}
public function showSaisons($saison){
    $control=new RecipiesController();
    $recipies=$control->getSaisonRecipies($saison);
    echo '<center><h1> Page '.$saison.' <h1></center>';
    echo '<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    Spécifier Saison
  </button>
 </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="Saison.php?requestedSaison=Hiver">Hiver</a></li>
    <li><a class="dropdown-item" href="Saison.php?requestedSaison=Printemps">Printemps</a></li>
    <li><a class="dropdown-item" href="Saison.php?requestedSaison=Ete">Ete</a></li>
    <li><a class="dropdown-item" href="Saison.php?requestedSaison=Automne">Automne</a></li>
  </ul>
  
</div>';
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
    for($x=0;$x<count($recipies);$x++){
        echo $this->card($x,$recipies);     
    }
    echo ' </div></section>';
}
public function showNutrition(){
   $control=new RecipiesController();
   $ingredients= $control->getAllIngredients();
    echo '<center><h1> Page Nutrition <h1></center>';
    
   if(isset($_SESSION['admin'])){
    echo '<a type="button" href="AddNutrition.php" class="btn btn-secondary">Ajouter Ingredient</a>';
  
  echo '<table class="table">
  <thead>
    <tr>
      <th scope="col">Ingredient</th>
      <th scope="col">Healthy</th>
      <th scope="col">rend Healthy</th>
      <th scope="col">Modifier</th>
      <th scope="col">Supprimer</th>
    </tr>
  </thead>
  <tbody>';
  
   for($x=0;$x<count($ingredients);$x++){
        echo $this->nutritionrow($x,$ingredients);     
    }
   
    
    echo '</tbody>
</table>';  
 
  }else{
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-3">';
    for($x=0;$x<count($ingredients);$x++){
        echo $this->nutrition($x,$ingredients);     
    }
    echo ' </div></section>';
}}

public function showFilterdCategories($categorie,$arrayofFilters){
    $control=new RecipiesController();
    $proposed=$control->getUnvalidated();
    $recipies=$control->getFilterdRecipies($categorie,$arrayofFilters);
    echo '<center><h1> Page '.$categorie.' <h1></center>';
    
    echo '<div class="dropdown">';
    if(isset($_SESSION['admin'])){
      echo '<a type="button" href="AjouterRecette.php" class="btn btn-secondary">AjouterRecette</a>';
    }
  echo '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    Trié les Recettes Selon
  </button>
 </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=tcuisson&requestedCategorie='.$categorie.'">Temp cuisson</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=tpreparation&requestedCategorie='.$categorie.'">Temp Preparation</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=ttotal&requestedCategorie='.$categorie.'">Temp Total</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=notation&requestedCategorie='.$categorie.'">La Notation</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=nbCalorie&requestedCategorie='.$categorie.'">Nombre des Calories</a></li>
  </ul>
</div>';
echo '<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
    Filtrer les Recettes Selon
  </button>
 </button>
  <form method="POST" class="dropdown-menu"  action="PageCategorieFiltrer.php" aria-labelledby="dropdownMenuButton1">
  <input type="hidden" name="requestedCategorie" value="'.$categorie.'"> 
  <li><a class="dropdown-item" href="#">Temp Cuisson</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8" type="text" name="mintcuisson" placeholder="min"><input class="dropdown-item" type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  placeholder="max" name="maxtcuisson"></li>
    <li><a class="dropdown-item" href="#">Temp Preparation</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  name="mintpreparation" placeholder="min"><input class="dropdown-item" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  type="text" placeholder="max" name="maxtpreparation"></li>
    <li><a class="dropdown-item" href="#">Temp Total</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  name="minttotal" placeholder="min"><input class="dropdown-item" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  type="text" placeholder="max" name="maxttotal"></li>
   <li><a class="dropdown-item" href="#">Notation</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="number" placeholder="min note/5" min="0" max="5" name="minnotation"><input class="dropdown-item" type="number" placeholder="max note/5" min="0" max="5" name="maxnotation"></li>
    <li><a class="dropdown-item" href="#">Nombre de Calorie</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="number" placeholder="min" min="0"  name="mincalorie"><input class="dropdown-item" type="number" placeholder="max" min="0" name="maxcalorie"></li>
   <li class="text" id="timecontainer">
  <div class="form-check">
  <input class="form-check-input" type="radio" value="Hiver" name="flexRadioDefault" id="flexRadioDefault1">
  <label class="form-check-label" for="flexRadioDefault1">
    Hiver
  </label>
</div>
<div class="form-check">

  <input class="form-check-input" type="radio" value="Printemps" name="flexRadioDefault" id="flexRadioDefault2" >
  <label class="form-check-label" for="flexRadioDefault2">
    Printemps
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" value="Ete" name="flexRadioDefault" id="flexRadioDefault3">
  <label class="form-check-label" for="flexRadioDefault3">
    Ete
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" value="Automne" name="flexRadioDefault" id="flexRadioDefault4" >
  <label class="form-check-label" for="flexRadioDefault4">
    Automne
  </label>
</div>
</li>
 <button type="submit" class="btn btn-primary">Filtrer</button>
    <form/>
</div>';
if(isset($_SESSION['admin'])){
  echo '<table class="table">
  <thead>
    <tr>
      <th scope="col">Recette</th>
      <th scope="col">Valider</th>
      <th scope="col">operation 1</th>
      <th scope="col">operation 2</th>
      <th scope="col">operation 3</th>
    </tr>
  </thead>
  <tbody>';
  if(count($recipies)==0){
        echo '<p id="emptyMessage" >Pas de recette Comme Ca</p>';
     }
    else for($x=0;$x<count($recipies);$x++){
        echo $this->row($x,$recipies);     
    }
     for($x=0;$x<count($proposed);$x++){
        echo $this->row($x,$proposed);     
    }
    
    echo '</tbody>
</table>';
}else{
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
     if(count($recipies)==0){
        echo '<p id="emptyMessage" >Pas de recette Comme Ca</p>';
     }
    else for($x=0;$x<count($recipies);$x++){
        echo $this->card($x,$recipies);     
    }
    echo ' </div></section>';
}}
public function showSortedCategories($categorie,$criteria,$asc){
    $control=new RecipiesController();
     $proposed=$control->getUnvalidated();
    $recipies=$control->getSortedRecipies($categorie,$criteria,$asc);
    echo '<center><h1> Page '.$categorie.' <h1></center>';
    
    echo '<div class="dropdown">';
    if(isset($_SESSION['admin'])){
      echo '<a type="button" style="margin-right:5px;" href="AjouterRecette.php" class="btn btn-secondary">AjouterRecette</a>';
    }
  echo '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    Trié les Recettes Selon
  </button>
 </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=tcuisson&requestedCategorie='.$categorie.'">Temp cuisson</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=tpreparation&requestedCategorie='.$categorie.'">Temp Preparation</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=ttotal&requestedCategorie='.$categorie.'">Temp Total</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=notation&requestedCategorie='.$categorie.'">La Notation</a></li>
    <li><a class="dropdown-item" href="PageCategorie.php?requestedCriteria=nbCalorie&requestedCategorie='.$categorie.'">Nombre des Calories</a></li>
  </ul>
</div>';
echo '<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
    Filtrer les Recettes Selon
  </button>
 </button>
  <form method="POST" class="dropdown-menu"  action="PageCategorieFiltrer.php" aria-labelledby="dropdownMenuButton1">
   <li><a class="dropdown-item" href="#">Temp Cuisson</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8" type="text" name="mintcuisson" placeholder="min"><input class="dropdown-item" type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  placeholder="max" name="maxtcuisson"></li>
    <li><a class="dropdown-item" href="#">Temp Preparation</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  name="mintpreparation" placeholder="min"><input class="dropdown-item" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  type="text" placeholder="max" name="maxtpreparation"></li>
    <li><a class="dropdown-item" href="#">Temp Total</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  name="minttotal" placeholder="min"><input class="dropdown-item" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" minlength="8" maxlength="8"  type="text" placeholder="max" name="maxttotal"></li>
   <li><a class="dropdown-item" href="#">Notation</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="number" placeholder="min note/5" min="0" max="5" name="minnotation"><input class="dropdown-item" type="number" placeholder="max note/5" min="0" max="5" name="maxnotation"></li>
    <li><a class="dropdown-item" href="#">Nombre de Calorie</a></li>
    <li class="text" id="timecontainer"><input class="dropdown-item" type="number" placeholder="min" min="0"  name="mincalorie"><input class="dropdown-item" type="number" placeholder="max" min="0" name="maxcalorie"></li>
   <li class="text" id="timecontainer">
  <div class="form-check">
  <input class="form-check-input" type="radio" value="Hiver" name="flexRadioDefault" id="flexRadioDefault1">
  <label class="form-check-label" for="flexRadioDefault1">
    Hiver
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" value="Printemps" name="flexRadioDefault" id="flexRadioDefault2" >
  <label class="form-check-label" for="flexRadioDefault2">
    Printemps
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" value="Ete" name="flexRadioDefault" id="flexRadioDefault3">
  <label class="form-check-label" for="flexRadioDefault3">
    Ete
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" value="Automne" name="flexRadioDefault" id="flexRadioDefault4" >
  <label class="form-check-label" for="flexRadioDefault4">
    Automne
  </label>
</div>
</li>
 <button type="submit" class="btn btn-primary">Filtrer</button>
    <form/>
</div>';
    if(isset($_SESSION['admin'])){
  echo '<table class="table">
  <thead>
    <tr>
      <th scope="col">Recette</th>
      <th scope="col">Valider</th>
      <th scope="col">operation 1</th>
      <th scope="col">operation 2</th>
      <th scope="col">operation 3</th>
    </tr>
  </thead>
  <tbody>';
  if(count($recipies)==0){
        echo '<p id="emptyMessage" >Pas de recette Comme Ca</p>';
     }
    else for($x=0;$x<count($recipies);$x++){
        echo $this->row($x,$recipies);     
    }
    for($x=0;$x<count($proposed);$x++){
        echo $this->row($x,$proposed);     
    }
    echo '</tbody>
</table>';
}else{
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
     if(count($recipies)==0){
        echo '<p id="emptyMessage" >Pas de recette Comme Ca</p>';
     }
    else for($x=0;$x<count($recipies);$x++){
        echo $this->card($x,$recipies);     
    }
    echo ' </div></section>';
}
}
public function showContact(){
    echo '<div class="contactcontainer">
    <div class="content">
      <div class="left-side">
        <div class="address details">
          <i class="fas fa-map-marker-alt"></i>
          <div class="topic">Address</div>
          <div class="text-one">Alger, Oued Smar 16309</div>
          <div class="text-two">Delly Brahim</div>
        </div>
        <div class="phone details">
          <i class="fas fa-phone-alt"></i>
          <div class="topic">Phone</div>
          <div class="text-one">0557 32 63 16</div>
        </div>
        <div class="email details">
          <i class="fas fa-envelope"></i>
          <div class="topic">Email</div>
          <div class="text-one">jm_bensalem@esi.com</div>
        </div>
      </div>
      <div class="right-side">
        <div class="topic-text">Envoyer un message</div>
        <p>Si vous rencontrez un problème avec une recette, ou si vous voulez en savoir plus sur une recette, n\'hésitez pas à nous contacter et donner votre avis.</p>
      <form method="post" action="mailto:jm_bensalem@esi.dz">
        <div class="input-box" id="sendername">
          <input type="text" placeholder="Entrez votre nom">
        </div>
        <div class="input-box" id="sendersubject">
          <input type="text" placeholder="Entrez votre sujet">
        </div>
        <div class="input-box" id="sendermail">
          <input type="text" placeholder="Entrez votre email">
        </div>
        <div class="input-box message-box" id="sendermessage">
          <textarea rows="4" cols="50" name="comment" form="usrform" placeholder="Entrez votre message">
</textarea>
        </div>
        <div class="contactbutton">
          <input type="button" value="Envoiyez-Nous" onclick="sendEmail()" >
        </div>
      </form>
    </div>
    </div>
  </div>';
}

public function checkcard($x,$recipies){
     echo '<div class="col-sm mb-4">
       <div class="ideeform form-check">
  <input class="form-check-input" type="checkbox" name="IngredientChoice[]" value="'.$recipies[$x]['nom'].'" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    '.$recipies[$x]['nom'].'
  </label>
</div>
    </div>';
}
public function showChoiceIngredients($array){
    $control=new RecipiesController();
    $recipies=$control->getAllIngredients();
    echo '<center><h1 id="choose" Page Idées de Recette <h1></center>';
      echo '<div class="IngredientChoice">';
      echo '<center><h1> Choisir les Ingredients Désirer <h1></center>';
        echo ' <form action="idee.php" id="ingredientchoice" method="POST" class="cardsdisplay"><div class="row row-cols-1 row-cols-md-7">';
    for($x=0;$x<count($recipies);$x++){
        echo $this->checkcard($x,$recipies);     
    }
    echo ' </div>
    <button type="submit"  class="btn btn-secondary" data-dismiss="modal">Rechercher</button>
    </form></div>';
     
    $matching=$control->SearchMatchRecipies($array);
      echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
    for($x=0;$x<count($matching);$x++){
        echo $this->card($x,$matching);     
    }
    echo ' </div></section>';
}
public function showlogin(){

echo '<!-- Pills navs -->

<!-- Pills navs -->

<!-- Pills content -->
<div class="logincontainer tab-content">
  <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
    <form id="loginform" action="Redirect.php" method="POST" >
      <!-- Email input -->
      <input type="hidden" name="location" value="login"/> 
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Email ou nom </label>
        <input type="text" id="loginName" name="mail" class="form-control" required/>
        
        
      </div>

      <!-- Password input -->
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Password</label>
        <input type="password" name="password" id="loginPassword" class="form-control" required/>
        
      </div>

      <!-- 2 column grid layout -->
      <div class="row mb-4">
        <div class="col-md-6 d-flex justify-content-center">
          <!-- Checkbox -->
          <div class="form-check mb-3 mb-md-0">
           
          </div>
        </div>

        <div class="col-md-6 d-flex justify-content-center">
          <!-- Simple link -->
          <a href="#!">Forgot password?</a>
        </div>
      </div>

      <!-- Submit button -->
      <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

      <!-- Register buttons -->
      <div class="text-center">
        <p>Not a member? <a  href="Signup.php">Register</a></p>
      </div>
    </form>
  </div>
  
  
<!-- Pills content -->';    
}

public function showSignup(){

echo '<!-- Pills navs -->

<!-- Pills navs -->

<!-- Pills content -->
<div class="logincontainer tab-content">
  <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
    <form id="loginform" action="Redirect.php" method="POST">
      <!-- Email input -->
      <input type="hidden" name="location" value="signup" /> 
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">nom </label>
        <input type="name" name="nom" id="loginName" class="form-control" required/>    
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">prénom </label>
        <input type="name" name="prenom" id="loginName" class="form-control" required/>    
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Email </label>
        <input type="email" id="loginName" name="mail" class="form-control" required/>    
      </div>
<div class="form-outline mb-4">
      <label class="form-label" for="loginName">sex </label>
        <div class="form-check">
  <input class="form-check-input" name="sex" value="m" type="radio" name="flexRadioDefault" id="flexRadioDefault1" />
  <label class="form-check-label" for="flexRadioDefault1">
    Masculin
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" name="sex" value="f" type="radio" name="flexRadioDefault" id="flexRadioDefault2" />
  <label class="form-check-label" for="flexRadioDefault2">
    Feminin
  </label>
</div>  
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Date Naissance</label>
        <input type="date" id="loginPassword" name="dateN" class="form-control" required/> 
      </div>
      <!-- Password input -->
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Password</label>
        <input type="password" id="loginPassword" name="password" class="form-control" required/> 
      </div>

      <!-- 2 column grid layout -->
      <div class="row mb-4">
        

       

      <!-- Submit button -->
      
      <button type="submit" id="signup" class="btn btn-primary btn-block mb-4">Sign up</button>

    </form>
  </div>';
  

}
public function showHealthyRecipies($nbCalories){
    $control=new RecipiesController();
    $recipies=$control->getHealthyRecipies($nbCalories);
    echo '<center><h1> Page Healthy <h1></center>';
    
echo '<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
    seuil des Calories
  </button>
 </button>
  <form method="POST" class="dropdown-menu"  action="Healthy.php" aria-labelledby="dropdownMenuButton1">
   <li><a class="dropdown-item" href="#">Nombre de Calorie</a></li>
    <input class="dropdown-item" type="number" placeholder="max" min="0" name="maxcalorie"></li>
   <li class="text" id="timecontainer">

 <button type="submit" class="btn btn-primary">Filtrer</button>
    <form/>
</div>';
     echo ' <section class="cardsdisplay "><div class="row row-cols-1 row-cols-md-4">';
    for($x=0;$x<count($recipies);$x++){
        echo $this->card($x,$recipies);     
    }
    echo ' </div></section>';
}
public function AddNutrition(){
  
  echo '<div class="logincontainer tab-content"><center><h1>Ajouter ou Modifier Ingredients</h1></center>
   <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
    <form id="loginform" action="Redirect.php" method="POST" enctype="multipart/form-data">
      <!-- Email input -->
      <input type="hidden" name="location" value="ajouteringredient" /> 
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Nom d\'ingredient</label>
        <input type="name" name="nom" id="loginName" class="form-control" required/>    
      </div>
      <div class="form-check">
  <input class="form-check-input" type="checkbox" name="healthy"  id="flexCheckDefault">
  <label class="form-label" for="flexCheckDefault">
    Healthy
  </label>
</div>
      <div class="form-outline mb-4">
<label class="form-label" for="loginPassword">Saison</label>
        <div class="form-check">
  <input class="form-check-input" type="checkbox" name="Saison[]" value="Hiver" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Hiver
  </label>
</div>
    <div class="form-check">
  <input class="form-check-input" type="checkbox" name="Saison[]" value="Printemps" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Printemps
  </label>
</div>
    <div class="form-check">
  <input class="form-check-input" type="checkbox" name="Saison[]" value="Ete" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Ete
  </label>
</div>
    <div class="form-check">
  <input class="form-check-input" type="checkbox" name="Saison[]" value="Automne" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Automne
  </label>
</div>
</div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Calorie</label>
     <input type="number" name="Calorie" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Protein</label>
     <input type="number" name="Protein" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Carb</label>
     <input type="number" name="Carb" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Leau</label>
     <input type="number" name="Leau" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Sucre</label>
     <input type="number" name="Sucre" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Sodium</label>
     <input type="number" name="Sodium" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Fer</label>
     <input type="number" name="Fer" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Potassium</label>
     <input type="number" name="Potassium" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Phosphore</label>
     <input type="number" name="Phosphore" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Magnesium</label>
     <input type="number" name="Magnesium" id="loginName" class="form-control" required/>  
       </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Zinc</label>
     <input type="number" name="Zinc" id="loginName" class="form-control" required/>  
       </div>
      <button type="submit" id="signup" class="btn btn-primary btn-block mb-4">Ajouter Recette</button>
    </form>
    </div>
  </div>';
}
public function AddNews(){
    echo '<div class="logincontainer tab-content"><center><h1>Ajouter Recette</h1></center>
   <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
    <form id="loginform" action="Redirect.php" method="POST" enctype="multipart/form-data">
      <!-- Email input -->
      <input type="hidden" name="location" value="ajouternews" /> 
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Titre de News</label>
        <input type="name" name="titre" id="loginName" class="form-control" required/>    
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Lien image</label>
     <input type="text" name="image" id="loginName" class="form-control" required/>  
       </div>
       <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Lien Video</label>
     <input type="text" name="video" id="loginName" class="form-control" required/>  
       </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Description Bref</label>
      <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
<div class="form-outline mb-4">
      <label class="form-label" for="loginName">Paragraph </label>
      <textarea class="form-control" name="paragraph" id="exampleFormControlTextarea1" rows="3"></textarea>
       </div>
      

      
      <button type="submit" id="signup" class="btn btn-primary btn-block mb-4">Ajouter Recette</button>

    </form>
    </div>
  </div>';
}
public function ajouterRecette(){
    echo '<div class="logincontainer tab-content"><center><h1>Ajouter Recette</h1></center>
   <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
    <form id="loginform" action="Redirect.php" method="POST" enctype="multipart/form-data">
      <!-- Email input -->
      <input type="hidden" name="location" value="ajouterrecette" /> 
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Titre de la recette</label>
        <input type="name" name="titre" id="loginName" class="form-control" required/>    
      </div>
      <div class="form-outline mb-4">
       <label class="form-label" for="loginName">Categorie</label>
        <select name="categorie" class="form-select" aria-label="Default select example">
  <option  selected>Categorie</option>
  <option  value="plat">Plat</option>
  <option  value="entree">Entrée</option>
  <option  value="boisson">Boisson</option>
  <option  value="desert">Dessert</option>
</select></div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Description Bref</label>
      <textarea class="form-control" name="ddescription" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
<div class="form-outline mb-4">
      <label class="form-label" for="loginName">Description </label>
      <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
       </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">temp cuisson</label>
        <input type="text" placeholder="hh:mm:ss" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" id="loginPassword" name="tcuisson" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">temp preparation</label>
        <input type="text" placeholder="hh:mm:ss" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" id="loginPassword" name="tpreparation" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">temp repos</label>
        <input type="text" placeholder="hh:mm:ss" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" id="loginPassword" name="trepos" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Notation</label>
        <input type="number" max="5" min="0" id="loginPassword" placeholder="/5" name="notation" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Image</label>
        <input type="text"  id="loginPassword" name="image" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
       <label class="form-label" for="loginPassword">defficulté</label>
        <select class="form-select" name="deficulte" aria-label="Default select example">
  <option selected>Difficulté</option>
  <option  value="Facile">Facile</option>
  <option value="Moyenne">Moyenne</option>
  <option  value="Difficile">Difficile</option>
</select></div>
<div class="form-outline mb-4">
<label class="form-label" for="loginPassword">fetes</label>
        <div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Achoura" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Achoura
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Mariage" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
    Mariage
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Mouloud" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
   Mouloud
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Ramdane" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
    Ramdane
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Circoncision" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
    Circoncision
  </label>
</div>
<div class="form-outline mb-4" >
      <label class="form-label" for="loginPassword">Entrez le nombre des  ingredients</label>
        <input type="text" id="nbIngredients" name="ingredints" class="form-control" required/> 
        </div>
        <button type="button" id="nbingredientsbtn" class="btn btn-primary btn-block mb-4">Ajouter</button>
<div class="form-outline mb-4" id="ingredientcontainer">
      <label class="form-label" for="loginPassword">Entrez le nom la quantité et l\'unité </label>
        </div>
<div class="form-outline mb-4" >
      <label class="form-label" for="loginPassword">Entrez le nombre des étapes</label>
        <input type="text" id="nbetapes"  class="form-control" required/> 
        </div>
<button type="button" id="nbetapesbtn" class="btn btn-primary btn-block mb-4">Ajouter</button>
<div class="form-outline mb-4" id="etapetcontainer">
      <label class="form-label" for="loginPassword">Entrez les étape</label>
        </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">estimation des Calories</label>
        <input type="number" id="loginPassword" name="nbcalorie" class="form-control" required/> 
      </div>
</div>

      <!-- 2 column grid layout -->
      <div class="row mb-4">

      <!-- Submit button -->
      
      <button type="submit" id="signup" class="btn btn-primary btn-block mb-4">Ajouter Recette</button>

    </form>
  </div>';
}

public function ModifierRecette($titre){
    echo '<div class="logincontainer tab-content"> 
    <center><h1>Modifier '.$titre.'</h1></center><div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
    <form id="loginform" action="Redirect.php" method="POST" enctype="multipart/form-data">
      <!-- Email input -->
      <input type="hidden" name="location" value="ajouterrecette" /> 
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Titre de la recette</label>
        <input type="name" name="titre" id="loginName" class="form-control" required/>    
      </div>
      <div class="form-outline mb-4">
       <label class="form-label" for="loginName">Categorie</label>
        <select name="categorie" class="form-select" aria-label="Default select example">
  <option  selected>Categorie</option>
  <option  value="plat">Plat</option>
  <option  value="entree">Entrée</option>
  <option  value="boisson">Boisson</option>
  <option  value="desert">Dessert</option>
</select></div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginName">Description Bref</label>
      <textarea class="form-control" name="ddescription" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
<div class="form-outline mb-4">
      <label class="form-label" for="loginName">Description </label>
      <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
       </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">temp cuisson</label>
        <input type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" id="loginPassword" name="tcuisson" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">temp preparation</label>
        <input type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" id="loginPassword" name="tpreparation" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">temp repos</label>
        <input type="text" pattern="[0-9][0-9]:[0-9][0-9]:[0-9][0-9]" id="loginPassword" name="trepos" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Notation</label>
        <input type="number" max="5" min="0" id="loginPassword" name="notation" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">Image</label>
        <input type="text"  id="loginPassword" name="image" class="form-control" required/> 
      </div>
      <div class="form-outline mb-4">
       <label class="form-label" for="loginPassword">defficulté</label>
        <select class="form-select" name="deficulte" aria-label="Default select example">
  <option selected>Difficulté</option>
  <option  value="Facile">Facile</option>
  <option value="Moyenne">Moyenne</option>
  <option  value="Difficile">Difficile</option>
</select></div>
<div class="form-outline mb-4">
<label class="form-label" for="loginPassword">fetes</label>
        <div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Achoura" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Achoura
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Mariage" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
    Mariage
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Mouloud" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
   Mouloud
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Ramdane" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
    Ramdane
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="fetes[]" value="Circoncision" id="flexCheckChecked" checked>
  <label class="form-check-label" for="flexCheckChecked">
    Circoncision
  </label>
</div>
<div class="form-outline mb-4" >
      <label class="form-label" for="loginPassword">Entrez le nombre des  ingredients</label>
        <input type="text" id="nbIngredients" name="ingredints" class="form-control" required/> 
        </div>
        <button type="button" id="nbingredientsbtn" class="btn btn-primary btn-block mb-4">Ajouter</button>
<div class="form-outline mb-4" id="ingredientcontainer">
      <label class="form-label" for="loginPassword">Entrez le nom la quantité et l\'unité </label>
        </div>
<div class="form-outline mb-4" >
      <label class="form-label" for="loginPassword">Entrez le nombre des étapes</label>
        <input type="text" id="nbetapes"  class="form-control" required/> 
        </div>
<button type="button" id="nbetapesbtn" class="btn btn-primary btn-block mb-4">Ajouter</button>
<div class="form-outline mb-4" id="etapetcontainer">
      <label class="form-label" for="loginPassword">Entrez les étape</label>
        </div>
        <div class="form-outline mb-4">
      <label class="form-label" for="loginPassword">estimation des Calories</label>
        <input type="number" id="loginPassword" name="nbcalorie" class="form-control" required/> 
      </div>
</div>

      <!-- 2 column grid layout -->
      <div class="row mb-4">

      <!-- Submit button -->
      
      <button type="submit" id="signup" class="btn btn-primary btn-block mb-4">Ajouter Recette</button>

    </form>
  </div>';
}
}


?>
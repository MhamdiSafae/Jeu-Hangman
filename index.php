<?php
session_start();
echo "<html><head><title>Jeu du Pendu</title><link rel='stylesheet' type='text/css' href='style.css'></head></html>";

$lettre_essay = array();

if (!isset($_SESSION['letter_essaye']) ) {
    $_SESSION['letter_essaye']=$lettre_essay;
    $_SESSION['tentativeRestante']=6;
    $_SESSION['Mo_A_trouver']='';
    $_SESSION['motLength']=0;
    $_SESSION['startTime']=0;
   $_SESSION['gagner']=false;
   $_SESSION['endTime']=0;
}

if (isset($_POST['username'])&& isset($_POST['category'])) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['category'] = $_POST['category'];
    $fichier = ""; 
  if ($_SESSION['category']  == "pays") {
    $fichier = "BD/country.txt";
  } elseif ($_SESSION['category']  == "animal") {
    $fichier = "BD/animal.txt";
  } elseif ($_SESSION['category']  == "sport") {
    $fichier = "BD/sport.txt";
  }
  if ($fichier != "") {
    $mots = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $mot_aleatoire = $mots[array_rand($mots)];
    $wordToFind = $words[array_rand($mots)];
    $wordLength = strlen($mot_aleatoire );
    $_SESSION['Mo_A_trouver'] = $mot_aleatoire ;
    $_SESSION['motLength'] = $wordLength;
    $_SESSION['mot'] = array_fill(0, $_SESSION['motLength'], '_');
    $startTime = time();
    $_SESSION['startTime'] =$startTime ;
    
}
header("location:game.php");
}

echo '<h1>Jeu de Pendu</h1>';

    echo '<form method="post" style="width:680px;
    margin:0 auto;
    margin-top:4%;">';
    echo '<label >Entrez votre nom dutilisateur:</label>';
    echo '<input type="text" name="username"id="username" value="';if(isset($_SESSION['username'])){echo $_SESSION['username'];}echo'"><br>';
    echo "<label for='category'>Choisissez une catégorie:</label> <br>";
    echo'<label><input type="radio" name="category" value="pays"';
    if(isset($_SESSION['category']) && $_SESSION['category']=="pays"){
      echo 'checked';
    } echo'> Pays</label><br>';
    echo'<br>';
    echo'<label><input type="radio" name="category" value="animal" ';
    if(isset($_SESSION['category']) && $_SESSION['category']=="animal"){
      echo 'checked';
    } echo'> Animal</label><br>';
    echo'<br>';
    echo'<label><input type="radio" name="category" value="sport"';
    if(isset($_SESSION['category']) && $_SESSION['category']=="sport"){
      echo 'checked';
    } echo'> Sport</label><br>';
    echo'<br>';
    echo '<button type="submit" class="btn">Soumettre</button>';
    echo "</form>";

function afficher()
{
    $filename = 'data.txt';

    // Vérifier si le fichier existe
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $lines = explode("\n", $content);
        echo '<table >';
        echo '<thead><tr>
                    <th scope="col">Username</th><th scope="col">Catégorie</th><th scope="col">Mot</th>
                    <th scope="col">Times</th><th scope="col">Nombre des Erreurs</th></tr></thead>';
        foreach ($lines as $line) {
            $row = explode(',', $line);
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo 'Aucune donnée à afficher.';
    }
}
echo "<h2>Tableau des scores</h2>";
afficher();
?>
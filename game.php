<?php

session_start();
echo "<html><head><title>Jeu du Pendu</title><link rel='stylesheet' type='text/css' href='style.css'></head></html>";
if (isset($_POST['rejouer'])) {
    $lettre_essay = array();
        $_SESSION['letter_essaye']=$lettre_essay;
        $_SESSION['tentativeRestante']=6;
        $_SESSION['Mo_A_trouver']='';
        $_SESSION['motLength']=0;
        $_SESSION['startTime']=0;
       $_SESSION['gagner']=false;
       $_SESSION['endTime']=0;
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
  
    $wordLength = strlen($mot_aleatoire );
    $_SESSION['Mo_A_trouver'] = $mot_aleatoire ;
    $_SESSION['motLength'] = $wordLength;
    $_SESSION['mot'] = array_fill(0, $_SESSION['motLength'], '_');
    $startTime = time();
    $_SESSION['startTime'] =$startTime ;}}
if(!isset($_SESSION['username'])&& !isset($_SESSION['category'] )){header("location:index.php");}
if (isset($_POST['letter'])) {
    $letter = strtolower($_POST['letter']);
    

if (!in_array($letter, $_SESSION['letter_essaye'])) {
    $_SESSION['letter_essaye'][] = $letter;
    if (strpos(strtoupper($_SESSION['Mo_A_trouver']), strtoupper($letter)) !== false) {
    
        for ($i = 0; $i < $_SESSION['motLength'] ; $i++) {
            if (strtoupper($_SESSION['Mo_A_trouver'][$i]) == strtoupper($letter)) {
                $_SESSION['mot'][$i] = $_SESSION['Mo_A_trouver'][$i];
            }
        }
    } else {
        $_SESSION['tentativeRestante']--;
    }


}}

if ((isset($_SESSION['mot']) && !in_array('_', $_SESSION['mot'])) ) {
    $_SESSION['gagner'] = true;
    $_SESSION['endTime'] = time();
}

    echo '<div class="body">';
    echo "<p><b>Bonjour <span>" . $_SESSION['username'] . "</span>!Essayer de deviner un mot qui appartient à la catégorie <span>".$_SESSION['category'] . "</span> </p>";
    echo "<p>Nombre de caractères dans le mot: <span>" . $_SESSION['motLength'] . "</span></p>";
    echo "<p>Nombre d'erreurs autorisées: <span>6</span> </p>";
    switch ($_SESSION['tentativeRestante']) {
        case 6:
            echo "<img src='img/hangman-0.png'>";
            break;
        case 5:
            echo "<img src='img/hangman-1.png'>";
            break;
        case 4:
            echo "<img src='img/hangman-2.png'>";
            break;
        case 3:
            echo "<img src='img/hangman-3.png'>";
            break;
        case 2:
            echo "<img src='img/hangman-4.png'>";
            break;
        case 1:
            echo "<img src='img/hangman-5.png'>";
            break;
        default:
            echo "<img src='img/hangman-6.png'>";
            break;
    }
 
    echo "<p>Mot à trouver: ";
    for ($i = 0; $i < $_SESSION['motLength']; $i++) {
        if ($_SESSION['Mo_A_trouver'][$i] == ' ') {
            echo "<span style='font-size:60px'>&nbsp;</span>";
        } elseif ($_SESSION['mot'][$i] == '_') {
            echo "<button type='button' class='btndisabled' disabled>_</button>";
        } else {
            echo "<button type='button' class='btndisabled' disabled>" . $_SESSION['mot'][$i] . "</button>";
        }
    }

echo "</p>";

    echo "<p>Il vous reste <span>" . $_SESSION['tentativeRestante'] . "</span> essai(s)</b></p>";
    echo "<form method='post'>";



for ($i = 0; $i < 26; $i++) {
    $letter = chr(97 + $i);
    if (in_array($letter, $_SESSION['letter_essaye']) || $_SESSION['gagner']) {
        echo "<button type='button' disabled>" . $letter . "</button>";
    } elseif ($_SESSION['tentativeRestante'] > 0) {
        echo "<button type='submit' name='letter' class='letr' value='" . $letter . "'>" . $letter . "</button>";
    } else {
        echo "<button type='button' disabled>" . $letter . "</button>";
    }
}
echo "</form>";

if ($_SESSION['gagner']) {
echo "<p style='color:green'><b>Félicitations, vous avez trouvé le mot '" . $_SESSION['Mo_A_trouver'] . "' en " . forme_Time($_SESSION['endTime'] - $_SESSION['startTime']) . "!</p>";
echo "<form method='post'>";
echo "<button type='submit'class='btn' name='rejouer'>Rejouer</button>";
echo "</form>";
$data = array(
    'username' => $_SESSION['username'],
    'category'=> $_SESSION['category'],
    'word' => $_SESSION['Mo_A_trouver'],
    'time' => forme_Time($_SESSION['endTime'] - $_SESSION['startTime']),
    'erreur'=> 6- $_SESSION['tentativeRestante'],
);
Enregistrer($data);

} elseif($_SESSION['tentativeRestante']==0) {
    $_SESSION['endTime'] = time();
echo "<p style='color:red'><b>Désolé, vous avez perdu! Le mot était '" . $_SESSION['Mo_A_trouver']. "'.<br>
Time:".forme_Time($_SESSION['endTime'] - $_SESSION['startTime'])."</p> ";

echo "<form method='post'>";
echo "<button type='submit' class='btn' name='rejouer'>Rejouer</button>";
echo "</form>";
}
echo "</div>";

function forme_Time($time)
{
    return gmdate('H:i:s', $time);
}

function Enregistrer($data)
{
    $filename = 'data.txt';
    $line = implode(',', $data) . "\n";
    file_put_contents($filename, $line, FILE_APPEND);
}
?>
<?php
include ('addcategorie.html');
session_start();
if (($_SESSION['username'])==0) {
    header('Location: login.php');
     exit(); 
 }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $nom = $_POST['nom'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $sql = "INSERT INTO categorie (Nom, Description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $nom, $description);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            header('Location: gerercategorie.php');
        } else {
            //echo "Erreur lors de l'ajout du produit.". $stmt->error;
        }
        $stmt->close();
        $conn->close();
    } else {
        //echo "Erreur dans la préparation de la requête d'insertion.";
    }
} else {
    //echo "Les données du formulaire n'ont pas été soumises correctement.";
}
?>


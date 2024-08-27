<?php
session_start();
if (($_SESSION['username'])==0) {
    header('Location: login.php');
     exit(); 
 }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $codepostal = $_POST['codepostal'];
    $type_paiement = $_POST['type_paiement'];
    

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    /*$sql_update = "UPDATE panier SET valide = 1 WHERE id_user = ?";
        $stmt = $conn->prepare($sql_update);
    
        if ($stmt) {
            $stmt->bind_param("i", $_SESSION['userid']);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                echo "Commande validée avec succès.";
            } else {
                echo "La validation de la commande a échoué.";
            }
            $stmt->close();
        } else {
            echo "Erreur dans la préparation de la requête de mise à jour.";
        }*/ 
        
$sql_max_idcommande = "SELECT MAX(ID_commande) AS max_idcommande FROM commande";
$stmt_max_idcommande = $conn->prepare($sql_max_idcommande);

if ($stmt_max_idcommande) {
    $stmt_max_idcommande->execute();
    $result_max_idcommande = $stmt_max_idcommande->get_result();

    if ($result_max_idcommande && $result_max_idcommande->num_rows > 0) {
        $row_max_idcommande = $result_max_idcommande->fetch_assoc();
        $max_idcommande = $row_max_idcommande['max_idcommande'];
        echo "La valeur maximale de idcommande est : " . $max_idcommande;
    } else {
        echo "Aucune valeur trouvée pour idcommande.";
    }
    $stmt_max_idcommande->close();
} else {
    echo "Erreur dans la préparation de la requête pour obtenir la valeur maximale de idcommande.";
}

$max_idcommande+=1;
$sql_update = "UPDATE panier SET valide = 1, idcommande = ? WHERE id_user = ? AND valide = 0";
$stmt0 = $conn->prepare($sql_update);
if ($stmt0) {
    $stmt0->bind_param("ii",$max_idcommande, $_SESSION['userid']);
    $stmt0->execute();

    if ($stmt0->affected_rows > 0) {
        echo "Commande validée avec succès.";
    } else {
        echo "La validation de la commande a échoué ou aucun produit à valider.";
    }
    $stmt0->close();
} else {
    echo "Erreur dans la préparation de la requête de mise à jour.";
}

    $total=$_SESSION['totalpayer'];
    $date_commande = date('Y-m-d H:i:s'); 
    /*$sql = "INSERT INTO commande (date_commande,adresse,id_user,telephone,codepost,type,Total,ID_command) VALUES (?, ?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssiiissi",$date_commande,$adresse,$_SESSION['userid'],$telephone,$codepostal,$type_paiement,$total,$max_idcommande);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //header('Location: gerercategorie.php');
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
} */
$sql_insert_commande = "INSERT INTO commande (ID_commande,date_commande, adresse, id_user, telephone, codepost, type, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert_commande = $conn->prepare($sql_insert_commande);

if ($stmt_insert_commande) {
    $stmt_insert_commande->bind_param("issiddid", $max_idcommande, $date_commande, $adresse, $_SESSION['userid'], $telephone, $codepostal, $type_paiement, $total);
    $stmt_insert_commande->execute();

    if ($stmt_insert_commande->affected_rows > 0) {
        echo "Commande insérée avec succès dans la table 'commande'.";
    } else {
        echo "Erreur lors de l'insertion dans la table 'commande' : " . $stmt_insert_commande->error;
    }
    $stmt_insert_commande->close();
} else {
    echo "Erreur dans la préparation de la requête d'insertion dans la table 'commande'.";
}
header("Location: useraccueil.php");}  

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Commande</title>
    <link rel="stylesheet" href="usercommande.css">
</head>
<body>
    <div class="login-box">
        <h2>Formulaire de Commande</h2>
        <form action="usercommande.php" method="POST">
            <div class="user-box">
                <input type="text" name="adresse" required>
                <label>Adresse</label>
            </div>
            <div class="user-box">
                <input type="number" name="telephone" required>
                <label>Numéro de Téléphone</label>
            </div>
            <div class="user-box">
                <input type="number" name="codepostal" required>
                <label>Code postal</label>
            </div>
            <div class="user-box">
                <select name="type_paiement" required>
                    <option value="check">Par Chèque</option>
                    <option value="especes">En Espèces</option>
                </select>
            </div>
            <div class="btn-container">
                <button href='useraccueil.php' type="submit" class="btn-normal">Valider la Commande</button>
            </div>
        </form>
    </div>
</body>
</html>
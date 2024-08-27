<?php
session_start();

if (isset($_GET['reference'])) {
    $codecat = $_GET['reference'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $sql = "SELECT * FROM categorie WHERE Code_Categorie = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $codecat);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $categories = $result->fetch_assoc();
        } else {
            echo "Aucun produit trouvé avec ce code.";
        }

        $stmt->close();
    } else {
        echo "Erreur dans la préparation de la requête de récupération des données du produit.";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_description = $_POST['description'];
        $new_nom = $_POST['nom'];
        

        // Mettre à jour les valeurs dans la base de données
        $update_sql = "UPDATE categorie SET Nom=?, Description=? WHERE Code_Categorie=?";
        $update_stmt = $conn->prepare($update_sql);

        if ($update_stmt) {
            $update_stmt->bind_param("sss", $new_nom, $new_description, $codecat);
            $update_stmt->execute();

            if ($update_stmt->affected_rows > 0) {
                header('Location: gerercategorie.php');
                //echo "Produit mis à jour avec succès.";
                // Réactualiser les données du produit après la mise à jour
                $categories = [
                    'Description' => $new_description,
                    'Nom' => $new_nom,
                ];
            } else {
                //echo "La mise à jour du produit a échoué.";
            }

            $update_stmt->close();
        } else {
            //echo "Erreur dans la préparation de la requête de mise à jour du produit.";
        }
    }

    $conn->close();
} else {
    //echo "Code du produit non trouvé dans la session.";
}
?>

<main>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Bootstrap Simple Data Table</title>
<link rel="stylesheet" type="text/css" href="../signin/signin.css">
<!--link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script-->

<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
</head>
<main>
    <div class="login-box">
        <h2 class="mt-4">Modifier cette catégorie:</h2>
        <form action="categorieupdate.php?reference=<?php echo $codecat; ?>" method="POST" enctype="multipart/form-data">
            <!-- Pré-remplir le formulaire avec les données existantes du produit -->
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo isset($categories['Nom']) ? $categories['Nom'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo isset($categories['Description']) ? $categories['Description'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn-normal">Modifier</button>
        </form>
    </div>
</main>

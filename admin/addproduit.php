<?php
session_start();
if (($_SESSION['username'])==0) {
   header('Location:../signin/signin.php');
    exit(); 
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $designation = $_POST['designation'];
    $qte = $_POST['qte'];
    $prix = $_POST['prix'];
    $categorie = $_POST['categorie'];
    $Disponibilite = 1;
    $promotion = $_POST['promotion'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $file_destination = ""; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_destination = "inc/images/"    . $file_name;

        if (move_uploaded_file($file_tmp_name, $file_destination)) {
            // Le téléchargement et le déplacement du fichier se sont bien déroulés
        } else {
            echo "Le déplacement du fichier a échoué.";
            exit();
        }
    } else {
        echo "Aucun fichier image n'a été téléchargé ou une erreur est survenue.";
    }

    $sql = "INSERT INTO produit (Designation, Quantite, Prix, Image, Disponibilite, Promotion, Code_Cat) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sddsiss", $designation, $qte, $prix, $file_destination, $Disponibilite, $promotion, $categorie);

        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header('Location: gererproduit.php');
        } else {
            //echo "Erreur lors de l'ajout du produit : " . $stmt->error;
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
        <h2 class="mt-4">Ajouter un produit</h2>
        <form action="addproduit.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="designation">Produit :</label>
                <input type="text" class="form-control" id="designation" name="designation" required>
            </div>
            <div class="form-group">
                <label for="qte">Quantité en stock :</label>
                <input type="number" class="form-control" id="qte" name="qte" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
            </div>
            <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" name="image" accept="image/*"><br>
        </div>
            <div class="form-group">
                <label for="categorie">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie" required>
                <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "projet";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("La connexion a échoué : " . $conn->connect_error);
                    }

                    $sql = "SELECT Code_Categorie, Nom FROM categorie";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['Code_Categorie'] . "'>" . $row['Nom'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucune catégorie trouvée</option>";
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="promotion">Promotion:</label>
                <input type="number" class="form-control" id="promotion" name="promotion" required>
            </div>
            <button type="submit" class="btn-normal">Ajouter</button>
        </form>
    </div>
</main>
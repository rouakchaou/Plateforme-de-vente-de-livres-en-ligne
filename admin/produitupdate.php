<?php
session_start();

if (isset($_GET['reference'])) {
     $reference = $_GET['reference'];  

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $sql = "SELECT * FROM produit WHERE reference = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $reference);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $produit = $result->fetch_assoc();
        } else {
            echo "Aucun produit trouvé avec ce code.";
        }

        $stmt->close();
    } else {
        echo "Erreur dans la préparation de la requête de récupération des données du produit.";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les nouvelles valeurs du formulaire
        $new_designation = $_POST['designation'];
        $new_qte = $_POST['qte'];
        $new_prix = $_POST['prix'];
        $new_categorie = $_POST['categorie'];
        $new_disponibilite = $_POST['disponibilite'];
        $new_promotion = $_POST['promotion'];

        // Mettre à jour les valeurs dans la base de données
        $update_sql = "UPDATE produit SET Designation=?, Quantite=?, Prix=?, Disponibilite=?, Promotion=?, Code_Cat=? WHERE reference=?";
        $update_stmt = $conn->prepare($update_sql);

        if ($update_stmt) {
            $update_stmt->bind_param("sdsiiis", $new_designation, $new_qte, $new_prix, $new_disponibilite, $new_promotion, $new_categorie, $reference);
            $update_stmt->execute();

            if ($update_stmt->affected_rows > 0) {
                header('Location: gererproduit.php');
                //echo "Produit mis à jour avec succès.";
                // Réactualiser les données du produit après la mise à jour
                $produit = [
                    'Designation' => $new_designation,
                    'Quantite' => $new_qte,
                    'Prix' => $new_prix,
                    'Disponibilite' => $new_disponibilite,
                    'Promotion' => $new_promotion,
                    'Code_Cat' => $new_categorie
                ];
            } else {
                echo "La mise à jour du produit a échoué.";
            }

            $update_stmt->close();
        } else {
            echo "Erreur dans la préparation de la requête de mise à jour du produit.";
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
        <h2 class="mt-4">Modifier ce produit:</h2>
        <form action="produitupdate.php?reference=<?php echo $reference; ?>" method="POST" enctype="multipart/form-data">
            
            <!-- Pré-remplir le formulaire avec les données existantes du produit -->
            <div class="form-group">
                <label for="designation">Produit :</label>
                <input type="text" class="form-control" id="designation" name="designation" value="<?php echo isset($produit['Designation']) ? $produit['Designation'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="qte">Quantité en stock :</label>
                <input type="number" class="form-control" id="qte" name="qte" value="<?php echo isset($produit['Quantite']) ? $produit['Quantite'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="<?php echo isset($produit['Prix']) ? $produit['Prix'] : ''; ?>" required>
            </div>
            <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" name="image" accept="image/*"><br>
        </div>
            <div class="form-group">
                <label for="categorie">Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie" value="<?php echo isset($produit['Code_Cat']) ? $produit['Code_Cat'] : ''; ?>" required>
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
                <input type="number" class="form-control" id="promotion" name="promotion" value="<?php echo isset($produit['Code_Cat']) ? $produit['Promotion'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="disponibilite">Disponibilite:</label>
                <input type="number" class="form-control" id="disponibilite" name="disponibilite" value="<?php echo isset($produit['Promotion']) ? $produit['Promotion'] : ''; ?>" required>
            </div>

            <button type="submit" class="btn-normal">Modifier</button>
        </form>
    </div>
</main>

<?php
session_start();
if (($_SESSION['username'])==0) {
   header('Location:../signin/signin.php');
    exit(); 
}
require 'inc\header.php';
?>
<?php 
$_SESSION['messagecommandeadmin']="";
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "projet"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (isset($_GET['reference'])) {
        $code_com = $_GET['reference'];
    } else {
        echo "La référence de la commande n'est pas définie.";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['choix'])) {
    if (isset($_GET['reference'])) {
        $code_com = $_GET['reference'];
        $choix = $_POST['choix'];
        if ($choix == 'annuler'){
        $sql_delete_commande = "DELETE FROM commande WHERE ID_commande = ?";
    $stmt_commande = $conn->prepare($sql_delete_commande);
    if ($stmt_commande) {
        $stmt_commande->bind_param("s", $code_com);
        $stmt_commande->execute();
        if ($stmt_commande->affected_rows > 0) {
            $sql_delete_panier = "DELETE FROM panier WHERE idcommande = ?";
            $stmt_panier = $conn->prepare($sql_delete_panier);
            if ($stmt_panier) {
                $stmt_panier->bind_param("s", $code_com);
                $stmt_panier->execute();
                if ($stmt_panier->affected_rows > 0) {
                    //echo "La commande et les éléments du panier ont été supprimés avec succès.";
                } else {
                    //echo "La suppression des éléments du panier a échoué." .$stmt_panier->error;
                }
                $stmt_panier->close();
            } else {
                //echo "Erreur dans la préparation de la requête de suppression du panier.";
            }
        } else {
            //echo "La suppression de la commande a échoué." .$stmt_commande->error;
        }
        $stmt_commande->close();
    } else {
        //echo "Erreur dans la préparation de la requête de suppression de la commande.";
    }
    $_SESSION['messagecommandeadmin']="commande annulée";
}
    else {
        $updates = array();
        $valide=1;
        $sql_select_produits = "SELECT id_produit, quantite FROM panier WHERE idcommande = ?";
        $stmt_produits = $conn->prepare($sql_select_produits);
        if ($stmt_produits) {
            $stmt_produits->bind_param("i", $code_com);
            $stmt_produits->execute();
            $result_produits = $stmt_produits->get_result();

            while ($row_produit = $result_produits->fetch_assoc()) {
                $id_produit = $row_produit['id_produit'];
                $quantite_panier = $row_produit['quantite'];
                $sql_select_quantite_produit = "SELECT Quantite FROM produit WHERE reference = ?";
                $stmt_quantite_produit = $conn->prepare($sql_select_quantite_produit);
                if ($stmt_quantite_produit) {
                    $stmt_quantite_produit->bind_param("i", $id_produit);
                    $stmt_quantite_produit->execute();
                    $result_quantite_produit = $stmt_quantite_produit->get_result();
                    
                    if ($result_quantite_produit->num_rows > 0) {
                        $row_quantite_produit = $result_quantite_produit->fetch_assoc();
                        $quantite_produit = $row_quantite_produit['Quantite'];
                        
                        if (is_numeric($quantite_panier) && is_numeric($quantite_produit)) {
                            if ($quantite_produit - $quantite_panier>=0) {
                            $updates[$id_produit] = $quantite_produit - $quantite_panier;  
                            }
                            else {$valide=0;
                            }
                        }
                    } else {
                        //echo "Produit non trouvé dans la table des produits.";  
                    }
                    $stmt_quantite_produit->close();
                } else {
                    //echo "Erreur dans la préparation de la requête pour sélectionner la quantité du produit.";
                }
            }
            if ($valide==1){
                $usercoo =[];
                    $sql = "SELECT *
                    FROM users u
                    INNER JOIN commande c ON u.ID = c.id_user
                    WHERE c.ID_commande =  ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $code_com);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $usercoo = $result->fetch_assoc();
        } else {
            echo "Aucun produit trouvé avec ce code.";
        }
        $stmt->close();
    } else {
        echo "Erreur dans la préparation de la requête de récupération des données du produit.";
    }
            foreach ($updates as $id_produit => $quantite_difference) {
                $sql_update_quantite_produit = "UPDATE produit SET Quantite =? WHERE Reference = ?";
                $stmt_update_quantite_produit = $conn->prepare($sql_update_quantite_produit);

                if ($stmt_update_quantite_produit) {
                    $stmt_update_quantite_produit->bind_param("ii", $quantite_difference, $id_produit);
                    $stmt_update_quantite_produit->execute();
                    if ($stmt_update_quantite_produit->affected_rows > 0) {
                        //echo "La quantité du produit ID " . $id_produit . " a été mise à jour.";
                    } else {
                        //echo "Échec de la mise à jour de la quantité du produit ID " . $id_produit . ".";
                    }
                    $stmt_update_quantite_produit->close();
                } else {
                    //echo "Erreur dans la préparation de la requête de mise à jour de la quantité du produit.";
                }
            }
                $sql_update = "UPDATE panier SET valideadmin = 1 WHERE idcommande = ?";
                $stmt0 = $conn->prepare($sql_update);
                if ($stmt0) {
                    $stmt0->bind_param("i",$code_com);
                    $stmt0->execute();
                    if ($stmt0->affected_rows > 0) {
                    } else {
                        //echo "La validation de la commande a échoué ou aucun produit à valider.";
                    }
                    $_SESSION['messagecommandeadmin']="Commande validée avec succès.";
                    header('Location: validation.php?e=' . urlencode($usercoo['Email']));
                    exit();   
                    $stmt0->close();
                } else {
                    //echo "Erreur dans la préparation de la requête de mise à jour.";
                }
        }
            else {
                //echo"Quantité invalide dans le stock!";
                $sql_delete = "DELETE FROM panier WHERE idcommande = ?";
        $stmt = $conn->prepare($sql_delete);
        if ($stmt) {
            $stmt->bind_param("i", $code_com);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                //echo "Le produit a été supprimé avec succès.";
            } else {
                //echo "La suppression du produit a échoué." .$stmt->error;
            }
            $stmt->close();
        } else {
            //echo "Erreur dans la préparation de la requête de suppression.";
        }
        $sqlco_delete = "DELETE FROM commande WHERE ID_commande = ?";
        $stmt = $conn->prepare($sqlco_delete);
        if ($stmt) {
            $stmt->bind_param("s", $code_com);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                //echo "Le produit a été supprimé avec succès.";
            } else {
                //echo "La suppression du produit a échoué." .$stmt->error;
            }
            $stmt->close();
        } else {
            //echo "Erreur dans la préparation de la requête de suppression.";
        }
        $_SESSION['messagecommandeadmin']="Quantité invalide dans le stock pour la commande dont l'identifiant est : ".$code_com;
            }
            $stmt_produits->close();
        } else {
            //echo "Erreur dans la préparation de la requête pour sélectionner les produits du panier.";
        }
    }
    header("Location: gerercommande.php");
        exit();
    }
}
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
    $sql = "SELECT * FROM panier WHERE idcommande = ? AND valide = ? AND valideadmin=0";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $reference = $_GET['reference'];
    $valide = 1;
    $stmt->bind_param("ii", $reference, $valide);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $commandes = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $commandes = [];
    }
    $stmt->close();
} else {
    echo "Erreur dans la préparation de la requête.";
}
?>
<main>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Détails de la commande</title>
<link rel="stylesheet" type="text/css" href="inc/admin.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
    body {
    color: #566787;
    background: rgb(186, 220, 248);
    font-family: 'Times New Roman', Times, serif;
}

.table-responsive {
    margin: 30px 0;
}
.table-wrapper {
    min-width: 900px;
    background: #fff;
    padding: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.table-title {
    padding-bottom: 10px;
    margin: 0 0 10px;
    min-width: 100%;
}
.table-title h2 {
    margin: 8px 0 0;
    font-size: 22px;
}
.search-box {
    position: relative;        
    float: right;
}
.search-box input {
    height: 34px;
    border-radius: 20px;
    padding-left: 35px;
    border-color: #ddd;
    box-shadow: none;
}
.search-box input:focus {
    border-color: #3FBAE4;
}
.search-box i {
    color: #a0a5b1;
    position: absolute;
    font-size: 19px;
    top: 8px;
    left: 10px;
}
table.table tr th, table.table tr td {
    border-color: #e9e9e9;
}
table.table-striped tbody tr:nth-of-type(odd) {
    background-color: #fcfcfc;
}
table.table-striped.table-hover tbody tr:hover {
    background: #f5f5f5;
}
table.table th i {
    font-size: 13px;
    margin: 0 5px;
    cursor: pointer;
}
table.table td:last-child {
    width: 130px;
}
table.table td a {
    color: #a0a5b1;
    display: inline-block;
    margin: 0 5px;
}

table.table td i {
    font-size: 19px;
}    
.pagination {
    float: right;
    margin: 0 0 5px;
}
.pagination li a {
    border: none;
    font-size: 95%;
    width: 30px;
    height: 30px;
    color: #999;
    margin: 0 2px;
    line-height: 30px;
    border-radius: 30px !important;
    text-align: center;
    padding: 0;
}
.pagination li a:hover {
    color: #666;
}	
.pagination li.active a {
    background: #03A9F4;
}
.pagination li.active a:hover {        
    background: #0397d6;
}
.pagination li.disabled i {
    color: #ccc;
}
.pagination li i {
    font-size: 16px;
    padding-top: 6px
}
.hint-text {
    float: left;
    margin-top: 6px;
    font-size: 95%;
}    
</style>
<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
</head>
<main>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Nom du produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($commandes as $commande) : ?>
                            <tr>
                                <td><?= $commande['nomproduit'] ?></td>
                                <td><?= $commande['quantite'] ?></td>
                                <td><?= $commande['prix'] ?></td>
                                </tr>
                        <?php endforeach; ?>
                        <?php if (empty($commandes)) : ?>
                            <tr>
                                <td colspan='3'>Aucun produit trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>  
    </div>
    <form action="viewcommand.php?reference=<?php echo $code_com ?>" method="post">
    <input type="radio" id="valider" name="choix" value="valider">
    <label for="valider">Valider</label>
    <input type="radio" id="annuler" name="choix" value="annuler">
    <label for="annuler">Annuler</label>
    <button type="submit">Soumettre</button>
</form>

</main>

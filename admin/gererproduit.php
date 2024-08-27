<?php
session_start();
if (($_SESSION['username'])==0) {
   header('Location:../signin/signin.php');
    exit(); 
}
require 'inc\header.php';
?>
<?php 
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "projet"; 
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if (isset($_GET['delete_code'])) {
        $code_produit = $_GET['delete_code'];
        
        $sql_delete = "DELETE FROM produit WHERE reference = ?";
        $stmt = $conn->prepare($sql_delete);
    
        if ($stmt) {
            $stmt->bind_param("s", $code_produit);
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
    }
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
    $sql = "SELECT p.designation, p.prix, p.quantite, p.image, p.disponibilite, p.promotion , p.reference, p.code_cat, c.code_categorie AS code_categorie, c.nom AS nom_categorie
        FROM produit p
        INNER JOIN categorie c ON p.code_cat = c.code_categorie";

    $resultat = $conn->query($sql);
    if (!$resultat) {
        die("Erreur : " . $conn->error); // Affiche l'erreur de la base de données
    }
if ($resultat->num_rows > 0) {
    $_produits = [];
    while ($row = $resultat->fetch_assoc()) {
        $_produits[] = $row;
    }
    $resultat->free();
} else {
    //echo "Aucun produit trouvé.";
}
$conn->close();
?>
<main>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Bootstrap Simple Data Table</title>
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
                        <div class="search-box">
                            <i class="material-icons">&#xE8B6;</i>
                            <input type="text" class="form-control" placeholder="Search&hellip;"><br>
                        </div>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Images</th>
                            <th>Cathégorie</th>
                            <th>disponibilité</th>
                            <th>promotion</th>
                            <th>Actions</th>  
                        </tr>
                    </thead>
                    <tbody>
                    <?php
    if (!empty($_produits)) {
        foreach ($_produits as $index => $produit) {
            echo "<tr>";
            echo "<td>" . $produit['reference'] . "</td>";
            echo "<td>" . $produit['designation'] . "</td>";
            echo "<td>" . $produit['quantite'] . "</td>";
            echo "<td>" . $produit['prix'] . "</td>";
            echo "<td><img src='" . $produit['image'] . "' alt='Image du produit' width='90'></td>";
            echo "<td>" . $produit['code_cat'] . "</td>";
            echo "<td>" . $produit['disponibilite'] . "</td>";
            echo "<td>" . $produit['promotion'] . "</td>";
            echo '
                <td>
                    <a href="produitupdate.php?reference=' . $produit['reference'] . '" class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                    <a href="#" class="delete" title="Delete" data-toggle="tooltip" data-reference="' . $produit['reference'] . '"><i class="material-icons">&#xE872;</i></a>
                    </td>';
            echo "</tr>";

            //$_SESSION['code_produit'] = $produit['reference'];
        }

    } else {
        echo "<tr><td colspan='6'>Aucun produit trouvé.</td></tr>";
    }
?>
<?php
//$_SESSION['code_produit'] = $produit['reference'];
?>
                    </tbody>
                </table>
            </div>
        </div>  
    </div>
        <div class="row justify-content-end mt-3">
            <div class="col-auto">
            <button class="btn-normal" onclick="window.location.href='addproduit.php'">Ajouter Produit</button>
            </div>
        </div>

</main>
<script>
$(document).ready(function(){
    $('.delete').click(function(e){
        e.preventDefault();
        var codeProduit = $(this).data('reference');
        if(confirm("Êtes-vous sûr de vouloir supprimer ce produit ?")) {
            window.location.href = 'gererproduit.php?delete_code=' + codeProduit;
        }
    });
});
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();

    // Add an event listener for the search input
    $('.search-box input').keyup(function(){
        var query = $(this).val();

        // Use AJAX to send a request to the PHP script with the search query
        $.ajax({
            url: 'searchproduit.php', // Create a PHP script (search.php) to handle the search query
            type: 'POST',
            data: {query: query},
            success: function(response){
                // Update the table body with the search results
                $('table tbody').html(response);
            }
        });
    });
});
</script>
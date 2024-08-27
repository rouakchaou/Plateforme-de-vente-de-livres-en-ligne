<?php
// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=projet", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_POST['searchValue'])) {
    $searchValue = $_POST['searchValue'];

    // Requête pour récupérer les produits correspondants au terme de recherche
    $sql = "SELECT p.*, c.Nom AS nom_categorie, c.Description AS description FROM produit p LEFT JOIN categorie c ON p.Code_Cat = c.Code_Categorie WHERE p.Designation LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search', '%' . $searchValue . '%', PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Générer la structure HTML pour les résultats de la recherche
        foreach ($result as $produit) {
            echo '<div class="card">';
                echo '<div class="card-image">';
                    echo '<img src="../admin/' . htmlspecialchars($produit['Image']) . '" alt="' . htmlspecialchars($produit['Designation']) . '" class="product-image">';
                echo '</div>';
                echo '<div class="card-info">';
                    echo '<div class="inner-div">';
                        echo '<h1 class="title">' . htmlspecialchars($produit['Designation']) . '</h1>';
                        echo '<h6 class="card-title">Catégorie: ' . htmlspecialchars($produit['nom_categorie']) . '</h6>';
                        echo '<p class="card-author">' . htmlspecialchars($produit['description']) . '</p>';
                        echo '<div class="btn-fcontainer">';
                            echo '<button class="btn-forgot" onclick="window.location.href=\'bookdetails.php?reference=' . $produit['Reference'] . '\'"><span>Voir plus de détails</span></button>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
        if (empty($result)) {
            echo '<div>Aucun résultat trouvé</div>';
        }
    } else {
        echo "Erreur lors de la récupération des données : " . $pdo->errorInfo();
    }
}
?>

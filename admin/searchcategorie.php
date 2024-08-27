<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'projet';

try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$query = $_POST['query'];

$sql = "SELECT *
        FROM categorie
        WHERE categorie.Nom LIKE :query";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Modification de l'affichage pour correspondre aux colonnes de la table categorie
foreach ($rows as $row) {
    echo "<tr>";
    echo "<td>" . $row["Code_Categorie"] . "</td>";
    echo "<td>" . $row["Nom"] . "</td>";
    echo "<td>" . $row["Description"] . "</td>";
    echo '<td>
            <a href="edit.php?edit=' . $row['Code_Categorie'] . '" class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
            <a href="?pp=' . $row['Code_Categorie'] . '" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
          </td>';
    echo "</tr>";
}
?>

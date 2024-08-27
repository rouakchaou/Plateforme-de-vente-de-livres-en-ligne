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
        FROM produit
        WHERE produit.Designation LIKE :query 
        OR produit.Code_Cat LIKE :query";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    echo "<tr>";
    echo "<td>" . $row["Reference"] . "</td>";
    echo "<td>" . $row["Designation"] . "</td>";
    echo "<td>" . $row["Quantite"] . "</td>";
    echo "<td>" . $row["Prix"] . "</td>";
    echo "<td><img src='" . $row["Image"] . "' alt='Description de l'image' width='100' height='100'></td>";
    echo "<td>" . $row["Code_Cat"] . "</td>";
    echo "<td>" . $row["Disponibilite"] . "</td>";
    echo "<td>" . $row["Promotion"] . "</td>";
    echo '<td>
            <a href="edit.php?edit=' . $row['Reference'] . '" class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
            <a href="?pp=' . $row['Reference'] . '" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
          </td>';
    echo "</tr>";
}
?>

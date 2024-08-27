<?php
session_start();

/*if (isset($_SESSION['username']) && $_SESSION['username'] == 0) {
    header('Location:../signin/signin.php');
    exit();
}*/
include 'header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if (isset($_GET['reference'])) {
    $reference = $_GET['reference'];
    $sql = "SELECT p.designation, p.prix, p.quantite, p.image, p.disponibilite, p.promotion , p.reference, p.code_cat, c.code_categorie AS code_categorie,c.description, c.nom AS nom_categorie
    FROM produit p
    INNER JOIN categorie c ON p.code_cat = c.code_categorie
            WHERE p.code_cat = $reference";
} else {
    $sql = "SELECT p.designation, p.prix, p.quantite, p.image, p.disponibilite, p.promotion , p.reference, p.code_cat, c.code_categorie AS code_categorie,c.description, c.nom AS nom_categorie
    FROM produit p
    INNER JOIN categorie c ON p.code_cat = c.code_categorie";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $produits = [];
    while ($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }
    $result->free();
    
} else {
    echo "Aucun résultat trouvé.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="useraccueil.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Book Cards</title>
    <style>
    body {
      margin: 0; /* Supprimez la marge par défaut pour étendre le contenu jusqu'aux bords de la page */
    padding: 0; /* Supprimez le padding par défaut pour le même motif */
    color: #566787;
    background: rgb(186, 220, 248);
    font-family: 'Times New Roman', Times, serif;
    margin: 0; /* Assurez-vous qu'il n'y ait pas de marge autour du body qui puisse causer un padding */
    padding: 0; /* Supprimez également le padding du body pour une largeur totale */
}

.search-box {
  position: relative; /* La position relative permet à l'icône de positionnement absolu de se placer correctement */
    width: 20%; /* Largeur de la barre de recherche */
    margin-left: auto; /* Pousse la barre de recherche vers la droite */
    margin-right: 0; /* Ajustez si vous voulez un peu d'espace entre la barre de recherche et le bord droit */
    box-sizing: border-box; /* Assurez-vous que le padding et la bordure sont inclus dans la largeur */
}

.search-box input {
  width: 60%; /* L'input occupe toute la largeur de .search-box */
    height: 34px;
    border-radius: 20px;
    padding-left: 35px; /* Ajustez ou supprimez si nécessaire */
    padding-right: 35px; /* Espace pour l'icône de recherche */
    border: 1px solid #565656; /* Bordure transparente */
    background-color: transparent; /* Fond transparent */
    box-shadow: none; /* Supprimez l'ombre portée */
    color: #566787; /* Couleur du texte - ajustez selon vos besoins */
}

.search-box i {
    color: #a0a5b1;
    position: absolute;
    font-size: 19px;
    top: 8px;
    right: 10px; /* Ajustez selon l'espacement désiré du bord droit */
}

</style>
</head>
<body>
<div class="search-box">
    <i class="material-icons">search</i>
    <input type="text" class="form-control" placeholder="Rechercher des produits" id="searchInput" aria-label="Search">
    <!-- Le bouton de soumission peut être supprimé si la recherche se fait en temps réel lors de la frappe -->
</div>

<div id="results" class="mt-3"></div>

<!--script>
  let initialProducts = ''; // Variable pour stocker le HTML des produits initiaux

  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const cardContainer = document.querySelector('.card-container'); // Conteneur des cartes de produits

    // Stockez le contenu initial des produits dans la variable
    initialProducts = cardContainer.innerHTML;

    searchInput.addEventListener('input', function() {
      let query = this.value;

      if (query === '') {
        // Si la zone de recherche est vide, rétablir les produits initiaux
        cardContainer.innerHTML = initialProducts;
      } else {
        // Sinon, effectuez une recherche
        searchProducts(query);
      }
    });

    function searchProducts(query) {
      let xhr = new XMLHttpRequest();
      xhr.open('POST', 'search.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            cardContainer.innerHTML = xhr.responseText;
          } else {
            console.error("Une erreur s'est produite.");
          }
        }
      };
      xhr.send('searchValue=' + query);
    }
  });
</script-->

    </div>
    <div class="card-container">
        <?php
foreach ($produits as $produit) {
    ?>
    <div class="card">
        <div class="card-image">
            <img src="../admin/<?php echo $produit['image']; ?>" alt="<?php echo $produit['designation']; ?>" class="product-image">
        </div>
        <div class="card-info">
            <div class="inner-div"> 
            <h1 class="title"><?php echo $produit['designation']; ?></h1>
            <h6 class="card-title">Catégorie: <?php echo $produit['nom_categorie']; ?></h6>
            <p class="card-author"><?php echo $produit['description']; ?></p>
            
            <div class="btn-fcontainer">
            <button class="btn-forgot" onclick="window.location.href='bookdetails.php?reference=<?php echo $produit['reference']; ?>'"><span>Voir plus de détails</span></button>
            </div>
         </div>
        </div>
    </div>
    <?php
}
?>     
    </div>
    <script>
  let initialProducts = ''; // Variable pour stocker le HTML des produits initiaux

  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const cardContainer = document.querySelector('.card-container'); // Conteneur des cartes de produits

    // Stockez le contenu initial des produits dans la variable
    initialProducts = cardContainer.innerHTML;

    searchInput.addEventListener('input', function() {
      let query = this.value;

      if (query === '') {
        // Si la zone de recherche est vide, rétablir les produits initiaux
        cardContainer.innerHTML = initialProducts;
      } else {
        // Sinon, effectuez une recherche
        searchProducts(query);
      }
    });

    function searchProducts(query) {
      let xhr = new XMLHttpRequest();
      xhr.open('POST', 'search.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            cardContainer.innerHTML = xhr.responseText;
          } else {
            console.error("Une erreur s'est produite.");
          }
        }
      };
      xhr.send('searchValue=' + query);
    }
  });
</script>
</body>
</html>
<?php include 'footer.php';?>
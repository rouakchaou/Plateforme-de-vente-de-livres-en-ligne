
<?php
$ecriture='logout';
if (isset($_SESSION['username']) && $_SESSION['username'] == 0)
$ecriture='seconnecter';
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">  
    <link href="inc\css\bootstrap.min.css" rel="stylesheet">
    <link href="inc\css\starter-template.css" rel="stylesheet">

    <style>
        body {
    margin: 0;
    padding: 0;
    background-color: #87CEEB; /* Bleu clair pour l'arrière-plan */
    font-family: 'Times New Roman', Times, serif;
    color: #333333; /* Couleur de texte standard */
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: #333333;
}

.logo {
    display: flex;
    align-items: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    margin-right: 50px;
    padding: 10px 20px;

}

.logo img {
    margin-right: 10px;
    height: 50px; /* Ajustez selon la taille de votre logo */
}

.menu {
    display: flex;
    list-style-type: none; /* Enlève les puces */
    gap: 20px; /* Ajoute un espace entre les éléments du menu */
    align-items: center;
}

.menu a {
    color: #ffffff;
    text-decoration: none;
    padding: 5px 15px;
    display: flex;
    align-items: center;
}

.search-bar {
    display: flex;
    align-items: center;
    margin-right: 20px;
    justify-content: center; /* Centre la barre de recherche */
    

}

.search-bar input[type="text"] {
    padding: 5px;
    margin-right: 5px;
    border-radius: 20px 0 0 20px; /* Coins arrondis à gauche */
    border: 1px solid black; /* Bordure rose */
    margin-left: 400px;
    background-color: transparent; /* Rend le fond du bouton transparent */

}

.search-bar button {
    background-color: transparent; /* Rend le fond du bouton transparent */
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}

.search-bar button:hover {
    background-color: transparent; /* Rose foncé au survol */
}

.fa-user, .fa-envelope {
    color: #8f3278; /* Icônes roses */
    margin-right: 20px;
    margin-right: 300px;
    margin-left: 50px;

}

.bookstore-title {
    font-size: 20px; /* Ou toute autre taille de police souhaitée */
    /* Vous pouvez ajouter d'autres propriétés de style si nécessaire */
}

.nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

.left-section, .middle-section, .right-section {
            display: flex;
            align-items: center;
        }

        ul.menu {
    text-align: 60% center; /* Centre le menu */
    margin-top: 30px; /* Ajustez cette valeur selon vos besoins */
    list-style-type: none; /* Enlève les puces */
    padding: 5px; /* Enlève le padding par défaut */

}

ul.menu li {
    display: inline-block; /* Affiche les éléments de la liste en ligne */
    /*margin-right: 10px; /* Ajustez l'espacement entre les éléments */
}


ul.menu li a {
    color: black; /* Texte en noir */
    text-decoration: none; /* Enlève le soulignement */
    font-size: 16px; /* Ajustez la taille de police selon vos besoins */
}

ul.menu li a:hover {
    color: white;
    background-color: rgb(122, 194, 249)
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Ajoute une ombre portée */
    padding: 5px 10px; /* Augmente le padding pour un effet de "bouton" */
}


ul.menu li a.id {
    /*margin-left: 370px; /* Ajustez cette valeur pour augmenter l'espacement depuis la gauche */
}

/* Exemple pour des images */
img:hover {
    transform: scale(2.1); /* Agrandit l'image de 10% */
    transition: transform 0.3s ease-in-out; /* Animation douce */
}

/* Effet de zoom sur les liens du menu */

        </style>
  </head>
  <body>
  <?php
        $current_page = basename($_SERVER['PHP_SELF']);
  ?>
    <script src="inc\js\bootstrap.bundle.min.js"></script>      
<div class="col-lg-8 mx-auto p-3 py-md-5">
  <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
  <nav>
        <ul class="menu">
        <div class="logo">
        <a href="useraccueil.php">
        <img class="icon" src="inc/logo.png" alt="Panier" style="width: 1cm; height: 1cm;">
        </a>
        <span class="bookstore-title">BookStore</span>
        </div>
        <a href="useraccueil.php" class="nav-link transparent">
            <i class="fa fa-fw fa-envelope small-icon" style="color:#8f3278"></i> <?php echo $ecriture?>
        </a>
        <?php

/*if (isset($_SESSION['username']) && $_SESSION['username'] != 0) {
    // Si l'utilisateur est connecté, affichez le bouton de déconnexion
    echo '
    <a href="#"style="color:#8f3278"><i class="fa fa-fw fa-user" style="font-size:20px;color:#8f3278"></i>'.($_SESSION['username']).'</a>
        <a href="useraccueil.php" class="nav-link transparent">
            <i class="fa fa-fw fa-envelope small-icon" style="color:#8f3278"></i> Logout
        </a>
    ';
} else {
    // Si l'utilisateur n'est pas connecté, affichez le lien de connexion
    echo '
        <a href="../signin/signin.php" class="nav-link transparent">
            <i class="fa fa-fw fa-envelope small-icon" style="color:#8f3278"></i> Se connecter
        </a>
    ';
}*/
?>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projet";

$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT COUNT(*) AS nombre_lignes FROM panier WHERE id_user = ? AND valide=0";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $_SESSION['userid']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre_lignes = $row['nombre_lignes'];
    }
    $stmt->close();
}

?>
        <a href="panier.php">
            <img class="icon" src="inc/panier.png" alt="Panier" style="width: 0.7cm; height: 0.7cm;">
            <?php echo ($nombre_lignes)  ?>
        </a>
        </ul><br>
        <ul class="menu">
        <?php

          if ($conn->connect_error) {
              die("La connexion a échoué : " . $conn->connect_error);
          }

          $sql = "SELECT Code_Categorie, Nom FROM categorie";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
        ?>
                <li><a href="useraccueil.php?reference=<?php echo $row['Code_Categorie']; ?>" class="id"><?php echo $row['Nom']; ?></a></li>
        <?php
              }
          }
          $conn->close(); 
        ?>
        
        </ul>
      </nav>
  </header>
  </body>
</html>
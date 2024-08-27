<?php
session_start();

if (($_SESSION['username'])==0) {
   header('Location:../signin/signin.php');
    exit(); 
}
$unsigned=0;
/*if ($unsigned=1) {
    header('Location:../signin/signin.php');
     exit(); 
 }*/
include 'header.php';
$produit = null;

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
    $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (($_SESSION['username']) == 0) {
        $unsigned=1; 
    }
    $quantity = $_POST['quantity'];
    if ($quantity <= $produit['Quantite'] && $quantity > 0) {
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Préparation de la requête d'insertion dans la table 'panier'
        $sql = "INSERT INTO panier (id_user, id_produit, nomproduit, quantite, prix) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $prixcom = $quantity * $produit['Prix'];
            $stmt->bind_param("isssi", $_SESSION['userid'], $reference, $produit['Designation'], $quantity, $prixcom);

            $stmt->execute();

            if ($stmt->affected_rows > 0) {
               // echo "Le produit a été ajouté au panier.";
            } else {
                //echo "Erreur lors de l'ajout du produit au panier : " . $stmt->error;
            }

            $stmt->close();
        } else {
           // echo "Erreur dans la préparation de la requête d'insertion.";
        }

        $conn->close();
    } else {
       // echo "La quantité sélectionnée n'est pas valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details du Produit</title>
    <link rel="stylesheet" href="bookdetails.css">

    <style>
      body {
    font-family: 'Times New Roman', Times, serif;    
    background-color: rgb(186, 220, 248); /* Bleu clair pour l'arrière-plan */

}

.image-background {
    background-color: #87CEEB;
    max-width: 500px;

}

.add-to-cart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px; /* Ajustez selon vos besoins */
}

#add-to-cart {
    background-color: #8f3278; /* Rose */
    color: white;
    font-size: 14px;
    padding: 8px 20px;
    border-radius: 40px;
    justify-content: center;
    align-items: center;
}
.counter_container .quantity {
width: 60px; /* Ajustez la largeur selon vos besoins */
height: 30px; /* Hauteur du champ de saisie */
border: 1px solid rgb(122, 194, 249); /* Bordure rose */
border-radius: 15px; /* Coins arrondis */
text-align: center;
font-size: 16px; /* Taille de police */
color: #fff; /* Couleur du texte */
background-color: #8f3278; /* Couleur de fond rose */
}

/* Assurez-vous que le champ de saisie est lisible et joli sur tous les navigateurs */
.counter_container .quantity:focus {
    outline: none;
    border-color: rgb(122, 194, 249); /* Couleur de bordure lors de la mise au point */
}

.product-image {
    width: 410px; /* Largeur du carré */
    height: 410px; /* Hauteur du carré */
    object-fit: cover; /* Assure que l'image couvre bien l'espace sans être déformée */
    transition: transform 0.3s ease; /* Transition pour l'effet de zoom */
}

.product-image:hover {
    transform: scale(1.2); /* Zoom lors du survol */
}

      </style>
</head>
<body class="h-screen font-Kumbh">
    <main class="md:flex items-center justify-center md:h-4/5" >
        <div class="md:w-4/12" >
        <img src="../admin/<?php echo $produit['Image']; ?>" alt="" class="rounded-2xl product-image image-background">
        </div>

        <div class="md:w-8/12 px-12">
            <h1 class="text-gray-900 lg:text-3xl md:text-2xl text-3xl font-bold  mb-5"><?php echo $produit['Designation']; ?></h1><br>
            <p class="font-bold text-2xl text-gray-900">Prix: <?php echo $produit['Prix']; ?> DT</p><br><br>
            <p class="text-gray-600 mb-4"><?php 
            if ($produit['Promotion']>0){
                echo "En promotion: ".$produit['Promotion']."%<br>";
                $prix_apres_promo = $produit['Prix']-($produit['Prix'] * ($produit['Promotion'] / 100));
                echo "Prix après la promotion: ". $prix_apres_promo . " DT<br><br>";}
            else
                echo "Ce produit n'est pas en promotion.";?></p>
            <!--p class="text-gray-600 mb-4">Catégorie: <?php echo $produit['nom_categorie']; ?></p-->
            <?php if ($produit['Disponibilite'] > 0): ?>
                <p class="text-gray-600 mb-4"><?php echo $produit['Quantite']; ?> article(s) encore disponible(s).</p>
                <form method="POST">
                <div class="counter_container mb-4">
                    <input type="number" class="quantity" name="quantity" value="1" min="1">
                </div>
            <div>
                <button id="add-to-cart" class="shadow-md p-2 rounded-lg flex justify-center items-center bg-rose text-white transition duration-300">
                    Ajouter au panier
                </button>
            </div>
            </form>
            <script>
    document.getElementById('add-to-cart').addEventListener('click', function() {
            var quantity = parseInt(document.querySelector('.quantity').value);
            var availableQuantity = <?php echo $produit['Quantite']; ?>;

            if (quantity > availableQuantity) {
                alert("La quantité sélectionnée est supérieure à la quantité disponible.");
            }
        });
</script>
            <?php else: ?>
                <p class="font-bold text-2xl text-gray-900" style="color:#8f3278;">Out Of Stock!</p><br><br>
            <?php endif; ?>
        </div>
    </main>
    <script src="script.js"></script>
     
</body>
</html>
<?php include 'footer.php';
?>
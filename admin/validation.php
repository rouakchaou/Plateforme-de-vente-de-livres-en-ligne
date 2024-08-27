<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'projet';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form To Email Using JavaScript</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        font-family: 'Times New Roman', Times, serif;
    background-color: rgb(186, 220, 248);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h3 {
        margin-bottom: 20px;
        color: #333;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px;
    }

    button {
    background-color: #8f3278;
    border: none;
    color: white;  
    border-radius: 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 15px; /* Ajuster la taille du texte */
    padding: 10px 20px; /* Ajuster la taille du bouton en fonction du padding */
    margin: 1px 1px;
    cursor: pointer;
    white-space: nowrap;
}

    button:hover {
        background-color: rgb(186, 220, 248);
    }
</style>
<body>
    <?php
    $email = isset($_GET['e']) ? $_GET['e'] : '';
    ?>

    <div class="container">
        <form onsubmit="sendEmail(); return false;">
            <h3>SEND A VALIDATION MAIL !</h3>
            <input type="email" id="email" placeholder="Email id" value="<?= $email ?>" required>
            <button  type="submit">Envoyer</button>
            <br></br>
            <button  type="submit1" onclick="window.location.href='gererproduit.php'">Retour</button>
        </form>
    </div>

    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script>
        function sendEmail() {
            // Obtenez la valeur de l'e-mail depuis le champ d'entrée
            var toEmail = document.getElementById('email').value;

            // Vérifiez si l'e-mail est vide
            if (!toEmail) {
                // Affichez une alerte si l'e-mail est vide
                alert("Veuillez saisir une adresse e-mail valide.");
                return; // Arrêtez la fonction car l'e-mail est vide
            }

            // Chargez le contenu HTML depuis 'temp.htm'
            fetch('validationmailtemplate.php')
                .then(response => response.text())
                .then(htmlContent => {
                    // Envoyez l'e-mail avec le contenu HTML
                    Email.send({
                        SecureToken: "6506e85c-7cfe-42f1-9ef8-d1afc2a19b7f",
                        To: toEmail, // Utilisez la valeur de l'e-mail ici
                        From: "chaari.marwa@enis.tn",
                        Subject: "Confirmation mail ME shop",
                        Body: htmlContent,
                    }).then(
                        message => alert(message)
                    );
                })
                .catch(error => console.error(error));
        }
    </script>
</body>

</html>
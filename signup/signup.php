<?php
include ('signup.html');
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $role = "user";
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (Nom, Email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            header('Location: ../signin/signin.php');
        } else {
            //echo "Erreur lors de l'ajout de l'utilisateur.";
        }
        $stmt->close();
        $conn->close();
    } else {
        //die( "Erreur dans la préparation de la requête d'insertion.");
    }
    } else {
        //die ("Les données du formulaire n'ont pas été soumises correctement.");
    }
?>
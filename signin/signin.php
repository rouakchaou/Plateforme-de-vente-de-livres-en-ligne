<?php
include ('signin.html');
?>  
<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projet";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $email = $_POST['email']; // Assurez-vous de récupérer correctement les données du formulaire
    $password = $_POST['password']; // Assurez-vous de récupérer correctement les données du formulaire

    $sql = "SELECT * FROM users WHERE Email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['username'] = $user['Nom'];
            $_SESSION['userid'] = $user['ID'];
            if (isset($_POST['remember_me'])) {
            setcookie("remember_user", $username, time() + 3600 * 24 * 30, "/");
            setcookie("remember_pass", $password, time() + 3600 * 24 * 30, "/"); 
            }
            if ($user['Role'] == 'user'){ 
            header('Location: ../user/useraccueil.php');
            }
            else{
            header('Location: ../admin/gererproduit.php');
            }
        } else {
            //echo"mdp inv";
        }
    } else {
        //echo"introub";
    }

    $conn->close();
}
?>

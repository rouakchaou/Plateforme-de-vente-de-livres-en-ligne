
<?php 
$_SESSION['messagecommandeadmin']='';
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "projet"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    if (isset($_GET['reference'])) {
        $code_com = $_GET['reference'];
        echo $code_com;
        $sql_delete_commande = "DELETE FROM commande WHERE ID_commande = ?";
    $stmt_commande = $conn->prepare($sql_delete_commande);

    if ($stmt_commande) {
        $stmt_commande->bind_param("s", $code_com);
        $stmt_commande->execute();
        
        if ($stmt_commande->affected_rows > 0) {
            $sql_delete_panier = "DELETE FROM panier WHERE idcommande = ?";
            $stmt_panier = $conn->prepare($sql_delete_panier);

            if ($stmt_panier) {
                $stmt_panier->bind_param("s", $code_com);
                $stmt_panier->execute();
                
                if ($stmt_panier->affected_rows > 0) {
                } else {
                }
                $stmt_panier->close();
            } else {
            }
        } else {
        }
        $stmt_commande->close();
    } else {
    }
    }}
?>
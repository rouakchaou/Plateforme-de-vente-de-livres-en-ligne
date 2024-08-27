<!doctype html>
<html lang="en">
  <head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--title>Bootstrap Simple Data Table</title-->
<link rel="stylesheet" type="text/css" href="inc/admin.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!--script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script-->
<style>
    body {
    color: #566787;
    background: rgb(186, 220, 248);
    font-family: 'Times New Roman', Times, serif;
}

.table-responsive {
    margin: 30px 0;
}
.table-wrapper {
    min-width: 900px;
    background: #fff;
    padding: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.table-title {
    padding-bottom: 10px;
    margin: 0 0 10px;
    min-width: 100%;
}
.table-title h2 {
    margin: 8px 0 0;
    font-size: 22px;
}
.search-box {
    position: relative;        
    float: right;
}
.search-box input {
    height: 34px;
    border-radius: 20px;
    padding-left: 35px;
    border-color: #ddd;
    box-shadow: none;
}
.search-box input:focus {
    border-color: #3FBAE4;
}
.search-box i {
    color: #a0a5b1;
    position: absolute;
    font-size: 19px;
    top: 8px;
    left: 10px;
}
table.table tr th, table.table tr td {
    border-color: #e9e9e9;
}
table.table-striped tbody tr:nth-of-type(odd) {
    background-color: #fcfcfc;
}
table.table-striped.table-hover tbody tr:hover {
    background: #f5f5f5;
}
table.table th i {
    font-size: 13px;
    margin: 0 5px;
    cursor: pointer;
}
table.table td:last-child {
    width: 130px;
}
table.table td a {
    color: #a0a5b1;
    display: inline-block;
    margin: 0 5px;
}

table.table td i {
    font-size: 19px;
}    
.pagination {
    float: right;
    margin: 0 0 5px;
}
.pagination li a {
    border: none;
    font-size: 95%;
    width: 30px;
    height: 30px;
    color: #999;
    margin: 0 2px;
    line-height: 30px;
    border-radius: 30px !important;
    text-align: center;
    padding: 0;
}
.pagination li a:hover {
    color: #666;
}	
.pagination li.active a {
    background: #03A9F4;
}
.pagination li.active a:hover {        
    background: #0397d6;
}
.pagination li.disabled i {
    color: #ccc;
}
.pagination li i {
    font-size: 16px;
    padding-top: 6px
}
.hint-text {
    float: left;
    margin-top: 6px;
    font-size: 95%;
}    
</style>
  </head>
  <body>
  <?php
        $current_page = basename($_SERVER['PHP_SELF']);
  ?>
    <!--script src="inc\js\bootstrap.bundle.min.js"></script-->      
<div class="col-lg-8 mx-auto p-3 py-md-5">
  <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
  <nav>
        <div class="logo">
        <a href="useraccueil.php">
        <img class="icon" src="inc/logo.png" alt="Panier" style="width: 1cm; height: 1cm;">
        </a>
          <span>BookStore</span>
        </div>
        <ul class="menu">
        <?php
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "projet";

          $conn = new mysqli($servername, $username, $password, $dbname);

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
        ?>
        <?php
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
$conn->close();
?>

<a href="#"style="color:#8f3278"><i class="fa fa-fw fa-user" style="font-size:20px;color:#8f3278"></i> <?php echo ($_SESSION['username'])  ?> </a>
<a href="useraccueil.php?=logout=1" class="nav-link" style="color:#8f3278"><i class="fa fa-fw fa-envelope" style="color:#8f3278"></i> Logout</a>

          <a href="panier.php">
            <img class="icon" src="inc/panier.png" alt="Panier" style="width: 0.7cm; height: 0.7cm;">
        </a>
        <a >
        <?php echo ($nombre_lignes)  ?>
        </a>
        </ul>
      </nav>
  </header>
  </body>
</html>
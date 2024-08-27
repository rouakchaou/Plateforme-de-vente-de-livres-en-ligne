<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">

    <!--link rel="stylesheet" type="text/css" href="admin.css"-->
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/starter-template/">  
    <link href="inc\css\bootstrap.min.css" rel="stylesheet">
    <link href="inc\css\starter-template.css" rel="stylesheet">
  </head>
  <body>
  <?php
        $current_page = basename($_SERVER['PHP_SELF']);
  ?>
    <script src="inc\js\bootstrap.bundle.min.js"></script>      
<div class="col-lg-8 mx-auto p-3 py-md-5">
  <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
  <nav>
        <div class="logo">
          <img src="inc/images/logo.png" alt="Logo de mon projet">
          <span>BookStore</span>
        </div>
        <ul class="menu">
          <li><a href="gererproduit.php">Produit</a></li>
          <li><a href="gerercategorie.php">Catégorie</a></li>
          <li><a href="gerercommande.php">Commande</a></li>
          <a href="#"style="color:#8f3278"><i class="fa fa-fw fa-user" style="font-size:20px;color:#8f3278"></i> <?php echo ($_SESSION['username'])  ?> </a>
          <a href="../signin/signin.php" class="nav-link" style="color:#8f3278"><i class="fa fa-fw fa-envelope" style="color:#8f3278"></i> Logout</a>
        </ul>
      </nav>
  </header>
  </body>
</html>
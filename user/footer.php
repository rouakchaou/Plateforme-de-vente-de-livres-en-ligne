<style>

.footer {
    font-family: 'Times New Roman', Times, serif;
    height: 100%; /* Assure que le body s'étend sur toute la hauteur de la page */
   background-color: #707070; /* blanc cassé très clair */
   color: white; /* couleur du texte sombre pour le contraste */
   width: 100%; /* s'étend sur toute la largeur */
   min-width: 173vh; /* Assure que le body couvre au moins toute la hauteur de la fenêtre de visualisation */
   min-height: 32vh; /* Assure que le body couvre au moins toute la hauteur de la fenêtre de visualisation */
   box-sizing: border-box; /* s'assure que le padding n'augmente pas la largeur du footer */

}
.footer .grid {
   display: grid;
   grid-template-columns: repeat(4, 1fr);
   align-items: flex-start;
   margin: 0 auto; /* centre le grid horizontalement */
   max-width: 1200px; /* ajustez la largeur maximale selon vos besoins */
}
/* Ajoutez des requêtes média si nécessaire pour les écrans plus petits */
@media (max-width: 768px) {
    .footer .grid {
        grid-template-columns: repeat(2, 1fr); /* Sur les petits écrans, utilisez 2 colonnes */
    }
}

.footer .grid .box h3{
    font-size: 1.3rem; /* Taille de police plus petite pour les titres */
   color:var(--black);
   margin-bottom: 0.2rem;
   text-transform: capitalize;
   padding: 0.1rem 0; /* ajustez le padding pour réduire l'espace */

}

.footer .grid .box a{
    display: block; /* ou 'flex' si vous voulez que les icônes soient alignées avec le texte */
   align-items: center;
   margin:1rem 0;
   font-size: 1rem; /* Taille de police plus petite pour les liens */
   padding: 0.1rem 0; /* ajustez le padding pour réduire l'espace */
   color:var(--light-color);
}

.footer .grid .box a i{
    display: inline-block;
   width: 40px; /* ou la taille que vous avez pour les autres icônes */
   text-align: center;
   margin-right: 5px; /* ou l'espace que vous voulez entre l'icône et le texte */

}

footer .grid .box a.email {
    padding-top: 0px; /* Ajustez le padding supérieur pour aligner l'élément en haut avec les autres */
   padding-bottom: 0px; /* Ajustez le padding inférieur si nécessaire */
   line-height: 1.5; /* Assurez-vous que cela correspond à la hauteur de ligne des autres éléments de lien */
   display: inline-flex; /* Utilisez flex pour un meilleur contrôle de l'alignement */
   align-items: center; /* Cela aligne les enfants (texte et icône) au centre verticalement */
}

.footer .grid .box a.email i {
   width: 0px; /* Ajustez ceci en fonction de la taille réelle de vos icônes */
   text-align: center;
   margin-right: 1%; /* Espace entre l'icône et le texte */
}

.footer .grid .box a:hover{
   color:var(--main-color);
}

.footer .grid .box a:hover i{
   padding-right: 2rem;
}

.footer .credit{
    text-align: center;
   padding: 1rem; /* Réduit le padding pour diminuer la hauteur du crédit */
   border-top: 1px solid #eaeaea; /* Bordure claire pour un contraste doux */
   font-size: 1rem; /* Taille de police plus petite pour le texte de crédit */
   color: white; /* Couleur du texte */
   margin-top: 1rem; /* Ajoute un espace au-dessus de la section crédit si nécessaire */
}

.footer .credit span {
   color: var(--main-color); /* Couleur pour le texte mis en évidence */
}
.box {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
}

.box a {
  margin: 0;
  padding: 5;
  /* Ajoutez d'autres styles de réinitialisation au besoin */
}

.grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  /* Autres propriétés de la grille */
}

.box a {
  grid-column: 1 / -1; /* Assurez-vous que cela s'applique à tous les éléments a de la même manière */
}

.box a {
  display: flex;
  align-items: center;
}

.box a i {
  margin-right: 10px; /* Ajustez selon les besoins */
}

</style>
    
<footer class="footer">

   <section class="grid">

      <div class="box">
         <h3>quick links</h3>
         <a href="useraccueil.php"> <i class="fas fa-angle-right"></i> home</a>
         <a href="about.php"> <i class="fas fa-angle-right"></i> about</a>
         <a href="useraccueil.php"> <i class="fas fa-angle-right"></i> shop</a>
         <a href="contact.php"> <i class="fas fa-angle-right"></i> contact</a>
      </div>

      <div class="box">
         <h3>extra links</h3>
         <a href="signin.php"> <i class="fas fa-angle-right"></i> signin</a>
         <a href="signup.php"> <i class="fas fa-angle-right"></i> signup</a>
         <a href="cart.php"> <i class="fas fa-angle-right"></i> cart</a>
         <a href="usercommande.php"> <i class="fas fa-angle-right"></i> orders</a>
      </div>

      <div class="box">
         <h3>contact us</h3>
         <a href="tel:1234567890"><i class="fas fa-phone"></i> +123 456 7899</a>
         <a href="tel:1112223333"><i class="fas fa-phone"></i> +111 222 3333</a>
         <a href="https://mail.google.com" class="email"><i class="fas fa-envelope"></i> BookStore@gmail.com</a>
         <a href="https://www.google.com/myplace"><i class="fas fa-map-marker-alt"></i> mumbai, india - 400104 </a>
      </div>

      <div class="box">
         <h3>follow us</h3>
         <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i>facebook</a>
         <a href="https://twitter.com/?lang=fr"><i class="fab fa-twitter"></i>twitter</a>
         <a href="https://www.instagram.com"><i class="fab fa-instagram"></i>instagram</a>
         <a href="https://www.linkedin.com"><i class="fab fa-linkedin"></i>linkedin</a>
      </div>

   </section>

   <div class="credit">&copy; copyright @ <?= date('Y'); ?> by <span>mr. web designer</span> | all rights reserved!</div>

</footer>
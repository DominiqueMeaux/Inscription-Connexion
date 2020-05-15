
<?php 
require 'db.php';
require_once "controller/indexController.php";






?>




    
<?php
ob_start();

?>

    <?php include('messages.php');?>

    <form action="index.php" method="post">
      <div class="form-group">
        <label for="name">Nom d'utilisateur</label>
        <input type="text" name="name" class="form-control" placeholder="Nom d'utilisateur" value="<?= $name ?? '';?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?? '';?>">
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" class="form-control" placeholder="Mot de passe">
      </div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>




<?php
$content = ob_get_clean();
$title = "Inscription";
require "template.php";
?>
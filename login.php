<?php 
require('db.php');
require_once "controller/loginController.php";
ob_start();

?>

    

    <?php include('messages.php');?>

    <form action="login.php" method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?? '';?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Mot de passe">
      </div>
      <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    <br>

    <p><a href="forgot.php">J'ai oublié mon mot de passe.</a></p>
    <p><a href="index.php">Je veux ouvrir un compte.</a></p>

    <?php
$content = ob_get_clean();
$title = "Connexion";
require "template.php";
?>
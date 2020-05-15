<?php

require 'db.php';
require_once "controller/passwordController.php";


ob_start();

?>

    

    <?php include('messages.php');?>

    <form action="password.php" method="post">
      <div class="form-group">
        <label for="actual">Mot de passe actuel</label>
        <input type="password" name="actual" class="form-control" placeholder="Mot de passe actuel">
      </div>
      <div class="form-group">
        <label for="password">Nouveau mot de passe</label>
        <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe">
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirmez le mot de passe</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmez le mot de passe">
      </div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <br>

    <p><a href="dashboard.php">Revenir à mon compte</a></p>

    <?php
$content = ob_get_clean();
$title = "Changer mon mot de passe";
require "template.php";
?>
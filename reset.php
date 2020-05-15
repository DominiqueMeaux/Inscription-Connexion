<?php
require 'db.php';
require_once "controller/resetController.php";

ob_start();

?>

    

    <?php include('messages.php');?>

    <form action="reset.php?token=<?=$token?>" method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?? '';?>">
      </div>
      <div class="form-group">
        <label for="password">Nouveau mot de passe</label>
        <input type="password" name="password" class="form-control" placeholder="Mot de passe">
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirmez le mot de passe</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmez le mot de passe">
      </div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

    <?php
$content = ob_get_clean();
$title = "Réinitialiser mon mot de passe";
require "template.php";
?>
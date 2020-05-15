<?php
require 'db.php';
require_once "controller/forgotController.php";





?>

<?php
ob_start();

?>

    

    <?php include('messages.php');?>

    <form action="forgot.php" method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?? '';?>">
      </div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <br>

    <p><a href="login.php">Je m'en souviens en fait.</a></p>

    <?php
$content = ob_get_clean();
$title = "Mot de passe oublié";
require "template.php";
?>
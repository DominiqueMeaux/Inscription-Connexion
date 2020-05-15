<?php

require('db.php');
require_once "controller/dashboardController.php";



?>


<?php
ob_start();

?>

    <h2><?=$title;?></h2>

    <?php include('messages.php');?>

    <form action="dashboard.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Nom d'utilisateur</label>
        <input type="text" name="name" class="form-control" placeholder="Nom d'utilisateur" value="<?= $name ?? $user->name;?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?? $user->email;?>">
      </div>
      <div class="form-group">
        <label for="photo">Photo au format jpeg, jpg ou png d'au moins 200x200px</label>
        <input type="file" name="photo" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

    <br>

    <a style="float: right;" onclick="return confirm('Confirmez la suppresion de votre comtpe ?');" href="delete.php" class="btn btn-danger delete">Supprimer mon compte</a>

    <p><a href="password.php">Modifier mon mot de passe.</a></p>

	<!-- On affiche la photo si il y en a une -->
	<?php if(!empty($user->photo)):?>
		<a href="photos/<?=$user->id.'/'.$user->photo;?>">
			<img src="photos/<?=$user->id.'/'.$user->photo;?>" alt="avatar" width="200" height="200">
		</a>
	<?php endif;?>


	<?php
$content = ob_get_clean();
$title = "Tableau de bord";
require "template.php";
?>
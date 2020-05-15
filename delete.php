<?php
require 'db.php';


// Vérification de connexion et variable de session sinon redirection sur la page login
if(empty($_SESSION['user'])){
  header('Location: login.php');
}

$user = $_SESSION['user'];


// stockage dans une variable avec le dossier photo et l'id de l'utilisateur
$filePath = 'photos/'.$user->id.'/'.$user->photo;

// Utilisation de la class php DirectoryIterator pour supprimer les fichier cachés
$dir = new DirectoryIterator(dirname('photos/'.$user->id));

//Boucle foreach pour parcourir les fichiers
foreach($dir as $fileinfo){
	// Si j'ai des fichier qui commence par un point (isDot())
	if($fileinfo->isDot()){
		// Je fais unlink pour tout supprimer (getPathname =>  Retourne le chemin et le nom de l'entrée courante du dossier)
		unlink($fileinfo->getPathname());
	}
}

// Si l'utilisateur à bien une photo, que le fichier existe et que ce fichier et bien un fichier et non un dossier
if($user->photo && file_exists($filePath) && is_file($filePath)){

	// On supprime la photo si elle existe
	unlink($filePath);
	// Puis on supprime le dossier
	rmdir('photos/'.$user->id);
}

/**
 * Requête de suppression du l'utilisateur
 */
$req = $db->prepare('DELETE FROM users WHERE id=:id');
$req->bindValue(':id', $user->id, PDO::PARAM_INT);
$req->execute();
$req->closeCursor();

// On supprime la session et on redirige vers l'accueil
unset($_SESSION['user']);
session_destroy();
header('Location: index.php');
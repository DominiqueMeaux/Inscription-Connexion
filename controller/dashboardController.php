<?php 

// Si l'utilisateur n'est pas connecté on le redirige sur login
if(empty($_SESSION['user'])){
    header('Location: login.php');
  }
  
  
  
  $user = $_SESSION['user'];
  
  $title = 'Bonjour '.$user->name;
  
if(!empty($_POST))
{
	$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

  	extract($post);

  	$errors = [];

  	if(empty($name) || strlen($name) < 3){
	    array_push($errors, 'Le nom est require et doit contenir au moins 3 caractères.');
	  }

	  if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
	    array_push($errors, 'L\'email n\'est pas une adresse email valide.');
	  }

	if(empty($errors))
	{
        /**
         * Requête de vérification ( Un même nom et un même email ne peut pas être utilisé par deux utilisateur != )
         */
		$req = $db->prepare('SELECT * FROM users WHERE name=:name AND id != :id');
		$req->bindValue(':name', $name, PDO::PARAM_STR);
		$req->bindValue(':id', $user->id, PDO::PARAM_INT);
		$req->execute();
        $req->closeCursor();
		if($req->rowCount() > 0){
			array_push($errors, 'Un autre utilisateur a déjà ce nom.');
		}

		$req = $db->prepare('SELECT * FROM users WHERE email=:email AND id != :id');
		$req->bindValue(':email', $email, PDO::PARAM_STR);
		$req->bindValue(':id', $user->id, PDO::PARAM_INT);
		$req->execute();
        $req->closeCursor();
		if($req->rowCount() > 0){
			array_push($errors, 'Un autre utilisateur a déjà cet email.');
		}


        //  Vérification de la présence d'une photo et traitement
		if(!empty($_FILES['photo']['name']))
		{

            // Placement dans une variable de $_FILES['photo]
			$photo = $_FILES['photo'];

            // Chemin de destination
			$filePath = 'photos/'.$user->id;
            $thumbPath = $filePath.'/thumbnail';
            
            // Création du dossier photos avec mkdir ( utilisation du '@' pour éviter l'erreur ( dossier déjà créer))
			@mkdir($filePath, 0777, true);

			

			$allowedExt = ['jpeg', 'jpg', 'png'];

            $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
            
            // Si l'extension n'est pas dans le tableau alors
			if(!in_array($ext, $allowedExt)){
				array_push($errors, 'Le fichier n\'est pas autorisé.');
			}
			else{

                // Sinon on stock dans une variable 
                $infos = getimagesize($photo['tmp_name']);
                
                // Gestion de la taille de l'image
				$width = $infos[0];

				$height = $infos[1];

				if($width < 200 || $height < 200){
					array_push($errors, 'L\'image doit faire au moins 200px de large et 200px de hauteur.');
				}
				else{
                    // uniqid permet de mettre un préfix avant le nom de la photo pour le rendre unique
                    $filename = uniqid($user->id, true).'.'.$ext;
                    
                    // On enregistre la photo ( revoie true ou false en fonction de si ca a fonctionné ou pas )
					move_uploaded_file($photo['tmp_name'], $filePath.'/'.$filename);

					
				}
			}
		}

		if(empty($errors))
		{
            /**
             * Requête de récupération de l'utilisateur pour insertion photo en bd
             */
			$req = $db->prepare('SELECT * FROM users WHERE id=:id');
			$req->bindValue(':id', $user->id, PDO::PARAM_INT);
			$req->execute();
			$user = $req->fetch();
            $req->closeCursor();

			if($user->photo){
				$oldFilePath = $filePath.'/'.$user->photo;
				$oldThumbFilePath = $thumbPath.'/'.$user->photo;
			}
            /**
             * Requête de mise à jour de l'utilisateur
             */
			$req = $db->prepare('UPDATE users SET name=:name, email=:email, photo=:photo WHERE id=:id');
			$req->bindValue(':name', $name, PDO::PARAM_STR);
			$req->bindValue(':email', $email, PDO::PARAM_STR);
			$req->bindValue(':photo', $filename ?? $user->photo, PDO::PARAM_STR);
			$req->bindValue(':id', $user->id, PDO::PARAM_INT);
            $req->execute();
            


            /**
             * Requête de récupération des infos de l'utilisateur si la photo a changé
             */
			$req = $db->prepare('SELECT * FROM users WHERE id=:id');
			$req->bindValue(':id', $user->id, PDO::PARAM_INT);
			$req->execute();
			$user = $req->fetch();
           
            
            // On transmet les nouvelles données en session
			unset($_SESSION['user']);
			$_SESSION['user'] = $user;


            // Suppréssion de l'ancienne photo en cas de changement et vérif de la présence d'une photo
			if(!empty($oldFilePath) && !empty($filename)){
				@unlink($oldFilePath);
				@unlink($oldThumbFilePath);
			}

			$success = 'Informations mises à jour.';
		}
	}
}
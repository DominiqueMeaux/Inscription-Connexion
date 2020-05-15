<?php 

if(!empty($_SESSION['user'])){
    header('Location: dashboard.php');
  }
  
  if(empty($_GET['token'])){
    header('Location: index.php');
  }
  
  $token = $_GET['token'];

  /**
   * Vérification de l'existance du token en bd
   */
  
  $req = $db->prepare('SELECT * FROM password_resets WHERE token=:token');
  $req->bindValue(':token', $token, PDO::PARAM_STR);
  $req->execute();
  
  // Si il n'existe pas, redirection page d'accueil
  if(!$req->rowCount()){
    header('Location: index.php');
  }
  else{
    $password_reset = $req->fetch();
  }
  
  if(!empty($_POST))
  {
      $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  
      extract($post);
  
      $errors = [];
  
      if($password_reset->email !== $email){
        array_push($errors, 'Cette adresse email est invalide.');
      }
  
      if(empty($password) || strlen($password) < 6){
        array_push($errors, 'Le mot de passe est requis et doit contenir au moins 6 caractères.');
      }
  
      if(!empty($password) && $password != $password_confirmation){
        array_push($errors, 'Les mots de passe ne correspondent pas.');
      }
  
      //Requête pour vérifier si l'email existe toujours dans la table users
  
      if(empty($errors)){
        $req = $db->prepare('UPDATE users SET password=:password WHERE email=:email');
        $req->bindValue(':password', password_hash($password, PASSWORD_ARGON2ID), PDO::PARAM_STR);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
  
        $success = 'Mot de passe mise à jour. <a href="login.php">Me connecter</a>';
  
        /**
         * Requête de suppression des données devenu inutile
         */
        $req = $db->prepare('DELETE FROM password_resets WHERE email=:email');
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
  
        unset($email, $password);
      }
  }
  
  
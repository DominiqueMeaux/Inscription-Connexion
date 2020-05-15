<?php 

if(empty($_SESSION['user'])){
    header('Location: login.php');
  }
  
  $user = $_SESSION['user'];
  
  
  if(!empty($_POST))
  {
      $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
  
        extract($post);
  
        $errors = [];
  
        if(!password_verify($actual, $user->password)){
            array_push($errors, 'Le mot de passe actuel n\'est pas le bon.');
        }
  
        if(empty($password) || strlen($password) < 6){
          array_push($errors, 'Le mot de passe est requis et doit contenir au moins 6 caractères.');
        }
  
      if($password !== $password_confirmation){
          array_push($errors, 'Les mots de passe ne correspondent pas.');
      }
  
      if(empty($errors))
      {
          $req = $db->prepare('UPDATE users SET password=:password WHERE id=:id');
          $req->bindValue(':password', password_hash($password, PASSWORD_ARGON2ID), PDO::PARAM_STR);
          $req->bindValue(':id', $user->id, PDO::PARAM_INT);
          $req->execute();
          $req->closeCursor();
  
          $success = 'Mot de passe mis à jour.';
      }
  }
  
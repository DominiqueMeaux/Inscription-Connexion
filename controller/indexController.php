<?php

// Si la session user existe redirection sur dashboard
if(!empty($_SESSION['user'])){
    header('Location: dashboard.php');
  }
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
  
  if(empty($password) || strlen($password) < 6){
    array_push($errors, 'Le mot de passe est requis et doit contenir au moins 6 caractères.');
  }
  
  if(empty($errors))
  {
    $req = $db->prepare('SELECT * FROM users WHERE name = :name');
    $req->bindValue(':name', $name, PDO::PARAM_STR);
    $req->execute();
    $req->closeCursor();
    
    if($req->rowCount() > 0){
      array_push($errors, 'Un utilisateur est déjà enregistré avec ce nom.');
    }
    
    $req = $db->prepare('SELECT * FROM users WHERE email = :email');
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->execute();
    $req->closeCursor();
    
    if($req->rowCount() > 0){
      array_push($errors, 'Un utilisateur est déjà enregistré avec cet email.');
    }
    
    if(empty($errors))
    {
      $req = $db->prepare('INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW()) ');
      $req->bindValue(':name', $name, PDO::PARAM_STR);
      $req->bindValue(':email', $email, PDO::PARAM_STR);
      $req->bindValue(':password', password_hash($password, PASSWORD_ARGON2ID), PDO::PARAM_STR);
      $req->execute();
      $req->closeCursor();
      
      unset($name, $email, $password);
      $success = 'Votre inscription est terminée, vous pouvez <a href="login.php">vous connecter</a>.';
    }
    
  }
  
}

?>
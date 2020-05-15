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

  if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
    array_push($errors, 'L\'adresse email n\'est pas valide.');
  }

  if(empty($password)){
    array_push($errors, 'Le mot de passe est requis.');
  }


  if(empty($errors))
  {

    /**
     * Requête de vérification si l'adresse mail existe en base de données
     */
    $req = $db->prepare('SELECT * FROM users WHERE email=:email');
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->execute();

    $user = $req->fetch();
    //Si elle eiste on vérifie que le password correspond puis on redirige sur le tableaux de bord si c'est bon
    if($user && password_verify($password, $user->password)){
      $_SESSION['user'] = $user;
      header('Location: dashboard.php');
    }
    // Sinon message d'erreur
    array_push($errors, 'Mauvais identifiants');
  }
}

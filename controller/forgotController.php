<?php


if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
}

require 'vendor/autoload.php';

if (!empty($_POST)) {
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    extract($post);

    $errors = [];


    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, 'Cet email est invalide.');
    } else {

        /**
         * Requête de vérification pour savoir si un utilisateur est inscrit avec cette adresse mail
         */
        $req = $db->prepare('SELECT * FROM users WHERE email=:email');
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();

        // Si aucun utilisateur n'est enregistré avec cette email
        if (!$req->rowCount()) {
            array_push($errors, 'Cet email ne correspond à aucun membre du site.');
        }
        // Sinon on récupère l'utilisateur avec fetch
        else {
            $user = $req->fetch();
            
        }

        // Si il n'y a pas d'erreur on crée un jeton de sécurité avec inicid() (Génère un id unique basé sur le timestamp)
        if (empty($errors)) {
            $token = uniqid();

            /**
             * Requête d'insertion du jeton de sécurité
             */
            $req = $db->prepare('INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())');
            $req->bindValue(':email', $email, PDO::PARAM_STR);
            $req->bindValue(':token', $token, PDO::PARAM_STR);
            $req->execute();
            $req->closeCursor();

            // Liens que l'utilisateur reçoit pour réinitialiser le mots de passe 
            $link = 'Bonjour, veuillez cliquer sur <a href="http://localhost/membres/reset.php?token=' . $token . '">ce lien</a> pour réinitialiser votre mote de passe.';

            // Créer le Transport
            // Nouvelle instance de la class swift_TmtpTransport ( 1er paramètre le smtp et 2ème paramètre le port )
            $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 465))
            // Mot de passe et username sur mailTrap par exemple
                ->setUsername('ba22b37ebd7715')
                ->setPassword('7df50bcba12507');

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message('Mot de passe oublié'))
            //Mettre l'adresse mail de l'expediteur du mail entre les '' de setFrom
                ->setFrom(['' => 'John Doe'])
                ->setTo([$email => $user->name])
                //Spécifié que l'on envoi du text/html pour que l'utilisateur puisse cliquer sur le lien de redirection
                ->addPart($link, 'text/html');;

            // Send the message
            $result = $mailer->send($message);

            if ($result) {
                $success = 'Un email vous a été envoyé avec des instructions.';
                unset($email);
            }
        }
    }
}

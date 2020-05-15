<?php
require 'db.php';
// Déconnexion de l'utilisateur et destruction des sessions
unset($_SESSION['user']);
session_destroy();
// Redirection sur la page d'accueil
header('Location: index.php');
<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Détruire la session
session_destroy();

// Rediriger vers la page de login
header('Location: login.php');
exit;
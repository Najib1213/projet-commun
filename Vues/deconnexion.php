<?php
require_once '../Modèles/session.php';

deconnexion();

// Redirection vers l'accueil (ou une autre page publique)
header('Location: Accueil.php');
exit;

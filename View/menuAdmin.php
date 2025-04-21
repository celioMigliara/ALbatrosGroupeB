<?php
// Inclusion du modèle User pour accéder à la fonction de comptage
require_once __DIR__ . '/../Model/User.php';

// Récupération du nombre de comptes utilisateurs en attente de validation
$nbComptesEnAttente = User::countUtilisateursEnAttente();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Institut Albatros</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accueil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <img src="asset/logo.png" alt="logo institut albatros">
            </div>
            <input type="checkbox" id="menu-toggle-admin" class="menu-toggle">
            <label for="menu-toggle-admin" class="burger-menu">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <nav class="navAdmin">
                <ul class="menuAdmin">
                    <li class="menu-deroulant">
                        <a href="#" class="bouton-menu">Demandes</a>
                        <ul class="sous-menu">
                            <li><a href="#">Gérer les tâches</a></li>
                            <li><a href="#">Gérer les demandes</a></li>
                        </ul>
                    </li>
                    <li class="menu-deroulant">
                        <a href="#" class="bouton-menu">Gestion</a>
                        <ul class="sous-menu">
                            <li><a href="#">Gérer les utilisateurs</a></li>
                            <li><a href="#">Gérer les sites</a></li>  
                            <li><a href="#">Gérer les bâtiments</a></li>
                            <li><a href="#">Gérer les endroits</a></li>
                        </ul>
                    </li>
                    <li class="menu-pc notif-container">
                        <a href="../Vue/ListeInscriptions.php">Valider les accès</a>
                        <?php if ($nbComptesEnAttente > 0): ?>
                            <!-- Badge rouge avec le nombre d’inscriptions en attente -->
                            <span class="notif-badge"><?= $nbComptesEnAttente ?></span>
                        <?php endif; ?>
                    </li>
                    <li class="menu"><a href="#">Historique</a></li>
                    <li class="menu"><a href="#">Feuille de route</a></li>
                </ul>
            </nav>
        </header>

        <!-- Zone de bienvenue avec le petit bonhomme vert kaki -->
        <section class="admin-header">
            <div class="admin-welcome">
                <div class="dropdown user-dropdown">
                    <label for="user-dropdown-toggle" class="dropdown-btn icon-only">
                        <i class="fas fa-user"></i>
                    </label>
                    <div class="dropdown-content">
                        <a href="parametres.php"><i class="fas fa-cog"></i> Paramètres</a>
                        <a href="deconnexion.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                    </div>
                </div>

                <div class="admin-text">
                    <h2>Bienvenue, <span class="admin-name">Alexandre</span> !</h2>
                    <p>Content de vous revoir.</p>
                </div>
            </div>
        </section>

        <main class="dashboard">
            <!-- NOTIFICATIONS -->
            <section class="notifications-section">
                <h2><i class="fas fa-bell"></i> Notifications récentes</h2>

                <div class="notification-card alert">
                    <div class="notification-header">
                        <h3><i class="fas fa-user-clock"></i> <?= $nbComptesEnAttente ?> inscriptions en attente</h3>
                        <span class="notification-badge"><?= $nbComptesEnAttente ?></span>
                    </div>
                    <p>Comptes utilisateurs à valider. Dernière demande il y a quelques minutes.</p>
                    <div class="notification-actions">
                        <a href="../Vue/ListeInscriptions.php" class="btn-view-demande">
                            <i class="fas fa-eye"></i> valider les accès
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

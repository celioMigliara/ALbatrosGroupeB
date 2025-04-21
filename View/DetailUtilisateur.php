<?php
// Inclusion du modèle User pour accéder aux données utilisateur
require_once __DIR__ . '/../Model/User.php';

// Vérifie si un ID utilisateur est passé dans l'URL et s'il est bien numérique
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID utilisateur invalide.");
}

// Conversion sécurisée de l'ID utilisateur
$idUtilisateur = (int) $_GET['id'];

// Récupération des infos de l'utilisateur via son ID
$utilisateur = User::getUtilisateurById($idUtilisateur);

// Si aucun utilisateur trouvé, message d'erreur
if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

// Récupération des bâtiments si l'utilisateur est de rôle "Utilisateur" (id_role = 3)
$batiments = [];
if ($utilisateur['id_role'] == 3) {
    $batiments = User::getBatimentsAssignes($idUtilisateur);
}

// Récupération de la liste des utilisateurs en attente pour navigation
$listeUtilisateurs = User::getIdsUtilisateursEnAttente();
$ids = array_column($listeUtilisateurs, 'id_utilisateur');
$index = array_search($idUtilisateur, $ids);

$prevId = $ids[$index - 1] ?? null;
$nextId = $ids[$index + 1] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'utilisateur</title>
    <link rel="stylesheet" href="./css/detailUtilisateur.css">
</head>
<body>


<!-- Conteneur principal de la fiche utilisateur -->
<div class="container">
    <!-- Avatar utilisateur -->
    <div class="avatar-container">
        <img src="./asset/iconusr_orange.png" alt="Avatar utilisateur">
    </div>

    <!-- Informations de l'utilisateur -->
    <div class="info-container">
        <h2><?= htmlspecialchars($utilisateur['prenom_utilisateur'] . ' ' . $utilisateur['nom_utilisateur']) ?></h2>
        <p><?= htmlspecialchars($utilisateur['mail_utilisateur']) ?></p>
        <p class="role">Poste : <?= htmlspecialchars(User::getNomRole($utilisateur['id_role'])) ?></p>

        <!-- Liste des bâtiments (si rôle utilisateur) -->
        <?php if (!empty($batiments)) : ?>
            <div class="building-list">
                <strong>Bâtiment(s) assigné(s) :</strong>
                <ul>
                    <?php foreach ($batiments as $bat) : ?>
                        <li>🏢 <?= htmlspecialchars($bat) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <!-- Boutons d'action -->
    <div class="actions">
        <!-- Valider : lien direct -->
        <a href="../Controller/validerInscription.php?id=<?= $utilisateur['id_utilisateur'] ?>" class="btn-action accept" title="Valider">&#10003;</a>
        <!-- Refuser : ouvre un popup intégré -->
        <button class="btn-action refuse" onclick="ouvrirPopupRefus()" title="Refuser">&#10005;</button>
    </div>

    <!-- Formulaire de refus stylé intégré dans la carte -->
    <div class="popup-refus" id="popup-refus">
        <p>❗ Êtes-vous sûr de refuser cette inscription ?</p>
        <textarea id="motif-refus" placeholder="Veuillez indiquer un motif..." rows="4"></textarea>
        <div class="popup-btns">
            <button class="popup-btn confirm" onclick="envoyerRefus(<?= $utilisateur['id_utilisateur'] ?>)">Envoyer</button>
            <button class="popup-btn cancel" onclick="fermerPopupRefus()">Annuler</button>
        </div>
    </div>

    <!-- Flèches navigation -->
    <div class="nav-arrows">
        <?php if ($prevId): ?>
            <a href="DetailUtilisateur.php?id=<?= $prevId ?>" class="arrow left" title="Précédent">&#8592;</a>
        <?php else: ?>
            <span class="arrow left disabled">&#8592;</span>
        <?php endif; ?>

        <?php if ($nextId): ?>
            <a href="DetailUtilisateur.php?id=<?= $nextId ?>" class="arrow right" title="Suivant">&#8594;</a>
        <?php else: ?>
            <span class="arrow right disabled">&#8594;</span>
        <?php endif; ?>
    </div>
</div>

<!-- ✅ Message validation -->
<?php if (isset($_GET['status']) && $_GET['status'] === "validation") : ?>
<script>
    window.onload = function() {
        alert("✅ Validation confirmée ! Un mail a été envoyé à l'utilisateur.");
    };
</script>
<?php endif; ?>

<!--  Message validation refus-->
<?php if (isset($_GET['status']) && $_GET['status'] === "refus") : ?>
<script>
    window.onload = function() {
        alert("❌ Refus envoyé avec succès ! Un mail a été transmis à l'utilisateur.");
    };
</script>
<?php endif; ?>


<!-- 🔧 SCRIPTS POPUP -->
<script>
function ouvrirPopupRefus() {
    document.getElementById("popup-refus").style.display = "block";
}

function fermerPopupRefus() {
    document.getElementById("popup-refus").style.display = "none";
}

function envoyerRefus(userId) {
    const motif = document.getElementById("motif-refus").value.trim();
    if (!motif) {
        alert("⚠️ Merci de remplir le motif du refus.");
        return;
    }
    // Redirige vers le controller avec l'ID + motif
    window.location.href = `../Controller/refuserInscription.php?id=${userId}&motif=${encodeURIComponent(motif)}`;
}
</script>

</body>
</html>

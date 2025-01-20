<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Gestion de mot de passe pour l'accès à la galerie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gallery_password = 'Chamrousse25'; // Définissez ici le mot de passe de la galerie

    if ($_POST['gallery_password'] === $gallery_password) {
        $_SESSION['gallery_access'] = true;
        header("Location: galerie.php");
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}

// Vérifier l'accès à la galerie
if (!isset($_SESSION['gallery_access'])) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Galerie protégée</title>
    </head>
    <body>
        <h1>Galerie protégée</h1>
        <?php if (!empty($error)) : ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="gallery_password">Entrez le mot de passe :</label>
            <input type="password" id="gallery_password" name="gallery_password" required>
            <button type="submit">Accéder</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie</title>
</head>
<body>
    <h1>Photos et vidéos des séjours</h1>
    <p>Bienvenue dans la galerie protégée.</p>
    <p><a href="accueil.php">Retour à l'accueil</a></p>
</body>
</html>

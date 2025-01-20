<?php
// Démarrer la session
session_start();

// Connexion à la base de données
$host = '127.0.0.1';
$dbname = 'ski_alpin';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérification de l'existence de l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        $error = "Cet e-mail est déjà utilisé.";
    } else {
        // Insérer le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->execute(['email' => $email, 'password' => $hashed_password]);

        // Rediriger après inscription
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: accueil.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Séjours Ski Alpin</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Bleu clair pour une ambiance froide */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('ski-background.jpg'); /* Image de fond */
            background-size: cover;
            background-position: center;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .form-container h1 {
            text-align: center;
            color: #0056b3; /* Bleu foncé */
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .form-container p {
            text-align: center;
            margin-top: 15px;
        }

        .form-container p a {
            color: #007bff;
            text-decoration: none;
        }

        .form-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Inscription</h1>
        <?php if (!empty($error)) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" placeholder="Entrez votre e-mail" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="Créez un mot de passe" required>

            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà inscrit ? <a href="index.php">Connectez-vous ici</a>.</p>
    </div>
</body>
</html>

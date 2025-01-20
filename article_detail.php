<?php
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

// Récupérer l'article en fonction de l'ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT title, content, image_path, created_at FROM articles WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Article introuvable !");
    }
} else {
    die("ID d'article invalide !");
}
?>

<!DOCTYPE html>
<html lang="fr">

    <header>
        <!-- Vide -->
    </header>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            color: #1c3a59;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .article-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            line-height: 1.8;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #005b76;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s ease-in-out;
        }

        .back-link:hover {
            background-color: #003c4f;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?= htmlspecialchars($article['image_path']) ?>" alt="Image de l'article" class="article-image">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
        <small>Publié le <?= htmlspecialchars($article['created_at']) ?></small>
        <br>
        <a href="accueil.php" class="back-link">Retour aux articles</a>
        <a href="articles/detail.php?id=<?= $article['id'] ?>">Lire la suite</a>
    </div>
</body>
</html>

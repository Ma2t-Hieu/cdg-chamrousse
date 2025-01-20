<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

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

    // Récupérer les détails de l'article
    $stmt = $pdo->prepare("SELECT title, content, image_path, created_at FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($article) {
        $title = htmlspecialchars($article['title']);
        $content = htmlspecialchars($article['content']);
        $image_path = htmlspecialchars($article['image_path']);
        $created_at = date("d M Y", strtotime($article['created_at']));
    } else {
        echo "Article introuvable.";
    }
} else {
    echo "ID d'article non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Détail de l\'article' ?></title>
    <style>
        /* Styles globaux */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff; /* Blanc neige */
            color: #1c3a59; /* Couleur bleu froid */
            margin: 0;
            padding: 0;
            background-image: url('https://example.com/mountains.jpg'); /* Image de fond montagne */
            background-size: cover;
            background-position: center;
            height: 100%;
            overflow-x: hidden;
        }

        /* Header */
        header {
            text-align: center;
            padding: 38px;
            background-image: url('./images/banneraccueil.png'); /* Image locale */
            background-size: cover;
            background-position: center;
            color: white;
            height: 330px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 3rem;
            color: #005b76; /* Couleur bleu glacier */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Footer */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #005b76;
            color: white;
        }

        footer p {
            margin: 0;
            font-size: 1rem;
        }

        /* Styles article */
        .article-content {
            display: flex;
            gap: 20px;
            margin: 20px;
            align-items: flex-start;
        }

        .article-image {
            width: 400px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .text-content {
            flex: 1;
        }

        .text-content p {
            margin: 10px 0;
        }

        .article-container {
            
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

/* Boutons de navigation */
.navigation-buttons {
    margin: 20px;
    text-align: center;
}

.btn {
    background-color: #005b76;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    margin: 10px;
    transition: background-color 0.3s ease;
    text-decoration: none;
}

.btn:hover {
    background-color: #003c4f;
}



    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <!-- Vide ou vous pouvez ajouter un logo ou titre -->
    </header>





    <!-- Container de l'article -->
    <div class="article-container">
    <?php if (isset($article)): ?>
        <div class="article-content">
            <img src="<?= $image_path ?>" alt="Image de l'article" class="article-image">
            <div class="text-content">
                <h1><?= $title ?></h1>
                <p><strong>Date :</strong> <?= $created_at ?></p>
                <p><?= nl2br($content) ?></p>
            </div>
        </div>
        <div class="navigation-buttons">
            <button onclick="window.history.back();" class="btn">← Revenir à la page précédente</button>
            <button onclick="window.location.href='/ski_alpin/accueil.php';" class="btn">🏠 Retour à l'accueil</button>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer>
    <p>&copy; <?= date("Y") ?> ⛷️ Séjours Ski Alpin. Tous droits réservés. <b><i>🏀Prof2Balles🏀</i></b> Production.</p>
</footer>

</body>
</html>

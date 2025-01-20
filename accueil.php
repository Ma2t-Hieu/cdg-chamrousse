<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'ski_alpin';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les articles du séjour 2025
$stmt_articles = $pdo->query("SELECT id, title, content, image_path, created_at FROM articles WHERE YEAR(created_at) = 2025 ORDER BY created_at DESC");
$articles_2025 = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les documents utiles
$stmt_documents = $pdo->query("SELECT id, title, file_path FROM documents ORDER BY created_at DESC");
$documents_utiles = $stmt_documents->fetchAll(PDO::FETCH_ASSOC);

// Mot de passe pour accéder aux articles 2025
$mot_de_passe_correct = 'Chamrousse25';

// Variables pour gérer l'état de l'onglet actif
$tab_actif = isset($_POST['tab']) ? $_POST['tab'] : 'presentation'; // Onglet par défaut : "presentation"
$message_erreur = ''; // Message d'erreur par défaut
$acces_articles = false;

// Vérification du mot de passe
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    $mot_de_passe_saisi = $_POST['password'];
    if ($mot_de_passe_saisi === $mot_de_passe_correct) {
        $acces_articles = true;
    } else {
        $message_erreur = 'Mot de passe incorrect. Veuillez réessayer.';
    }
    $tab_actif = 'articles'; // Rester sur l'onglet "Articles" après soumission
}

// Nombre d'articles par page
$articles_par_page = 10;

// Page actuelle
$page_actuelle = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calcul de l'offset
$offset = ($page_actuelle - 1) * $articles_par_page;

// Récupérer les articles avec pagination
$stmt_articles = $pdo->prepare("SELECT id, title, content, image_path, created_at FROM articles WHERE YEAR(created_at) = 2025 ORDER BY created_at DESC LIMIT :offset, :limit");
$stmt_articles->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt_articles->bindValue(':limit', $articles_par_page, PDO::PARAM_INT);
$stmt_articles->execute();
$articles_2025 = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);

// Calcul du nombre total d'articles
$stmt_total = $pdo->query("SELECT COUNT(*) FROM articles WHERE YEAR(created_at) = 2025");
$nombre_total_articles = $stmt_total->fetchColumn();

// Calcul du nombre total de pages
$nombre_pages = ceil($nombre_total_articles / $articles_par_page);

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = htmlspecialchars($_GET['q']);
    $stmt_articles = $pdo->prepare("SELECT id, title, content, image_path, created_at FROM articles WHERE title LIKE :q OR content LIKE :q ORDER BY created_at DESC");
    $stmt_articles->execute([':q' => "%$q%"]);
    $articles_2025 = $stmt_articles->fetchAll(PDO::FETCH_ASSOC);
}





?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Séjour Ski Alpin</title>
    <style>
        /* Styles pour l'ambiance froide et montagne */
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
            overflow-x: hidden; /* Empêcher le défilement horizontal */
        }

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

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9); /* Fond blanc avec opacité */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .tab-button {
            background-color: #005b76;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin: 0 10px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .tab-button:hover {
            background-color: #003c4f;
        }

        .section {
            display: none;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .section.active {
            display: block;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        .articles-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Colonnes dynamiques */
    gap: 20px;
    justify-content: center;
}

.article-card {
    width: 250px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    transition: transform 0.2s ease-in-out;
}

.article-card:hover {
    transform: scale(1.05);
}

.article-card a {
    text-decoration: none;
    color: inherit;
}

.article-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

.article-title {
    padding: 10px;
    font-size: 1.2rem;
    color: #1c3a59;
}


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


.search-container {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.search-container form {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-container input[type="text"] {
    border: none;
    outline: none;
    padding: 10px;
    font-size: 14px;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.search-container button {
    background-color: #005b76;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}

.search-container button:hover {
    background-color: #003c4f;
}

@media (max-width: 768px) {
    .search-container {
        top: 10px;
        right: 10px;
    }

    .search-container input[type="text"] {
        padding: 8px;
    }

    .search-container button {
        padding: 8px 10px;
    }
}


    </style>
</head>
<body>

    <header>
        <!-- Vide -->
    
    </header>

    <div class="container">
        <!-- Onglets -->
        <div class="tabs">
            <form method="POST" id="presentationForm" style="display:inline;">
                <input type="hidden" name="tab" value="presentation">
                <button type="submit" class="tab-button"><b>ACCUEIL</b></button>
            </form>
            <form method="POST" id="articlesForm" style="display:inline;">
                <input type="hidden" name="tab" value="articles">
                <button type="submit" class="tab-button"><b>ARTICLES 2025</b></button>
            </form>
            <form method="POST" id="documentsForm" style="display:inline;">
                <input type="hidden" name="tab" value="documents">
                <button type="submit" class="tab-button"><b>DOCUMENTS UTILES</b></button>
            </form>
        </div>

        <!-- Sections -->
        <div id="presentation" class="section <?= $tab_actif === 'presentation' ? 'active' : '' ?>">
            <h2>Le Séjour Ski Alpin</h2>
            <p>Venez vivre une expérience inoubliable dans les Alpes !</p>
        </div>

        <div id="articles" class="section <?= $tab_actif === 'articles' ? 'active' : '' ?>">
    <h2>Articles du Séjour 2025 - 16 au 22 Mars 2025</h2>
    <?php if ($acces_articles === false): ?>
        <form method="POST" action="">
            <input type="hidden" name="tab" value="articles">
            <label for="password">Entrez le mot de passe pour accéder aux articles 2025 :</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Soumettre</button>
        </form>
        <?php if (!empty($message_erreur)): ?>
            <p class="error-message"><?= htmlspecialchars($message_erreur) ?></p>
        <?php endif; ?>
    <?php else: ?>
        <div class="articles-container">
            <?php foreach ($articles_2025 as $article): ?>
                <div class="article-card">
    <img src="<?= htmlspecialchars($article['image_path']) ?>" alt="Image de l'article" class="article-image">
    <h3><?= htmlspecialchars($article['title']) ?></h3>
    <a href="articles/detail.php?id=<?= $article['id'] ?>">Lire la suite</a>
</div>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


        <div id="documents" class="section <?= $tab_actif === 'documents' ? 'active' : '' ?>">
            <h2>Documents Utiles</h2>
            <ul>
                <?php foreach ($documents_utiles as $document): ?>
                    <li>
                        <a href="<?= htmlspecialchars($document['file_path']) ?>" target="_blank"><?= htmlspecialchars($document['title']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>





    <footer>
        <p>&copy; <?= date("Y") ?> ⛷️ 2025 Séjours Ski Alpin. Tous droits réservés. <b><i>🏀Prof2Balles🏀</i></b> Production.</p>
    </footer>
</body>
</html>





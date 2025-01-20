<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Séjours Ski Alpin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Bleu clair pour une ambiance froide */
            color: #333;
            background-image: url(https://wallpapercave.com/wp/wp13208219.jpg);
            background-repeat: no-repeat; 
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            height: 200px;
            text-align: center;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        header h1 {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        main {
            padding: 20px;
            flex: 1; /* Permet à la section principale de s'étendre pour pousser le footer en bas */
        }

        .form-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-container h2 {
            text-align: center;
        }

        .form-container label {
            display: block;
            margin: 10px 0 5px;
        }

        .form-container input {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 10px;
            background: #333;
            color: white;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur le site dédié au stage ski alpin 2025</h1>
    </header>

    <main>
        <div class="form-container">
            <h2>Connexion / Inscription</h2>
            <form action="login.php" method="POST">
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre e-mail" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

                <button type="submit">Se connecter</button>
            </form>
            <p style="text-align:center;">Pas encore inscrit ? <a href="register.php">Inscrivez-vous ici</a>.</p>
        </div>
    </main>

    <footer>
        &copy; ⛷️ 2025 Séjours Ski Alpin. Tous droits réservés. <b><i>🏀Prof2Balles🏀</i></b> Production.
    </footer>
</body>
</html>

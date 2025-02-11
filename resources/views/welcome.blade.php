<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de Connexion</title>

    <style>
        /* Style général de la page */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        /* Flexbox pour les boutons */
        .row {
            display: flex;
            flex-direction: column;
            gap: 20px; /* Espace entre les boutons */
            justify-content: center;
        }

        /* Style des boutons */
        .btn {
            width: 100%;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-align: center;
        }

        /* Style pour les boutons */
        .btn-custom {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
            /* Pour donner un effet de transition */
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-3px); /* Effet d'élévation sur hover */
        }

        .btn-custom:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choisir un type de connexion</h1>
        <div class="row">
            <!-- Bouton pour se connecter en tant qu'Admin -->
            <a href="{{ route('admin.login') }}" class="btn btn-custom btn-lg">
                Connexion Admin
            </a>

            <!-- Bouton pour se connecter en tant que Client -->
            <a href="{{ route('client.login') }}" class="btn btn-custom btn-lg">
                Connexion Client
            </a>
        </div>
    </div>
</body>
</html>

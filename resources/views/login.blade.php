<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0; /* Gris clair */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .alert {
            background-color: #ffcccc;
            color: red;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-actions {
            margin-top: 20px;
        }
        .button {
            background-color: #222422;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .button:hover {
            background-color: #171817;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Connexion avec votre numéro de téléphone</h2>

        <!-- Afficher les messages d'erreur -->
        @if(session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ url('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="numerotelephone">Numéro de téléphone :</label>
                <input type="text" name="numerotelephone" id="numerotelephone" placeholder="Entrez votre numéro" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Se connecter</button>
            </div>
        </form>
    </div>

</body>
</html>

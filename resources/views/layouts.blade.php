<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Coworking')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        .navbar {
            background-color: #2c2c2c;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between; /* Espace entre le logo et les liens */
            align-items: center;
        }
        
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            padding: 10px;
            transition: background-color 0.3s, color 0.3s;
        }
        
        .navbar-brand {
            font-weight: bold; /* Mettre en gras */
            font-size: 1.2em;
        }
        
        /* Alignement des liens et bouton à droite */
        .navbar-links {
            display: flex;
            align-items: center;
        }
        
        .navbar-links a {
            margin-left: 20px; /* Espacement entre les liens */
        }
        
        /* Effet au survol */
        .navbar a:hover {
            background-color: #575757;
            border-radius: 5px;
        }
        
        /* Styles pour le bouton Se déconnecter */
        .btn-logout {
            background-color: #dc3545; /* Rouge */
            color: white; /* Texte blanc */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-transform: uppercase;
        }
        
        /* Effet au survol pour le bouton de déconnexion */
        .btn-logout:hover {
            background-color: #c82333; /* Rouge plus foncé */
            transform: translateY(-2px); /* Légère élévation */
        }
        
        

        .form-container {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 18px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2c2c2c;
            box-shadow: 0 0 5px rgba(44, 44, 44, 0.5);
        }

        .form-actions {
            text-align: center;
        }

        .form-actions button {
            padding: 12px 24px;
            background-color: #2c2c2c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .form-actions button:hover {
            background-color: #575757;
        }

        .form-actions button:active {
            transform: translateY(2px);
        }

        .form-check-input {
            transform: scale(1.5);
        }

        footer {
            background-color: #2c2c2c;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }

        .button {
            padding: 12px 24px;
            background-color: #2c2c2c;
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .button:hover {
            background-color: #575757;
            transform: translateY(-2px);
        }

        .button:active {
            background-color: #1a1a1a;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
        }

        form .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        form .btn-primary {
            background-color: #2c2c2c;
            color: white;
            border-radius: 5px;
            border: none;
            padding: 12px 24px;
            transition: background-color 0.3s;
        }

        form .btn-primary:hover {
            background-color: #575757;
        }

        form .btn-primary:focus {
            box-shadow: 0 0 5px rgba(44, 44, 44, 0.5);
        }

        form .row {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #433e3e;
        }

        table th {
            background-color: #2c2c2c;
            color: #000000;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #e0e0e0;
        }

        form input[type="date"],
        form input[type="time"],
        form input[type="number"] {
            padding: 15px;
            font-size: 18px;
            width: 100%;
            max-width: 350px;
        }

        .form-check-label {
            font-size: 20px;
        }

        h5 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 25%;
            padding: 12px;
            font-size: 18px;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: center;
        }

        .table-custom th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 12px;
            font-weight: bold;
        }

        .table-custom td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .table-custom tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-custom tr:hover {
            background-color: #f1f1f1;
        }

        .status-libre {
            background-color: #4CAF50;
            color: white;
        }

        .status-reserve {
            background-color: #9C27B0;
            color: white;
        }

        .status-occupe {
            background-color: #b03434;
            color: white;
        }

        .btn-reserver {
            background-color: #0d0e0d;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-reserver:hover {
            background-color: #3e413f;
        }

        .legend {
            margin-top: 20px;
            font-size: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .legend p {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend p::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .legend p:nth-child(1)::before {
            background-color: #b03434;
        }

        .legend p:nth-child(2)::before {
            background-color: #28a745;
        }

        .legend p:nth-child(3)::before {
            background-color: #9C27B0;
        }
          /* Style minimaliste pour le tableau */
          .simple-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: center;
            border-radius: 8px; 
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            background-color: #fff; 
        }
        
        .simple-table th,
        .simple-table td {
            padding: 12px;
            border: 1px solid #ddd;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        
        .simple-table th {
            background-color: #000000; 
            color: #fff;
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        
        .simple-table tr:nth-child(even) {
            background-color: #f7f7f7; 
        }
        
        .simple-table tr:hover {
            background-color: #f1f1f1; 
            transform: scale(1.02);
        }
        
        .simple-table td {
            font-family: 'Arial', sans-serif; 
            color: #333; 
        }
        
        .simple-table td:hover {
            background-color: #f2f2f2; 
        }
        
        .simple-table th, .simple-table td {
            border-radius: 4px;
        }
        
        .simple-table td {
            font-size: 14px; 
        }
        
        .simple-table td:first-child {
            font-weight: bold;
        }
        
.alert {
    padding: 15px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 20px;
    position: relative;
    transition: opacity 0.3s ease-in-out;
}

/* Alerte pour les erreurs */
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 5px solid #dc3545;
}

/* Icône d'avertissement */
.alert-danger::before {
    content: "⚠️ ";
    font-size: 18px;
    margin-right: 8px;
}

/* Liste des erreurs */
.alert-danger ul {
    margin: 0;
    padding-left: 20px;
}

.alert-danger li {
    list-style: none;
    padding: 5px 0;
}

/* Animation pour les erreurs */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert {
    animation: fadeIn 0.5s ease-in-out;
}

/* Bouton de fermeture (optionnel) */
.close-btn {
    position: absolute;
    top: 8px;
    right: 15px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #721c24;
}

.close-btn:hover {
    color: #dc3545;
}
.btn-payer {
    background-color: #1e1e1f; 
    color: white; 
    border: 1px solid #1e1e1f;
    padding: 10px 20px;
    border-radius: 5px; 
    font-size: 14px; 
    font-weight: bold; 
    text-transform: uppercase; 
    text-align: center;
    transition: background-color 0.3s ease, transform 0.3s ease; 
}

.btn-payer:hover {
    background-color: #000000; 
    border-color: #000000; 
    color: white;
    transform: translateY(-2px); 
}

.btn-payer:focus {
    outline: none; 
    box-shadow: 0 0 0 2px rgba(12, 12, 12, 0.5); 
    color: white; 
}


.btn-annuler {
    background-color: #dc3545; 
    color: white; 
    border: 1px solid #dc3545; 
    padding: 10px 20px; 
    border-radius: 5px; 
    font-size: 14px; /* Taille de la police */
    font-weight: bold; /* Texte en gras */
    text-transform: uppercase; /* Texte en majuscules */
    text-align: center; /* Centrer le texte */
    transition: background-color 0.3s ease, transform 0.3s ease; /* Animation */
}

.btn-annuler:hover {
    background-color: #c82333; 
    border-color: #c82333; 
    transform: translateY(-2px); 
}
.btn-annuler:focus {
    outline: none; 
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.5); 
}

.btn-valider-paiement {
    background-color: #5f5d5d; 
    color: white; 
    border: 1px solid #777877;
    padding: 12px 24px; 
    border-radius: 5px; 
    font-size: 16px; 
    font-weight: bold; 
    text-transform: uppercase; 
    text-align: center; 
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-valider-paiement:hover {
    background-color: #1c201d; 
    border-color: #212322; 
    transform: translateY(-2px); 
}

.btn-valider-paiement:focus {
    outline: none; 
    box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.5); 
}
.status-cell .cross {
    color: red;
    font-size: 20px;
    font-weight: bold;
}

        
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Coworking</a>
        
        <div class="navbar-links">
            <!-- Lien "Mes Réservations" -->
            <a href="{{ route('reservations.liste') }}">Mes Réservations</a>
            <!-- Lien "Espace de travail" -->
            <a href="{{ route('listeEsapce') }}">Espace de travail</a>
            <a href="{{ route('reservations.details') }}">Details reservations</a>
            
            
            <!-- Vérifier si un client est connecté -->
            @if(Session::has('client_id'))
                <!-- Bouton de déconnexion à la fin -->
                <a href="{{ route('logout') }}" class="btn-logout">Deconnexion</a>
            @else
                <a href="{{ route('login') }}">Se connecter</a>
            @endif
        </div>
    </nav>
    
    

    <div class="container">
        @yield('content')
    </div>
    <footer>&copy; 2025 Mon Site Web Moderne. Tous droits réservés.</footer>
</body>
</html>
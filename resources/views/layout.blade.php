<!DOCTYPE html>
<html lang="en">
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

        /* Body styles */
        body {
          background-color: #f4f4f4;
          color: #333;
          line-height: 1.6;
          display: flex;
          flex-direction: column;
          min-height: 100vh;
        }

        /* Navbar styles */
        .navbar {
          background-color: #2c2c2c;
          color: #fff;
          padding: 10px 20px;
          display: flex;
          justify-content: space-between; /* Espace entre le logo et les menus */
          align-items: center;
        }

        .navbar-brand {
          font-weight: bold;
          font-size: 1.2em;
        }

        .navbar-menu {
          display: flex;
          gap: 10px;
        }

        .navbar a {
          color: #fff;
          text-decoration: none;
          padding: 10px;
          transition: background-color 0.3s, color 0.3s;
        }

        .navbar a:hover {
          background-color: #575757;
          border-radius: 5px;
        }

        /* Container styles */
        .container {
          width: 100%;
          max-width: 1200px;
          margin: 20px auto;
          padding: 0 10px;
        }

        /* Form styles */
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
          padding: 10px;
          border: 1px solid #ddd;
          border-radius: 5px;
          font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
          border-color: #2c2c2c;
          box-shadow: 0 0 5px rgba(44, 44, 44, 0.5);
        }

        /* Table styles */
        table {
          width: 100%;
          border-collapse: collapse;
          margin: 20px 0;
          font-size: 16px;
          text-align: left;
        }

        table th,
        table td {
          padding: 12px;
          border: 1px solid #ddd;
        }

        table th {
          background-color: #2c2c2c;
          color: #fff;
        }

        table tr:nth-child(even) {
          background-color: #f9f9f9;
        }

        table tr:hover {
          background-color: #e0e0e0;
        }

        /* Footer styles */
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

        /* Button styles */
        .button {
          padding: 10px 20px;
          background-color: #2c2c2c;
          color: #fff;
          text-decoration: none;
          font-size: 16px;
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

        /* Alertes de succès et d'erreur */
        .alert {
          padding: 15px;
          margin-bottom: 20px;
          border-radius: 5px;
        }

        .alert-success {
          background-color: #d4edda;
          color: #155724;
          border-color: #c3e6cb;
        }

        .alert-danger {
          background-color: #f8d7da;
          color: #721c24;
          border-color: #f5c6cb;
        }

        .btn-danger {
          background-color: #dc3545;
          color: white;
          border: none;
          padding: 10px 20px;
          font-size: 1rem;
          border-radius: 5px;
          cursor: pointer;
          transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
          background-color: #c82333;
        }

        .btn-danger:focus {
          outline: none;
          box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
        }

        h2 {
          font-size: 1.5rem;
          font-weight: bold;
          color: #333;
          margin-bottom: 20px;
        }

        /* Page centered */
        .centered-page {
          display: flex;
          justify-content: center;
          align-items: center;
          height: calc(100vh - 60px); /* Moins la hauteur de la navbar */
        }

        /* Button style */
        .btn-primary {
          background-color: #43484d !important;
          color: #fff !important;
          font-size: 16px !important;
          font-weight: bold !important;
          padding: 10px 20px !important;
          border: none !important;
          border-radius: 5px !important;
          cursor: pointer !important;
          transition: background-color 0.3s, transform 0.2s !important;
        }

        .btn-primary:hover {
          background-color: #131416 !important;
          transform: translateY(-2px) !important;
        }

        .btn-primary:active {
          background-color: #1a1a1b !important;
          transform: translateY(2px) !important;
        }

        input[type="date"] {
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          background-color: white;
          border: 1px solid #ddd;
          padding: 10px;
          border-radius: 5px;
          font-size: 16px;
          cursor: pointer;
          width: 100%;
        }

        input[type="date"]:focus {
          border-color: #2c2c2c;
          box-shadow: 0 0 5px rgba(44, 44, 44, 0.5);
          outline: none;
        }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar">
      <a href="#" class="navbar-brand">Coworking</a>
      <div class="navbar-menu">
        <a href="{{ route('reservations.liste.total') }}" class="navbar-link">Valider Paiements</a>
        <a href="{{ route('reservations.liste.rehetra') }}" class="navbar-link"> Liste des réservations</a>
        <a href="{{ route('statistiques.topCreneaux') }}" class="navbar-link">Statistique</a>
        <a href="{{ route('chiffre.affaireRehetra') }}" class="navbar-link">chiffre d'Affaire</a>
        <a href="{{ route('import.form') }}">Import CSV</a>
        <a href="{{ route('reset.page') }}" class="navbar-link">Réinitialiser </a>
        <a href="{{ route('options.index') }}" class="navbar-link">Liste des options</a>
        <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="navbar-link btn btn-danger">Déconnexion</button>
        </form>
      </div>
    </nav>

    <!-- Contenu spécifique de la page -->
    <div class="container">
      @yield('content')  
    </div>  
    <!-- Footer -->
    <footer>&copy; 2025 Mon Site Web Moderne. Tous droits réservés.</footer>
  </body>
</html>

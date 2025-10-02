<?php
require __DIR__ . '/../config.php';

if (isset($_POST['submit_logout'])) {
    logout();
    header('Location: /groupy/index.php'); 
    exit();
}
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? "Groupy" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
          crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/groupy/">Groupy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="features.php">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="pricing.php">Pricing</a></li>
                </ul>

                <div class="ms-auto d-flex gap-2">
                    <?php if (!isset($_SESSION['connectedUser'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                Inscription
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item text-primary" href="/groupy/Views/User/formRegClient.php">Inscription <strong>client</strong></a></li>
                                <li><a class="dropdown-item text-primary" href="/groupy/Views/User/formRegVendeur.php">Inscription <strong>professionel</strong></a></li>
                            </ul>
                        </div>
                        <a href="/groupy/Views/User/formco.php" class="btn btn-outline-primary">Connexion</a>
                    <?php endif; ?>
                    

                    <?php if (isset($_SESSION['connectedUser'])): ?>
                        <a href="/groupy/Views/User/dashboard.php" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" 
                                class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 
                                        8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-
                                        .832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 
                                        1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                        </a>
                        <form action="#" method="post">
                            <button class="btn btn-danger" type="submit" name="submit_logout">
                                Déconnexion
                            </button>
                        </form>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="container my-4">
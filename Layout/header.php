<?php
require __DIR__ . '/../config.php';

if (isset($_POST['submit_logout'])) {
    csrf_verify();
    logout();
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? "Groupy" ?></title>
    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
    crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- important pour le footer -->
    <style> 
        html, body {
            min-height: 100vh;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main.container {
            flex: 1;
        }
    </style>

</head>
<body class="bg-light">
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Groupy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex gap-2">
                    <?php if (!isset($_SESSION['connectedUser'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                Inscription
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item text-primary" href="<?= BASE_URL ?>/Views/User/formRegClient.php">Inscription <strong>client</strong></a></li>
                                <li><a class="dropdown-item text-primary" href="<?= BASE_URL ?>/Views/User/formRegVendeur.php">Inscription <strong>professionel</strong></a></li>
                            </ul>
                        </div>
                        <a href="<?= BASE_URL ?>/Views/User/formco.php" class="btn btn-outline-primary">Connexion</a>
                    <?php endif; ?>
                    

                    <?php if (isset($_SESSION['connectedUser'])): ?>
                        <a href="<?= BASE_URL ?>/Views/User/dashboard.php" class="btn btn-outline-secondary">
                            <i class="bi bi-person-circle"></i>
                        </a>
                        <form action="#" method="post">
                            <?= csrf_field() ?>
                            <button class="btn btn-danger" type="submit" name="submit_logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="container my-4">
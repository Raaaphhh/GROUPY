<?php 
require '../../Layout/header.php'; 

if (!isset($_SESSION['connectedUser'])) {
    header('Location: /groupy/Views/User/formco.php'); 
    exit();
}

$role = get_role($_SESSION['connectedUser']['id_user']);
if ($role === "client") {
    echo "ADMIN";
}
elseif ($role === "vendeur") {
    echo "VENDEUR";
}

$title = "Dashboard - Groupy"; 
?>

<body class="bg-light text-center">
<h1>Espace utilisateur</h1>

<div class="container mt-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Profil</h5>
                    <a href="/groupy/Views/User/profil.php" class="btn btn-primary">Voir Profil</a>
                </div>
            </div>
        </div>

    </div>
</div>
</body>

<?php require '../../Layout/footer.php'; ?>

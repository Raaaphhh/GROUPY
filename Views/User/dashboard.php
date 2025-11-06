<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../Layout/header.php'; 
// require ''; 

if (!isset($_SESSION['connectedUser']) || $_SESSION['connectedUser']['motdepasse_change'] === 0) {
    header('Location: /groupy/Views/User/formco.php'); 
    exit();
}

$role = get_role($_SESSION['connectedUser']['id_user']);
$title = "Dashboard - Groupy"; 
?>

<body class="bg-light text-center">
<h1>Espace <?php echo $role ?> </h1>

<div class="container mt-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Profil</h5>
                    <a href="/groupy/Views/User/profil.php" class="btn btn-primary">Voir</a>
                </div>
            </div>
        </div>

        <?php if ($role === "client"): ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Factures</h5>
                    <a href="/groupy/Views/Client/factures.php" class="btn btn-warning">Voir</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($role === "vendeur"): ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestion des produits</h5>
                    <a href="/groupy/Views/Vendeur/actProdVend.php" class="btn btn-success">Voir</a>
                </div>
            </div>
        </div>    

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestion des préventes</h5>
                    <a href="/groupy/Views/Vendeur/actPrevVend.php" class="btn btn-success">Voir</a>
                </div>
            </div>
        </div>    
        <?php endif; ?>

        <?php if ($role === "gestionnaire" ): ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gestion</h5>
                    <a href="/groupy/Views/Gestionnaire/actProdGest.php" class="btn btn-success">Voir</a>
                </div>
            </div>
        </div>   
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Signalement</h5>
                    <a href="/groupy/Views/Gestionnaire/" class="btn btn-success">Voir</a>
                </div>
            </div>
        </div>   
        <?php endif; ?>

    </div>
</div>

</body>

<?php require '../../Layout/footer.php'; ?>

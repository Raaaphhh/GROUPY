<?php 
session_start();
require '../../Controlleur/UserController.php';
if (!isset($_SESSION['connectedUser'])) {
    header('Location: /groupy/Views/User/formco.php'); 
    exit();
}

if (isset($_POST['submit'])) {
    logout();
    header('Location: /groupy/index.php'); 
    exit();
}
$title = "Inscription Pro - Groupy"; 
include '../../Layout/header.php'; 
?>

<body class="bg-light text-center">
    <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['connectedUser']['prenom']); ?></h1>

    <form action="#" method="post">
        <button class="btn btn-danger" type="submit" name="submit">Deconnexion</button>
    </form>
</body>

<?php require '../../Layout/footer.php'; ?>
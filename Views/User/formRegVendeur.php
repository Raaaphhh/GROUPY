<?php 
session_start();
require '../../Controlleur/UserController.php';

if (isset($_POST['submit'])) {
  array_pop($_POST);
  try {
      register($_POST);
      header("Location: formCo.php");
      exit;
  } catch (Exception $e) {
      echo "Erreur d'inscription : " . $e->getMessage();
      exit;
  }
}

$title = "Inscription Pro - Groupy"; 
include '../../Layout/header.php'; 
?>

<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Inscription Vendeur</h4>
                </div>
                <div class="card-body">

                <form method="post" action="#">
                    <div class="row">
                        <!-- Colonne gauche : Infos vendeur -->
                        <div class="col-md-6">
                        <h5 class="mb-3 text-primary">Informations Vendeur</h5>

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="motdepasse" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="motdepasse" name="motdepasse" required>
                        </div>
                        </div>

                        <!-- Colonne droite : Infos entreprise -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-success">Informations Entreprise</h5>

                            <div class="mb-3">
                                <label for="nom_entreprise" class="form-label">Nom de l'entreprise</label>
                                <input type="text" class="form-control" id="nom_entreprise" name="nom_entreprise" required>
                            </div>

                            <div class="mb-3">
                                <label for="siret" class="form-label">Numéro SIRET</label>
                                <input type="text" class="form-control" id="siret" name="siret" required>
                            </div>

                            <div class="mb-3">
                                <label for="adresse_entreprise" class="form-label">Adresse de l'entreprise</label>
                                <input type="text" class="form-control" id="adresse_entreprise" name="adresse_entreprise" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_pro" class="form-label">Adresse Email Professionnelle</label>
                                <input type="email" class="form-control" id="email_pro" name="email_pro" required>
                            </div>
                        </div>
                    </div>

                    <!-- Conditions + bouton -->
                    <div class="mt-3">
                        <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="conditions" required>
                        <label class="form-check-label" for="conditions">J’accepte les conditions d’utilisation</label>
                        </div>

                        <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="submit">S'inscrire</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</body>

<?php include '../../Layout/footer.php'; ?>
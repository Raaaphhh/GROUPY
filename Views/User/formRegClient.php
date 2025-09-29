<?php 
session_start();
require '../../Controlleur/UserController.php';

if (isset($_POST['submit'])) {
  array_pop($_POST);
  try {
    register($_POST);
    exit;
  } catch (Exception $e) {
    echo "Erreur d'inscription : " . $e->getMessage();
    exit;
  }
}

$title = "Inscription Client - Groupy"; 
include '../../Layout/header.php'; 
?>

<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
          <h4>Inscription client</h4>
        </div>
        <div class="card-body">

          <form method="post" action="#">
            <div class="mb-3">
              <label for="nom" class="form-label">Nom</label>
              <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
            </div>

            <div class="mb-3">
              <label for="prenom" class="form-label">Prénom</label>
              <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
            </div>

            <div class="mb-3">
              <label for="adresse" class="form-label">Adresse</label>
              <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Entrez votre adresse" required>
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Téléphone</label>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Adresse Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="exemple@mail.com" required>
            </div>

            <div class="mb-3">
              <label for="motdepasse" class="form-label">Mot de passe</label>
              <input type="password" class="form-control" id="motdepasse" name="motdepasse" placeholder="********" required>
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="conditions" required>
              <label class="form-check-label" for="conditions">J’accepte les conditions d’utilisation</label>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary" name="submit">S'inscrire</button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>
</body>

<?php include '../../Layout/footer.php'; ?>
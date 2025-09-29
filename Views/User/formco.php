<?php 
session_start();
require '../../Controlleur/UserController.php';

if (isset($_POST['submit'])) {
  array_pop($_POST);
  try{
    login($_POST);
  }
  catch(Exception $e){
    echo 'Erreur : '.$e->getMessage();
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
          <h4>Connexion</h4>
        </div>
        <div class="card-body">
            <form method="post" action="#">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="exemple@mail.com" required>
                </div>

                <div class="mb-3">
                    <label for="motdepasse" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="motdepasse" name="motdepasse" placeholder="********" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="submit">Connexion</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>

<?php require '../../Layout/footer.php'; ?>

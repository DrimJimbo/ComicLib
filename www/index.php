<?php
  require_once "../include/db.php";
  
  session_start();
  if(isset($_SESSION['user'])){
    header("Location: search.php");
    exit();
  }
  $hash = "$2y$10$60B1gwO4Z3n2R4xcD4zROOnHCGj015VPPGR4Gd15c5OSqk8s3OkNq";
  if(isset($_POST['submit'])){
    $pass = $_POST['passwordInput'];
    $u = $_POST['usernameInput'];
    if(password_verify($pass,$hash) && $u == "jimbo" ){
      echo "success";
      $_SESSION['user'] = "jimbo";
      header("Location: search.php");
      exit();
    }
  }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComicLib | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ajout pour centrer verticalement le formulaire sur la page */
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #connectContent {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
    </style>
</head>
<body>

<main id="connectContent" class="bg-white shadow-sm rounded border">
    <section>
        <h2 class="text-center mb-4">Se connecter</h2>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="usernameInput" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" name="usernameInput" id="usernameInput" placeholder="Votre pseudo" required>
            </div>

            <div class="mb-3">
                <label for="passwordInput" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="passwordInput" id="passwordInput" placeholder="••••••••" required>
            </div>

            <div class="d-grid gap-2">
                <input type="submit" class="btn btn-primary" value="Se connecter" name="submit">
            </div>
        </form>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

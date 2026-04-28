<?php
    require_once "../include/db.php";
    require_once "../include/function.php";
    global $pdo;
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: index.php");
        exit();
    }
    if(isset($_POST['sub_lot'])){
        $lot = array($_POST['titre_lot'], $_POST['titre_en_lot'], $_POST['serie_lot'], $_POST['tome_lot'], $_POST['date_lot']);
        if(isset($_FILES['picture_lot']) && $_FILES['picture_lot']['error'] === 0){
            $namepic = downloadPic($_FILES['picture_lot'],$lot);
        }
        else{
            $namepic = "/images/default.png";
        }
        array_push($lot,$namepic);
        $comics = false;//array($_POST['titre_com'],$_POST['titre_en_com'],$_POST['serie_com'],$_POST['tome_com'],$_POST['date_com']);
        ajouterLot($lot,$comics);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComicLib | Ajouter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/add.js" defer></script>
    <style>
        body { background-color: #f4f7f6; }
        .main-container { max-width: 700px; margin-top: 50px; }
        .card { border: none; border-radius: 0 0 12px 12px; }
        .nav-tabs { border-bottom: none; }
        .nav-tabs .nav-link { 
            background-color: #e9ecef; 
            color: #495057; 
            border: none; 
            margin-right: 5px;
            border-radius: 10px 10px 0 0;
        }
        .nav-tabs .nav-link.active { 
            background-color: #ffffff; 
            font-weight: bold;
        }
        .nav-link#comic-tab.active { color: #0d6efd; border-top: 3px solid #0d6efd; }
        .nav-link#lot-tab.active { color: #198754; border-top: 3px solid #198754; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">ComicLib</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="search.php">Rechercher</a>
                <a class="nav-link active" href="#">Ajouter</a>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <h2 class="text-center mb-4">Ajouter à la collection</h2>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="comic-tab" data-bs-toggle="tab" data-bs-target="#comic-panel" type="button" role="tab">Un Comic</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lot-tab" data-bs-toggle="tab" data-bs-target="#lot-panel" type="button" role="tab">Un Lot</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade show active" id="comic-panel" role="tabpanel">
                <div class="card shadow p-4">
                    <form method="post" class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Titre français</label>
                            <input type="text" class="form-control" name="titre_com" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Titre anglais</label>
                            <input type="text" class="form-control" name="titre_en_com" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Série</label>
                            <input type="text" class="form-control" name="serie_com" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tome</label>
                            <input type="number" class="form-control" name="tome_com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Date de sortie</label>
                            <input type="date" class="form-control" name="date_com" required>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="sub_com" class="btn btn-primary w-100">Enregistrer ce Comic</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="lot-panel" role="tabpanel">
                <div class="card shadow p-4">
                    <form method="post" class="row g-3" id="ajout" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Titre français du lot</label>
                            <input type="text" class="form-control" name="titre_lot" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Titre anglais du lot</label>
                            <input type="text" class="form-control" name="titre_en_lot" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Série</label>
                            <input type="text" class="form-control" name="serie_lot">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tome</label>
                            <input type="number" class="form-control" name="tome_lot">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Date de sortie</label>
                            <input type="date" class="form-control" name="date_lot">
                        </div>
                        <div class="flex-grow-1 w-100">
                            <input type="file" name="picture_lot" class="form-control" id="inputProfilePic" accept="image/*">
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle"></i> Formats acceptés : JPG, PNG ou GIF. Taille max : 10 Mo.
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" name="sub_lot" class="btn btn-success w-100">Enregistrer ce Lot</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 mt-4">
                    <button id="btnadd" class="btn btn-success w-100">Ajouter un comic</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
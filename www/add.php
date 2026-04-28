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
        $lot = array(':titre' => $_POST['titre'],  ':id' => $_POST['serie'], ':tome' => $_POST['tome'], ':date' => $_POST['date']);
        if($_POST['serie'] == "autre"){
            $seriearray[':serie'] = $_POST['nomserie'];
            $seriearray[':tome'] = $_POST['nbtome'];
            $seriearray[':hs'] = $_POST['nbhs'];
            $id = addSerie($seriearray);
            $lot[':id'] = $id;
        }
        else{
            $id = getSerie($_POST['serie']);
            $lot[':id'] = $id;
        }
        if(isset($_FILES['picture_lot']) && $_FILES['picture_lot']['error'] === 0){
            $namepic = downloadPic($_FILES['picture_lot'],$lot);
        }
        else{
            $namepic = "/images/default.png";
        }
        $lot[':picture'] = $namepic;
        addComic($lot);
    }
    $query = "SELECT * FROM serie";
    $request = $pdo->prepare($query);
    try{
        $request->execute([]);
        $res = $request->fetchAll();
    }catch (PDOException $e){
        die("Erreur get serie : ".$e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComicLib | Ajouter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">ComicLib</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link " href="search.php">Rechercher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Ajouter</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <div class="card shadow p-4">
            <form method="post" class="row g-3" id="ajout" enctype="multipart/form-data">
                <h2 class="text-center mb-4">Ajouter à la collection</h2>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Titre</label>
                    <input type="text" class="form-control" name="titre" required>
                </div>
                <div class="col-md-12" id="divserie">
                    <label class="form-label fw-semibold" >Série</label>
                    <select type="text" class="form-control" name="serie" id="serie">
                        <option value="autre">Autre</option>
                    <?php
                        foreach($res as $row){
                            $serie = $row['nom_serie'];
                            echo "<option value='$serie'>$serie</option>";
                        }
                        echo "</select>";
                    ?>
                </div>
                <div class="col-md-4" id="divtome">
                    <label class="form-label fw-semibold">Tome</label>
                    <input type="number" class="form-control" name="tome" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Date de sortie</label>
                    <input type="date" class="form-control" name="date" required>
                </div>
                <div class="flex-grow-1 w-100">
                    <input type="file" name="picture_lot" class="form-control" id="inputProfilePic" accept="image/*" required>
                    <div class="form-text mt-2">
                        <i class="bi bi-info-circle"></i> Formats acceptés : JPG, PNG ou GIF. Taille max : 10 Mo.
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" name="sub_lot" class="btn btn-success w-100">Enregistrer ce Lot</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/add.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
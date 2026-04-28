<?php
    require_once "../include/db.php";
    require_once "../include/function.php";
    global $pdo;
    session_start();
    if(!isset($_SESSION['user'])){
        header("Location: index.php");
        exit();
    }
    $query = "SELECT * FROM lot_comics WHERE 1=1";
    $query2 = "SELECT * FROM comics WHERE 1=1";
    $param = [];
    if(isset($_POST['sub'])){
        if(!empty($_POST['titre'])){
            $query .=" AND titre_lot LIKE :titre";
            $query2 .=" AND titre_fr LIKE :titre";
            $param[':titre'] = "%".$_POST['titre']."%";
        }
        if(!empty($_POST['serie'])){
            $query = $query." AND serie_lot LIKE :serie";
            $query2 .=" AND serie_com LIKE :serie";
            $param[':serie'] = "%".$_POST['serie']."%";
        }
        if (!empty($_POST['annee'])) {
            $query .= " AND strftime('%Y', date_lot) = :annee";
            $query2 .= " AND strftime('%Y', date) = :annee";
            $param[':annee'] = $_POST['annee'];
        }
        if (!empty($_POST['mois'])) {
            $mois_map = [
                'Janvier'=>'01', 'Février'=>'02', 'Mars'=>'03', 'Avril'=>'04', 
                'Mai'=>'05', 'Juin'=>'06', 'Juillet'=>'07', 'Aout'=>'08', 
                'Septembre'=>'09', 'Octobre'=>'10', 'Novembre'=>'11', 'Décembre'=>'12'
            ];
            $num_mois = $mois_map[$_POST['mois']];
            $query .= " AND strftime('%m', date_lot) = :mois";
            $query2 .= " AND strftime('%m', date) = :mois";
            $param[':mois'] = $num_mois;
        }

        if (!empty($_POST['jour'])) {
            $jour = sprintf("%02d", $_POST['jour']);
            $query .= " AND strftime('%d', date_lot) = :jour";
            $query2 .= " AND strftime('%d', date) = :jour";
            $param[':jour'] = $jour;
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComicLib | Recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {background-color: #f8f9fa; }
        .search-container { margin-top: 50px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
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
                        <a class="nav-link active" href="#">Rechercher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">Ajouter</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="search-container mx-auto" style="max-width: 800px;">
            <h2 class="mb-4 text-center">Rechercher</h2>
            <form method="post" class="row g-3">
                
                <div class="col-md-12">
                    <label class="form-label fw-bold">Titre :</label>
                    <input type="text" class="form-control" name="titre" placeholder="Ex: Rex">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-bold">Serie :</label>
                    <input type="text" class="form-control" name="serie" placeholder="Ex: Venom">
                </div>
                <?php
                /*<div class="col-md-6">
                    <label class="form-label fw-bold">Auteur :</label>
                    <input type="text" class="form-control" name="auteur">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Type :</label>
                    <input type="text" class="form-control" name="Type">
                </div>*/
                ?>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Jour :</label>
                    <input type="number" class="form-control" name="jour" min="1" max="31">
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Mois :</label>
                    <select class="form-select" name="mois">
                        <option value="">- Sélectionner -</option>
                        <option>Janvier</option>
                        <option>Février</option>
                        <option>Mars</option>
                        <option>Avril</option>
                        <option>Mai</option>
                        <option>Juin</option>
                        <option>Juillet</option>
                        <option>Aout</option>
                        <option>Septembre</option>
                        <option>Octobre</option>
                        <option>Novembre</option>
                        <option>Décembre</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Année :</label>
                    <input type="number" class="form-control" name="annee" min="1900" max="<?= date("Y") ?>">
                </div>

                <div class="col-12 mt-4 d-grid">
                    <button type="submit" name="sub" class="btn btn-primary btn-lg">Lancer la recherche</button>
                </div>

            </form>
        </div>
    </div>
    <div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Résultats de la recherche</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Titre</th>
                        <th>Serie</th>
                        <th>Tome</th>
                        <th>Date</th>
                        <th class="text-center">Lot</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    getTableLot($query,$param);
                    getTableComic($query2,$param);
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialise tous les popovers de la page
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    </script>
</body>
</html>
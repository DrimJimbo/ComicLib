<?php
$pdo = connectDB();
initDB();
function connectDB(){
  try {
    $chemin_bdd = __DIR__ . '/../bdd/db.sqlite';

    // Connexion PDO
    $pdo = new PDO('sqlite:' . $chemin_bdd);

    // Activation des erreurs pour le développement
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
  }
}

function initDB(){
  global $pdo;

    // Table TYPE (Suppression de la virgule après nom_type)
    $createTypeTable = "CREATE TABLE IF NOT EXISTS type (
        id_type INTEGER PRIMARY KEY AUTOINCREMENT,
        nom_type VARCHAR(50)
    );";

    // Table AUTEUR (Suppression de la virgule après nom)
    $createAuteurTable = "CREATE TABLE IF NOT EXISTS auteur (
        id_auteur INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(50)
    );";

    // Table COMICS
    $createComicsTable = "CREATE TABLE IF NOT EXISTS comics (
        id_comics INTEGER PRIMARY KEY AUTOINCREMENT,
        titre_fr VARCHAR(200),
        titre_en VARCHAR(200),
        serie_com VARCHAR(100),
        tome_com INTEGER,
        date DATE
    );";

    // Table ECRIRE (Clé primaire composée, pas d'AUTOINCREMENT ici)
    $createEcrireTable = "CREATE TABLE IF NOT EXISTS ecrire (
        id_comics INTEGER,
        id_auteur INTEGER,
        PRIMARY KEY (id_comics, id_auteur),
        FOREIGN KEY (id_comics) REFERENCES comics(id_comics) ON DELETE CASCADE,
        FOREIGN KEY (id_auteur) REFERENCES auteur(id_auteur) ON DELETE CASCADE
    );";

    // Table LOT_COMICS
    $createLotComicsTable = "CREATE TABLE IF NOT EXISTS lot_comics (
        id_lot INTEGER PRIMARY KEY AUTOINCREMENT,
        titre_lot VARCHAR(200),
        titre_en_lot VARCHAR(200),
        serie_lot VARCHAR(100),
        tome_lot VARCHAR(50),
        date_lot DATE,
        image VARCHAR(200) DEFAULT '/images/default.png'
    );";

    // Table COMPOSITION_LOT (Clé primaire composée)
    $createCompositionLotTable = "CREATE TABLE IF NOT EXISTS composition_lot (
        id_lot INTEGER,
        id_comics INTEGER,
        PRIMARY KEY (id_lot, id_comics),
        FOREIGN KEY (id_lot) REFERENCES lot_comics(id_lot) ON DELETE CASCADE,
        FOREIGN KEY (id_comics) REFERENCES comics(id_comics) ON DELETE CASCADE
    );";

  try {
    $pdo->exec($createTypeTable);
    $pdo->exec($createAuteurTable);
    $pdo->exec($createComicsTable);
    $pdo->exec($createEcrireTable);
    $pdo->exec($createLotComicsTable);
    $pdo->exec($createCompositionLotTable);
  } catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
  }
  
}
?>
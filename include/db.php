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

    // Table COMICS
    $createComicsTable = "CREATE TABLE IF NOT EXISTS comics (
        id_comics INTEGER PRIMARY KEY AUTOINCREMENT,
        titre_fr VARCHAR(200),
        titre_en VARCHAR(200),
        serie_com VARCHAR(100),
        tome_com INTEGER,
        possede INTEGER,
        date DATE
    );";

    // Table LOT_COMICS
    $createLotComicsTable = "CREATE TABLE IF NOT EXISTS lot_comics (
        id_lot INTEGER PRIMARY KEY AUTOINCREMENT,
        titre_lot VARCHAR(200),
        titre_en_lot VARCHAR(200),
        serie_lot VARCHAR(100),
        tome_lot VARCHAR(50),
        possede_lot INTEGER,
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

    $createGroupeTable = "CREATE TABLE IF NOT EXISTS groupe (
        id_serie INTEGER PRIMARY KEY AUTOINCREMENT,
        serie VARCHAR(200),
        nb_tome INTEGER,
        nb_hs INTEGER
    );";

  try {
    $pdo->exec($createComicsTable);
    $pdo->exec($createLotComicsTable);
    $pdo->exec($createCompositionLotTable);
    $pdo->exec($createGroupeTable);
  } catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
  }
  
}
?>
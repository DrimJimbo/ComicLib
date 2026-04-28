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
  $createSerieTable = "CREATE TABLE IF NOT EXISTS serie (
      id_serie INTEGER PRIMARY KEY AUTOINCREMENT,
      nom_serie VARCHAR(200) UNIQUE,
      nb_tome INTEGER,
      nb_hs INTEGER
  );";

  // Table COMICS
  $createComicsTable = "CREATE TABLE IF NOT EXISTS comics (
      id_comics INTEGER PRIMARY KEY AUTOINCREMENT,
      titre VARCHAR(200),
      id_serie INTEGER,
      tome INTEGER,
      date DATE,
      picture VARCHAR(500),
      FOREIGN KEY (id_serie) REFERENCES serie(id_serie) ON DELETE CASCADE
  );";

  try {
    $pdo->exec($createComicsTable);
    $pdo->exec($createSerieTable);
  } catch (PDOException $e) {
    die("Erreur init : " . $e->getMessage());
  }
  
}
?>
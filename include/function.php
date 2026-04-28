<?php
require_once "db.php";
function addSerie($array){
    global $pdo;
    $query = "INSERT INTO serie(nom_serie,nb_tome,nb_hs) VALUES (:serie,:tome,:hs)";
    $request = $pdo->prepare($query);
    try{
        $request->execute($array);
    } catch (PDOException $e){
        die("Erreur ajout serie : ".$e->getMessage());
    }
    return $pdo->lastInsertId();
}

function getSerie($serie){
    global $pdo;
    $query = "SELECT id_serie FROM serie WHERE nom_serie = :serie";
    $request = $pdo->prepare($query);
    try{
        $request->execute([
            ':serie' => $serie
        ]);
        $res = $request->fetchAll()[0]['id_serie'];
    }catch (PDOException $e){
        die('Erreur getSerie : '.$e->getMessage());
    }
    return $res;
}

function getSerieById($id){
    global $pdo;
    $query = "SELECT * FROM serie WHERE id_serie = :id";
    $request = $pdo->prepare($query);
    try{
        $request->execute([
            ':id' => $id
        ]);
        $res = $request->fetchAll()[0];
    }catch (PDOException $e){
        die('Erreur getSerie : '.$e->getMessage());
    }
    return $res;
}

function addComic($array){
    global $pdo;
    $query = "INSERT INTO comics(titre,id_serie,tome,date,picture) VALUES (:titre , :id, :tome, :date, :picture)";
    $request = $pdo->prepare($query);
    try{
        $request->execute($array);
    }catch (PDOException $e){
        die("Erreur addComic : ".$e->getMessage());
    }
}

function nameDiff($string){
    $transliterator = Transliterator::create('Any-Latin; Latin-ASCII;');    
    $string = $transliterator->transliterate($string);
    $string = str_replace(' ', '_', $string);
    $string = preg_replace('/[^A-Za-z0-9\_]/', '', $string);
    return $string;
}

function downloadPic($pic,$lot){
  $dir = "images/";
  $name = $pic['name'];
  $filetemp = $pic['tmp_name'];
  $extension = strtolower(pathinfo($name,PATHINFO_EXTENSION));
  $new_name = $lot[':titre']."_".$lot[':id'];
  $new_name = nameDiff($new_name);
  $new_name .= ".".$extension;
  $dest = $dir.$new_name;
  $test = move_uploaded_file($filetemp,$dest);
  return $dest;
}

function getTableComic($query,$param){
    global $pdo;
    $request = $pdo->prepare($query);
    try {
        $request->execute($param);
        $all = $request->fetchAll();
        foreach($all as $row) {
            $image = htmlspecialchars($row['picture']);
            $titre = htmlspecialchars($row['titre']);
            $serie = getSerieById(htmlspecialchars($row['id_serie']));
            $serie_titre = $serie['nom_serie'];
            $nbtome = $serie['nb_tome'];
            $date = htmlspecialchars($row['date']);
            $tome = htmlspecialchars($row['tome']);
            echo "<tr>";
            echo '<td class="text-center"><span class="text-primary" 
            style="cursor:pointer;" 
            data-bs-toggle="popover" 
            data-bs-trigger="hover focus" 
            data-bs-html="true" 
            data-bs-content="<img src=\''.$image.'\' class=\'img-fluid rounded\'>">
            <i class="bi bi-image"></i> 
            </span></td>';
            echo "<td>$titre</td>";
            echo "<td>$serie_titre</td>";
            echo "<td>$tome/$nbtome</td>";
            echo "<td>$date</td>";
            echo "</tr>";
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='3' class='text-danger text-center'>Erreur : " . $e->getMessage() . "</td></tr>";
    }
}

?>
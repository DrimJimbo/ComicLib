<?php
require_once "db.php";
function ajouterLot($lot,$comics){
    global $pdo;
    $query = "INSERT INTO lot_comics(titre_lot,titre_en_lot,serie_lot,tome_lot,date_lot,image) VALUES (:titre,:titre_en,:serie,:tome,:date,:image);";
    $request = $pdo->prepare($query);
    try{
        $request->execute([
            ':titre' => $lot[0],
            ':titre_en' => $lot[1],
            ':serie' => $lot[2],
            ':tome' => $lot[3],
            ':date' => $lot[4],
            ':image' => $lot[5]
        ]);
        $idlot = $pdo->lastInsertId();
    } catch (PDOException $e){
        die("Erreur ajouterLot lot : ".$e->getMessage());
    }
    for($i =0 ; $i < 0; $i++ ){
        $comic = array();
        for($j = 0; $j < count($comics);$j++){
            // Identique à l'option A
            array_push($comic,$comics[$j][$i]);
        }
        $query = "INSERT INTO  comics(titre_fr,titre_en,serie_com,tome_com,date) VALUES (:titre,:titre_en,:serie,:tome,:date);";
        $request = $pdo->prepare($query);
        try{
            $request->execute([
            ':titre' => $comic[0],
            ':titre_en' => $comic[1],
            ':serie' => $comic[2],
            ':tome' => $comic[3],
            ':date' => $comic[4],
            ]);
            $idcom = $pdo->lastInsertId();
            $query = "INSERT INTO composition_lot (id_lot, id_comics) VALUES (:idlot, :idcom)";
            $request = $pdo->prepare($query);
            $request->execute([
                ':idlot' => $idlot,
                ':idcom' => $idcom, 
            ]);
        } catch (PDOException $e){
            die("Erreur ajouterLot comic $j : ".$e->getMessage());
        }
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
  $new_name = $lot[0]."_".$lot[2].".".$extension;
  $new_name = nameDiff($new_name);
  $dest = $dir.$new_name;
  $test = move_uploaded_file($filetemp,$dest);
  return $dest;
}

function getTableLot($query,$param){
    global $pdo;
    $request = $pdo->prepare($query);
        try {
            $request->execute($param);
            $all = $request->fetchAll();
            
            foreach($all as $row) {
                $image = htmlspecialchars($row['image']);
                $titre = htmlspecialchars($row['titre_lot']);
                $serie = htmlspecialchars($row['serie_lot']);
                $date = htmlspecialchars($row['date_lot']);
                // On utilise un badge Bootstrap pour le "Yes"
                $lotBadge = '<span class="badge bg-success">Oui</span>';
                
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
                echo "<td>$serie</td>";
                echo "<td>$date</td>";
                echo "<td class='text-center'>$lotBadge</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='3' class='text-danger text-center'>Erreur : " . $e->getMessage() . "</td></tr>";
        }
}
function getTableComic($query,$param){
    global $pdo;
    $request = $pdo->prepare($query);
    try {
        $request->execute($param);
        $all = $request->fetchAll();
        
        foreach($all as $row) {
            $titre = htmlspecialchars($row['titre_fr']);
            $serie = htmlspecialchars($row['serie_com']);
            $tome = htmlspecialchars($row['tome_com']);
            $date = htmlspecialchars($row['date']);
            $lotBadge = '<span class="badge bg-danger">Non</span>';
            echo "<tr>";
            echo "<td></td>";
            echo "<td>$titre</td>";
            echo "<td>$serie</td>";
            echo "<td>$tome</td>";
            echo "<td>$date</td>";
            echo "<td class='text-center'>$lotBadge</td>";
            echo "</tr>";
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='3' class='text-danger text-center'>Erreur : " . $e->getMessage() . "</td></tr>";
    }
}
?>
<?php
    $MYSQL['host']     = "localhost";
    $MYSQL['user']     = "admin";
    $MYSQL['password'] = "admin";
    $MYSQL['database'] = "serre_db";

    $mysqli = mysqli_connect($MYSQL['host'], $MYSQL['user'], $MYSQL['password'], $MYSQL['database']);

    // Jointure des 3 tables sur l'horaire
    $result = $mysqli->query("
        SELECT 
            t.horaire,
            t.valeur AS temperature,
            h.valeur AS humidite,
            hs.valeur AS humidite_sol
        FROM temperature t
        JOIN humidite h ON t.horaire = h.horaire
        JOIN humidite_sol hs ON t.horaire = hs.horaire
        ORDER BY t.horaire DESC
        LIMIT 10
    ");

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['horaire'],
            (int)$row['temperature'],
            (int)$row['humidite'],
            (int)$row['humidite_sol']
        ];
    }

    $mysqli->close();

    header('Access-Control-Allow-Origin: *');
    header('Content-type: application/json');
    echo json_encode($data);
?>

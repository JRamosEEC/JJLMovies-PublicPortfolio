<?php
    $ini = parse_ini_file( __DIR__ . '/../Config/dbconfig.ini');

    $db = new PDO(  "mysql:host=" . $ini['servername'] . 
                    ":" . $ini['port'] . 
                    ";dbname=" . $ini['dbname'], 
                    $ini['username'], 
                    $ini['password'],);


    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
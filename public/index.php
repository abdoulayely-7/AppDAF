<?php

use app\core\DataBase;

require_once '../app/config/bootstrap.php';
//Router::resolve($tabs);
echo 'fils naka bay';
echo 'fils naka bay<br>';

try {
    $db = DataBase::getInstance()->getConnection();
    echo 'Connexion DB OK';
} catch (Exception $e) {
    echo 'Erreur DB : ' . $e->getMessage();
}

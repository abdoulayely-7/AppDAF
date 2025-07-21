<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$dsn = $_ENV['DSN'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASSWORD'] ?? '';

class Seeder
{
  public static function seed()
  {
    $pdo = new PDO($GLOBALS['dsn'], $GLOBALS['user'], $GLOBALS['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Seed profil
    $profils = ['CLIENT', 'COMMERCIAL'];
    $stmtProfil = $pdo->prepare("INSERT INTO profil (libelle) VALUES (:libelle)");

    foreach ($profils as $profil) {
      $stmtProfil->execute(['libelle' => $profil]);
    }

    echo count($profils) . " profils seedés.\n";

    // Récupérer les IDs de profil
    $profilStmt = $pdo->query("SELECT id, libelle FROM profil");
    $profilMap = [];
    while ($row = $profilStmt->fetch(PDO::FETCH_ASSOC)) {
      $profilMap[$row['libelle']] = $row['id'];
    }

    // Seed utilisateurs
    $utilisateurs = [
      [
        'nom'      => 'Wane',
        'prenom'   => 'Coach',
        'telephone' => '770000001',
        'password' => password_hash('passer', PASSWORD_BCRYPT),
        'cni'      => 'CNI20250001',
        'photorecto' => 'recto1.jpg',
        'photoverso' => 'verso1.jpg',
        'adresse'  => 'Dakar',
        'profil_id' => $profilMap['CLIENT']
      ],
      [
        'nom'      => 'Diop',
        'prenom'   => 'Awa',
        'telephone' => '770000002',
        'password' => password_hash('passer', PASSWORD_BCRYPT),
        'cni'      => 'CNI20250002',
        'photorecto' => 'recto2.jpg',
        'photoverso' => 'verso2.jpg',
        'adresse'  => 'Thiès',
        'profil_id' => $profilMap['CLIENT']
      ],
      [
        'nom'      => 'Ba',
        'prenom'   => 'Moussa',
        'telephone' => '770000003',
        'password' => password_hash('passer', PASSWORD_BCRYPT),
        'cni'      => 'CNI20250003',
        'photorecto' => 'recto3.jpg',
        'photoverso' => 'verso3.jpg',
        'adresse'  => 'Saint-Louis',
        'profil_id' => $profilMap['COMMERCIAL']
      ]
    ];

    $sqlUser = "INSERT INTO utilisateur (nom, prenom, telephone, password, cni, photorecto, photoverso, adresse, profil_id)
                VALUES (:nom, :prenom, :telephone, :password, :cni, :photorecto, :photoverso, :adresse, :profil_id)";
    $stmtUser = $pdo->prepare($sqlUser);

    foreach ($utilisateurs as $u) {
      $stmtUser->execute($u);
    }

    echo count($utilisateurs) . " utilisateurs seedés avec succès.\n";

    // Récupérer les IDs utilisateur
    $userIds = $pdo->query("SELECT id FROM utilisateur WHERE profil_id IN (
    SELECT id FROM profil WHERE libelle = 'CLIENT'
) ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);

    // Création de comptes
    $comptes = [];
    foreach ($userIds as $index => $uid) {
      // Compte principal
      $comptes[] = [
        'solde' => rand(1000, 5000),
        'numero_tel' => '78000000' . ($index + 1),
        'typecompte' => 'principal',
        'utilisateur_id' => $uid
      ];
      // Comptes secondaires
      for ($i = 1; $i <= 3; $i++) {
        $comptes[] = [
          'solde' => rand(500, 1500),
          'numero_tel' => '7800000' . rand(10, 99) . $i,
          'typecompte' => 'secondaire',
          'utilisateur_id' => $uid
        ];
      }
    }

    $sqlCompte = "INSERT INTO compte (solde, numero_tel, typecompte, utilisateur_id)
                  VALUES (:solde, :numero_tel, :typecompte, :utilisateur_id)";
    $stmtCompte = $pdo->prepare($sqlCompte);
    foreach ($comptes as $c) {
  try {
    $stmtCompte->execute($c);
  } catch (PDOException $e) {
    echo "Erreur compte : " . $e->getMessage() . "\n";
  }
}


    echo count($comptes) . " comptes seedés avec succès.\n";

    // Transactions
    $compteIds = $pdo->query("SELECT id FROM compte")->fetchAll(PDO::FETCH_COLUMN);
    $transactions = [];
    $types = ['DEPOT', 'RETRAIT', 'PAIEMENT', 'TRANSFERT'];

    foreach ($compteIds as $cid) {
      for ($i = 1; $i <= 5; $i++) {
        $transactions[] = [
          'date' => date('Y-m-d H:i:s', strtotime("-$i days")),
          'typetransaction' => $types[array_rand($types)],
          'montant' => rand(100, 2000),
          'compte_id' => $cid
        ];
      }
    }

    $sqlTransaction = "INSERT INTO transaction (date, typetransaction, montant, compte_id)
                      VALUES (:date, :typetransaction, :montant, :compte_id)";
    $stmtTransaction = $pdo->prepare($sqlTransaction);
    foreach ($transactions as $t) {
      $stmtTransaction->execute($t);
    }

    echo count($transactions) . " transactions seedées avec succès.\n";
  }
}

Seeder::seed();

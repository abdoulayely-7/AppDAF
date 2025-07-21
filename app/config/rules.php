<?php

use app\core\App;

require_once '../app/config/erreurs.php';

$rules = [
    'nom' => ['required' => MessageErreurEnum::NOM_VIDE],
    'prenom' => ['required' => MessageErreurEnum::PRENOM_VIDE],
    'adresse' => ['required' => MessageErreurEnum::ADRESSE_VIDE],
    'telephone' => [
        'required' => MessageErreurEnum::TELEPHONE_VIDE,
        'telephone' => MessageErreurEnum::TELEPHONE_INVALIDE,
        'unique' => [
            'repository' => App::getDependency('userRepository'),
            'method' => 'existByTelephone',
            'message' => MessageErreurEnum::TELEPHONE_EXISTE
        ]
    ],
    'solde' => ['required' => MessageErreurEnum::SOLDE_VIDE],
    'password' => ['required' => MessageErreurEnum::PASSWORD_VIDE],

    'cni' => [
        'required' => MessageErreurEnum::CNI_VIDE,
        'cni' => MessageErreurEnum::CNI_INVALIDE,
        'unique' => [
            'repository' => App::getDependency('userRepository'),
            'method' => 'existByCni',
            'message' => MessageErreurEnum::CNI_EXISTE
        ]
    ],
    'photorecto' => ['required' => MessageErreurEnum::PHOTO_RECTO_VIDE,],
    'photoverso' => ['required' => MessageErreurEnum::PHOTO_VERSO_VIDE,]
];

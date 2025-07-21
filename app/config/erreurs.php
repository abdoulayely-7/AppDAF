<?php
enum MessageErreurEnum: string
{

    //   case TELEPHONE_INVALIDE = "Le numéro de téléphone est invalide.";
    // case PASSWORD_VIDE = "Le mot de passe est requis.";
    case CHAMP_VIDE = "Ce champ est obligatoire.";
    case SOLDE_VIDE = "Le solde est obligatoire.";

    // Téléphone
    case TELEPHONE_INVALIDE = "Le numéro de téléphone doit commencer par 77, 78, 70, 76 ou 75 et contenir 9 chiffres.";
    case TELEPHONE_EXISTE = "Ce numéro de téléphone est déjà utilisé.";
    case TELEPHONE_VIDE = "Le téléphone est obligatoire.";

        // Mot de passe
    case PASSWORD_VIDE = "Le mot de passe est obligatoire.";
        // CNI
    case CNI_VIDE = "Le numéro CNI est obligatoire.";

    case CNI_INVALIDE = "Le numéro CNI est invalide. Il doit contenir 13 chiffres et commencer par 1 ou 2.";
    case CNI_EXISTE = "Ce numéro CNI est déjà utilisé.";
        // Adresse
    case ADRESSE_VIDE = "L'adresse est obligatoire.";
        // Nom et prénom
    case NOM_VIDE = "Le nom est obligatoire.";
    case PRENOM_VIDE = "Le prénom est obligatoire.";
    // photo
    case PHOTO_RECTO_VIDE = "La photo recto de la CIN est obligatoire.";
    case PHOTO_VERSO_VIDE = "La photo verso de la CIN est obligatoire.";
}

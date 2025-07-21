<?php
namespace app\core;

use app\core\middlewares\Auth;
use src\entity\Compte;
use src\entity\Profil;
use src\entity\User;
use src\repository\CompteRepository;
use src\repository\ProfilRepository;
use src\repository\TransactionRepository;
use src\repository\UtilisateurRepository;
use src\service\CompteService;
use src\service\ProfilService as ServiceProfilService;
use src\service\SecurityService;
use src\service\SmsService;
use src\service\TransactionService;
use Symfony\Component\Yaml\Yaml;

class App
{
    private static array $dependencies = [];

    public static function init()
    {
        $configPath = '../app/config/service.yml';
        if (file_exists($configPath)) {
            self::$dependencies = Yaml::parseFile($configPath);
        } else {
            throw new \Exception("Le fichier de configuration des dépendances est introuvable.");
        }
    }

    public static function getDependency(string $dep)
    {
        if (empty(self::$dependencies)) {
            self::init();
        }
        foreach (self::$dependencies as $category) {
            if (is_array($category) && isset($category[$dep])) {
                $class = $category[$dep];
                if (method_exists($class, 'getInstance')) {
                    return $class::getInstance();
                }
                return new $class();
            }
        }
        return null;
    }
}
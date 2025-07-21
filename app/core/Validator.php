<?php

namespace app\core;

use MessageErreurEnum;

require_once '../app/config/erreurs.php';
class Validator
{
    private static array $errors = [];

    public static function validate(array $data, array $rules): void
    {
        foreach ($rules as $field => $fieldRules) {
            // Cas des fichiers : on vérifie s'il est dans $_FILES
            if (isset($_FILES[$field])) {
                $file = $_FILES[$field];
                foreach ($fieldRules as $rule => $ruleValue) {
                    if ($rule === 'required' && $file['error'] === UPLOAD_ERR_NO_FILE) {
                        self::addError($field, $ruleValue->value);
                    }
                }
                continue;
            }

            $value = trim($data[$field] ?? '');
            foreach ($fieldRules as $rule => $ruleValue) {
                if ($rule === 'required' && $value === '') {
                    self::addError($field, $ruleValue->value);
                }

                if ($rule === 'telephone' && !preg_match("/^7[05678][0-9]{7}$/", $value)) {
                    self::addError($field, $ruleValue->value);
                }

                if ($rule === 'cni' && !preg_match("/^[12][0-9]{12}$/", $value)) {
                    self::addError($field, $ruleValue->value);
                }

                if ($rule === 'unique' && is_array($ruleValue)) {
                    $repo = $ruleValue['repository'];
                    $method = $ruleValue['method'];
                    if ($repo->$method($value)) {
                        self::addError($field, $ruleValue['message']->value);
                    }
                }
            }
        }
    }

    public static function isTelephone(string $value, string $key, ?string $message = null): bool
    {
        if (!preg_match("/^7[05678][0-9]{7}$/", trim($value))) {
            self::addError($key, $message ?? MessageErreurEnum::TELEPHONE_INVALIDE->value);
        }
        return true;
    }

    public static function isEmpty(string $value, string $key, ?string $message = null): bool
    {
        if (trim($value) === "") {
            self::addError($key, $message ?? MessageErreurEnum::CHAMP_VIDE->value);
        }
        return true;
    }



    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function addError(string $key, string $message): void
    {
        self::$errors[$key][] = $message;
    }

    public static function isValid(): bool
    {
        return empty(self::$errors);
    }

    public static function saveErrorsToSession(Session $session): void
    {
        $session->set("errors", self::$errors);
    }

    public static function resetErreur()
    {
        return self::$errors = [];
    }
}

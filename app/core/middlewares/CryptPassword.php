<?php
namespace app\core\middlewares;
class CryptPassword{
  public function __invoke()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['password'])) {
        return $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
      }
    }
  }
}
<?php
namespace app\core\middlewares;
use app\core\App;
class Auth
{
  public function __invoke()
  {
    $session = App::getDependency('session');
    if (!$session->isset('user')) {
      header("location:" . WEB_URL);
      exit;
    }
  }
}

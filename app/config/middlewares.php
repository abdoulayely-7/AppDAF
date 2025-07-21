<?php

namespace app\config;

use app\core\middlewares\Auth;
use app\core\middlewares\CryptPassword;

return [
  'auth' => Auth::class,
  'crypPassword' => CryptPassword::class,
];
<?php
namespace app\core\abstract;

use app\core\App;
use app\core\DataBase;
use app\core\Singleton;

abstract  class AbstractRepository extends Singleton
{
  protected string $table ;
  protected DataBase $db;


  public function __construct()
  {
    $this->db = App::getDependency("database");
  }
  abstract public function selectByid();
}

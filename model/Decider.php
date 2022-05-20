<?php

define('GAME_NAME', "a-b-microsec");
define('EPSILON', 0.1);

require_once dirname(__FILE__).'/DatabaseConnection.php';

final class Decider {
  public array $armNames = [];
  private $databaseConnection;

  public function __construct(array $armNames = [])
  {
    if (empty($armNames)) {
      throw 'Need to specify any arm names';
    }
    $this->armNames = $armNames;
    $this->databaseConnection = new DatabaseConnection();
  }

  public function selectArm()
  {
    $random = mt_rand() / mt_getrandmax();

    if ($random > EPSILON) {
      $action = "exploitation";
      $selectedIndex = random_int(0, count($this->armNames) -1);
      $armName = $this->armNames[$selectedIndex];
    } else {
      $action = "exploration";
      $maxArm = $this->selectRewardMaxArm();
      $armName = $maxArm->name;
    }
    $this->log(["type" => "selected", "arm" => $armName, "action" => $action]);
    $this->databaseConnection->insertExperiment(GAME_NAME, $armName);
    return $armName;
  }

  public function giveReward(string $armName, float $reward): void
  {
    $this->log(["type" => "reward", "arm" => $armName, "reward" => $reward ]);
    $this->databaseConnection->insertReward(GAME_NAME, $armName, $reward);
  }

  private function log($arr): void
  {
    file_put_contents(dirname(__FILE__).'/../log/decider.log', json_encode($arr) . "\n", FILE_APPEND | LOCK_EX);
  }

  public function genArms(): array
  {
    return array_map(fn($armName) => $this->databaseConnection->getArm(GAME_NAME, $armName) , $this->armNames);
  }

  public function selectRewardMaxArm()
  {
    $max = new stdClass();
    // 報酬が0以上であることを前提に、負の数を指定して必ず最大のものが選ばれるようにしている
    $max->expectedReward = -1.0;
    foreach($this->genArms() as $arm) {
      if ($arm->expectedReward > $max->expectedReward) {
        $max = $arm;
      }
    }
    return $max;
  }
}

<?php

if (!session_id()) {
  session_start();
}

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/../model/Decider.php';
require_once dirname(__FILE__).'/../model/Link.php';

function getNowTimestamp()
{
  return microtime(true);
}

function giveReward(Decider $decider, $armSelectedTime, $arm):void
{
  if (empty($armSelectedTime) || empty($arm)) {
    return;
  }
  /*
   * 表示されてからの時刻をtとして、 1/(t+1) を報酬とする
   * 報酬が最大化されるように最適化したい
   */
  $now = getNowTimestamp();
  $secDiff = $now - $armSelectedTime;
  $reward = 1.0 / ($secDiff + 1);
  $decider->giveReward($arm, $reward);
}

function render(string $arm) {
  echo "
    <h1>arm: ${arm}</h1>
    <form action=\"index.php\" method=\"POST\">
      <input type=\"submit\" value=\"select\">
    </form>
  ";
  Link::render();
  exit(0);
}

function main() {
  $decider = new Decider(['a', 'b']);

  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    giveReward($decider, $_SESSION['now'], $_SESSION['arm']);
  }

  $arm = $decider->selectArm();
  $_SESSION['now'] = getNowTimestamp();
  $_SESSION['arm'] = $arm;
  render($arm);
}

main();

<?php

require_once dirname(__FILE__).'/../model/Decider.php';
require_once dirname(__FILE__).'/../model/Link.php';

$decider = new Decider(['a', 'b']);

echo "
  <style>
    td, th {
      border: solid 1px black;
    }
  </style>
  <table>
    <tr>
      <th>arm</th>
      <th>counts</th>
      <th>total rewards</th>
      <th>expected reward per once</th>
    </tr>
";

foreach($decider->genArms() as $arm) {
  echo "
    <tr>
      <td>{$arm->name}</td>
      <td>{$arm->counts}</td>
      <td>{$arm->totalRewards}</td>
      <td>{$arm->expectedReward}</td>
    </tr>
  ";
}


echo "</table>";

Link::render();

<?php

final class DatabaseConnection {
  private $db;
  public function __construct()
  {
    $this->db = new SQLite3(dirname(__FILE__).'/../database/bandit-for-website-php.db');
  }

  public function insertExperiment(string $game, string $arm): void
  {
    $stmt = $this->db->prepare('INSERT INTO experiments(game, arm) VALUES (:game, :arm)');
    $stmt->bindParam('game', $game);
    $stmt->bindParam('arm', $arm);
    $stmt->execute();
  }

  public function insertReward(string $game, string $arm, float $reward): void
  {
    $stmt = $this->db->prepare('INSERT INTO rewards(game, arm, reward) VALUES(:game, :arm, :reward)');
    $stmt->bindParam('game', $game);
    $stmt->bindParam('arm', $arm);
    $stmt->bindParam('reward', $reward);
    $stmt->execute();
  }

  public function fetchSummary(string $game, string $arm): float
  {
    $count = $this->countExperiments($game, $arm);
    $rewardSum = $this->totalRewards($game, $arm);
    // 報酬を与えなかった試行では、報酬が0であったとする
    return $rewardSum / $count;
  }

  public function countExperiments(string $game, string $arm): int
  {
    $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM experiments WHERE game = :game AND arm = :arm');
    $stmt->bindParam('game', $game);
    $stmt->bindParam('arm', $arm);
    $res = $stmt->execute();
    $arr = $res->fetchArray();
    return $arr['count'];
  }

  public function totalRewards(string $game, string $arm) {
    $stmt = $this->db->prepare('SELECT TOTAL(reward) as total FROM rewards WHERE game = :game AND arm = :arm');
    $stmt->bindParam('game', $game);
    $stmt->bindParam('arm', $arm);
    $res = $stmt->execute();
    $arr = $res->fetchArray();
    return $arr['total'];
  }

  public function getArm(string $game, string $armName) {
    $arm = new stdClass();
    $arm->name = $armName;
    $arm->counts = $this->countExperiments($game, $armName);
    $arm->totalRewards = $this->totalRewards($game, $armName);
    if ($arm->counts === 0) {
      $arm->expectedReward = 0;
    } else {
      $arm->expectedReward = $arm->totalRewards / floatval($arm->counts);
    }
    return $arm;
  }
}

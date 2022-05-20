CREATE TABLE experiments(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  game TEXT,
  arm TEXT,
  created_at TEXT NOT NULL DEFAULT (DATETIME('now', 'localtime'))
);
CREATE INDEX experiments_game ON experiments(game, arm, id);
CREATE TABLE rewards(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  game TEXT,
  arm TEXT,
  reward REAL,
  created_at TEXT NOT NULL DEFAULT (DATETIME('now', 'localtime'))
);
CREATE INDEX rewards_game ON rewards(game, arm, id);
CREATE TABLE summaries(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  game TEXT,
  arm TEXT,
  experiments_count INTEGER,
  rewards_count INTEGER,
  rewards_average REAL,
  created_at TEXT NOT NULL DEFAULT (DATETIME('now', 'localtime'))
);
CREATE INDEX summaries_game ON summaries(game, arm, id);

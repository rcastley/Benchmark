DROP TABLE users;
CREATE TABLE users (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  user VARCHAR(255) NOT NULL UNIQUE,
  key VARCHAR(255),
  secret VARCHAR(255),
  cid INTEGER
);
CREATE INDEX "id" ON "users" ("id");

DROP TABLE benchmark_data;
CREATE TABLE benchmark_data (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  cid INTEGER NOT NULL,
  name VARCHAR(255) NOT NULL,
  dns INTEGER NOT NULL,
  wait INTEGER NOT NULL,
  load INTEGER NOT NULL,
  bytes INTEGER NOT NULL,
  doc_complete INTEGER NOT NULL,
  webpage_response INTEGER NOT NULL,
  items INTEGER NOT NULL
);
CREATE INDEX bid ON "benchmark_data" ("id");

DROP TABLE benchmark_charts;
CREATE TABLE benchmark_charts (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  cid INTEGER NOT NULL UNIQUE,
  name VARCHAR(255) NOT NULL,
  modified TEXT
);
CREATE INDEX cid ON "benchmark_charts" ("id");

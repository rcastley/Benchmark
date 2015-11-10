-- scripts/schema.sqlite.sql
--
-- You will need load your database schema with this SQL.

CREATE TABLE scc (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    user TEXT NOT NULL UNIQUE,
    state TEXT NOT NULL,
    key TEXT,
    secret TEXT);

CREATE INDEX "id" ON "scc" ("id");

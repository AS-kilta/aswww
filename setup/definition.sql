DROP SEQUENCE naviNodesSeq;
DROP SEQUENCE contentSeq;
DROP SEQUENCE usersSeq;

DROP TABLE naviNodes CASCADE;
DROP TABLE naviTitles;
DROP TABLE pages;
DROP TABLE signup;
DROP TABLE users CASCADE;
DROP TABLE groups;

CREATE SEQUENCE naviNodesSeq;
CREATE SEQUENCE contentSeq;
CREATE SEQUENCE usersSeq;

CREATE TABLE naviNodes (
    id INT PRIMARY KEY,
    parent INT REFERENCES naviNodes(id) ON DELETE SET NULL,
    module VARCHAR(64),
    nodeId INT
);

CREATE TABLE naviTitles (
    id INT REFERENCES naviNodes(id) ON DELETE CASCADE,
    lang VARCHAR(2),
    url TEXT,
    title TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE pages (
    id INT,
    lang VARCHAR(2),
    content TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE signup (
    id INT,
    lang VARCHAR(2),
    title TEXT,
    description TEXT,
    opens TIMESTAMP,
    closes TIMESTAMP,
    capacity INT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE users (
    id INT,
    username VARCHAR(32),
    password VARCHAR(32),
    realname TEXT,
    PRIMARY KEY(id)
);

CREATE TABLE groups (
    userid INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    groupname VARCHAR(32),
    UNIQUE (userid, groupname)
);


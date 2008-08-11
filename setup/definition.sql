DROP SEQUENCE naviSeq;
DROP SEQUENCE usersSeq;
DROP SEQUENCE pollSeq;
DROP SEQUENCE pollOptionSeq;
DROP SEQUENCE newsSeq;
DROP SEQUENCE eventsSeq;

DROP TABLE naviNodes CASCADE;
DROP TABLE naviTitles;
DROP TABLE pages;
DROP TABLE signup;
DROP TABLE users CASCADE;
DROP TABLE groups;

CREATE SEQUENCE naviSeq;
CREATE SEQUENCE usersSeq;
CREATE SEQUENCE pollSeq;
CREATE SEQUENCE pollOptionSeq;
CREATE SEQUENCE newsSeq;
CREATE SEQUENCE eventsSeq;

CREATE TABLE naviNodes (
    id INT PRIMARY KEY,
    parent INT REFERENCES naviNodes(id) ON DELETE SET NULL,
    module VARCHAR(64),
    weight SMALLINT DEFAULT 0,
    hidden BOOLEAN DEFAULT false
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


CREATE TABLE poll (
    id INT,
    lang VARCHAR(2),
    question TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE pollOption (
    lang VARCHAR(2),
    poll INT,
    content TEXT,
    weight INT,
    FOREIGN KEY (poll, lang) REFERENCES poll (id, lang)
);

CREATE TABLE news (
    id INT,
    lang VARCHAR(2),
    timestamp TIMESTAMP DEFAULT now(),
    heading TEXT,
    content TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE events (
    id INT,
    lang VARCHAR(2),
    timestamp TIMESTAMP DEFAULT now(),
    heading TEXT,
    content TEXT,
    time TIMESTAMP,
    PRIMARY KEY (id, lang)
);

CREATE TABLE site (
    skin TEXT
);

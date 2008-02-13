DROP TABLE naviNodes CASCADE;
CREATE TABLE naviNodes (
    id INT PRIMARY KEY,
    parent INT REFERENCES naviNodes(id) ON DELETE SET NULL,
    module VARCHAR(64),
    nodeId INT
);


DROP TABLE naviTitles;
CREATE TABLE naviTitles (
    id INT REFERENCES naviNodes(id) ON DELETE CASCADE,
    lang VARCHAR(2),
    url TEXT,
    title TEXT,
    PRIMARY KEY (id, lang)
);


DROP TABLE pages;
CREATE TABLE pages (
    id INT,
    lang VARCHAR(2),
    content TEXT,
    PRIMARY KEY (id, lang)
);


DROP TABLE signup;
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


DROP TABLE users;
CREATE TABLE users (
    id INT,
    username VARCHAR(32),
    password VARCHAR(32),
    realname TEXT,
    PRIMARY KEY(id)
);


DROP TABLE groups;
CREATE TABLE groups (
    userid INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    groupname VARCHAR(32),
    UNIQUE (userid, groupname)
);


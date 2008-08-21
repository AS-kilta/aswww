DROP TABLE naviNodes CASCADE;
DROP TABLE naviTitles;
DROP TABLE pages;
DROP TABLE signup;
DROP TABLE users CASCADE;
DROP TABLE groups;
DROP TABLE news;
DROP TABLE poll;
DROP TABLE pollOption;
DROP TABLE site;

CREATE TABLE naviNodes (
    id SERIAL PRIMARY KEY,
    parent INT REFERENCES naviNodes(id) ON DELETE SET NULL,
    contentModule VARCHAR(64),
    contentId INT,
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
    id SERIAL,
    lang VARCHAR(2),
    content TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE signup (
    id SERIAL,
    lang VARCHAR(2),
    title TEXT,
    description TEXT,
    opens TIMESTAMP,
    closes TIMESTAMP,
    capacity INT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE users (
    id SERIAL,
    username VARCHAR(32),
    password VARCHAR(32),
    realname TEXT,
    PRIMARY KEY(id)
);


CREATE TABLE groups (
    id SERIAL,
    name VARCHAR(64)
);

CREATE TABLE groups_users (
    group_id INT NOT NULL REFERENCES groups(id) ON DELETE CASCADE,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE (group_id, user_id)
);

CREATE TABLE poll (
    id SERIAL,
    lang VARCHAR(2),
    question TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE pollOption (
    poll_id INT NOT NULL REFERENCES poll(id) ON DELETE CASCADE,
    lang VARCHAR(2),
    position INT,
    content TEXT,
    FOREIGN KEY (poll_id, lang) REFERENCES poll (id, lang)
);

CREATE TABLE pollVote (
    poll_id INT REFERENCES poll(id) ON DELETE CASCADE,
    position INT,
    ip VARCHAR(15)
);

CREATE TABLE news (
    id SERIAL,
    lang VARCHAR(2),
    timestamp TIMESTAMP DEFAULT now(),
    heading TEXT,
    content TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE events (
    id SERIAL,
    lang VARCHAR(2),
    timestamp TIMESTAMP DEFAULT now(),
    heading TEXT,
    time TEXT,
    place TEXT,
    description TEXT,
    PRIMARY KEY (id, lang)
);

CREATE TABLE site (
    skin TEXT
);

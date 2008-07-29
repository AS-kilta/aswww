INSERT INTO site(skin) VALUES ('aski-v2');

-- PAGES
INSERT INTO naviNodes(id,parent,module,weight) VALUES (1,null,'news',0);
INSERT INTO naviTitles(id,lang,url,title) VALUES (1,'fi','','Etusivu');
INSERT INTO naviTitles(id,lang,url,title) VALUES (1,'en','english','Front page');

INSERT INTO naviNodes(id,parent,module,weight) VALUES (2,null,'page',1);
INSERT INTO naviTitles(id,lang,url,title) VALUES (2,'fi','kilta','Kilta');
INSERT INTO naviTitles(id,lang,url,title) VALUES (2,'en','guild','Guild');

INSERT INTO naviNodes(id,parent,module,weight) VALUES (3,2,'page',0);
INSERT INTO naviTitles(id,lang,url,title) VALUES (3,'fi','hallitus','Hallitus');
INSERT INTO naviTitles(id,lang,url,title) VALUES (3,'en','board','Board');

INSERT INTO naviNodes(id,parent,module,weight) VALUES (4,2,'page',1);
INSERT INTO naviTitles(id,lang,url,title) VALUES (4,'fi','toimarit','Toimarit');

INSERT INTO pages(id,lang,content) VALUES (2,'fi','<h1>Kilta</h1>');
INSERT INTO pages(id,lang,content) VALUES (2,'en','<h1>Guild</h1>');
INSERT INTO pages(id,lang,content) VALUES (3,'fi','<h1>Hallitus</h1>');
INSERT INTO pages(id,lang,content) VALUES (3,'en','<h1>Board</h1>');
INSERT INTO pages(id,lang,content) VALUES (4,'fi','<h1>Toimarit</h1>');


-- Users
INSERT INTO users(id,username,password,realname) VALUES (1,'tapio',md5('tapio'),'Tapio Auvinen');
INSERT INTO users(id,username,password,realname) VALUES (2,'antti',md5('antti'),'Antti Nieminen');
INSERT INTO users(id,username,password,realname) VALUES (3,'jaakko',md5('jaakko'),'Jaakko Kantojärvi');
INSERT INTO users(id,username,password,realname) VALUES (4,'assari',md5('demo'),'Assari');

-- SIGNUP
INSERT INTO naviNodes(id,parent,module,weight,hidden) VALUES (6,null,'ilmo',1,true);
INSERT INTO naviTitles(id,lang,url,title) VALUES (6,'fi','taskumatti','Taskumatti');

INSERT INTO signup(id,lang,title,description) VALUES (6,'fi','Taskumatti','Tilaa taskumatti');


-- SET UP SEQUENCES
SELECT setval('naviSeq', 100);
SELECT setval('newsSeq', 100);

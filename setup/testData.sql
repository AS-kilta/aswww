
-- PAGES
INSERT INTO naviNodes(id,parent,module,nodeId) VALUES (1,null,'frontpage',0);
INSERT INTO naviTitles(id,lang,url,title) VALUES (1,'fi','','Etusivu');
INSERT INTO naviTitles(id,lang,url,title) VALUES (1,'en','english','Front page');

INSERT INTO naviNodes(id,parent,module,nodeId) VALUES (2,null,'page',10);
INSERT INTO naviTitles(id,lang,url,title) VALUES (2,'fi','kilta','Kilta');
INSERT INTO naviTitles(id,lang,url,title) VALUES (2,'en','guild','Guild');

INSERT INTO naviNodes(id,parent,module,nodeId) VALUES (3,2,'page',11);
INSERT INTO naviTitles(id,lang,url,title) VALUES (3,'fi','hallitus','Hallitus');
INSERT INTO naviTitles(id,lang,url,title) VALUES (3,'en','board','Board');

INSERT INTO naviNodes(id,parent,module,nodeId) VALUES (4,2,'page',12);
INSERT INTO naviTitles(id,lang,url,title) VALUES (4,'fi','toimarit','Toimarit');

INSERT INTO pages(id,lang,content) VALUES (10,'fi','<h1>Kilta</h1>');
INSERT INTO pages(id,lang,content) VALUES (10,'en','<h1>Guild</h1>');
INSERT INTO pages(id,lang,content) VALUES (11,'fi','<h1>Hallitus</h1>');
INSERT INTO pages(id,lang,content) VALUES (11,'en','<h1>Board</h1>');
INSERT INTO pages(id,lang,content) VALUES (12,'fi','<h1>Toimarit</h1>');


-- Users
INSERT INTO users(id,username,password,realname) VALUES (1,'tapio',md5('tapio'),'Tapio');


-- SIGNUP
INSERT INTO naviNodes(id,parent,module,nodeId) VALUES (5,null,'ilmo',0);
INSERT INTO naviTitles(id,lang,url,title) VALUES (5,'fi','ilmo','Ilmot');

INSERT INTO naviNodes(id,parent,module,nodeId) VALUES (6,null,'ilmo',1);
INSERT INTO naviTitles(id,lang,url,title) VALUES (6,'fi','taskumatti','Taskumatti');

INSERT INTO signup(id,lang,title,description) VALUES (1,'fi','Taskumatti','Tilaa taskumatti');


-- SET UP SEQUENCES
SELECT setval('naviNodesSeq', 100);
SELECT setval('contentSeq', 100);
SELECT setval('newsSeq', 100);
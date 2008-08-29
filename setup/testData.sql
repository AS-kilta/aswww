INSERT INTO site(skin) VALUES ('aski-v2');

-- PAGES
INSERT INTO naviNodes(parent,contentModule) VALUES (null,'news',);
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','','Etusivu');
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'en','english','Front page');

INSERT INTO pages(lang,content) VALUES ('fi','<h1>Kilta</h1>');
INSERT INTO pages(lang,content) VALUES ('en','<h1>Guild</h1>');

INSERT INTO naviNodes(parent,contentModule,contentId) VALUES (null,'page',(SELECT currval('pages_id_seq')));
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','kilta','Kilta');
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'en','guild','Guild');


-- Users
INSERT INTO users(id,username,password,realname) VALUES (1,'tapio',md5('tapio'),'Tapio Auvinen');
INSERT INTO users(id,username,password,realname) VALUES (2,'antti',md5('antti'),'Antti Nieminen');
INSERT INTO users(id,username,password,realname) VALUES (3,'jaakko',md5('jaakko'),'Jaakko Kantojärvi');

-- SIGNUP
INSERT INTO signup(lang,title,description) VALUES ('fi','Taskumatti','Tilaa taskumatti');
INSERT INTO naviNodes(parent,contentModule,contentId,hidden) VALUES (null,'ilmo',(SELECT currval('signup_id_seq')),true);
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','taskumatti','Taskumatti');

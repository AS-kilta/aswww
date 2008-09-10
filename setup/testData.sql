INSERT INTO site(skin) VALUES ('aski-v2');

-- Front page
INSERT INTO naviNodes(parent,contentModule) VALUES (null,'news');
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','','Etusivu');
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'en','english','Front page');

-- Event calendar
INSERT INTO naviNodes(parent,contentModule,hidden) VALUES (null,'events',true);
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','tapahtumat','Tapahtumat');
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'en','calendar','Events');

-- Pages
INSERT INTO pages(lang,content) VALUES ('fi','<h1>Kilta</h1>');
INSERT INTO pages(id,lang,content) VALUES ((SELECT currval('pages_id_seq')),'en','<h1>Guild</h1>');

INSERT INTO naviNodes(parent,contentModule,contentId) VALUES (null,'page',(SELECT currval('pages_id_seq')));
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','kilta','Kilta');
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'en','guild','Guild');

-- Users
INSERT INTO users(username,password,realname) VALUES ('tapio',md5('tapio'),'Tapio Auvinen');
INSERT INTO users(username,password,realname) VALUES ('jaakko',md5('jaakko'),'Jaakko Kantojarvi');

-- SIGNUP
INSERT INTO signup(lang,title,description) VALUES ('fi','Taskumatti','Tilaa taskumatti');
INSERT INTO naviNodes(parent,contentModule,contentId,hidden) VALUES (null,'ilmo',(SELECT currval('signup_id_seq')),true);
INSERT INTO naviTitles(id,lang,url,title) VALUES ((SELECT currval('navinodes_id_seq')),'fi','taskumatti','Taskumatti');

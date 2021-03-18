drop table if exists joueur cascade;
drop table if exists categorie cascade;
drop table if exists entreprise cascade;
drop table if exists jeu cascade;
drop table if exists succes cascade;
drop table if exists etre cascade;
drop table if exists acheter cascade;
drop table if exists obtenir cascade;
drop table if exists commenter cascade;
drop table if exists noter cascade;
drop table if exists reapprovisionner cascade;
drop table if exists amitie cascade;
drop table if exists partager cascade;



create table joueur(
idjoueur serial,
nom varchar(25) not null,
naissance date not null,
pseudo varchar(25) unique not null,
mdp varchar(50) not null,
mail varchar(25) unique default null, 
/*pas besoin lors de la connexion, 
on admet que le mail peut ne pas être renseignée*/
argent int default 0,
primary key(idjoueur));

create table categorie(
nom varchar(25),
primary key(nom));

create table entreprise(
ident serial,
nom varchar(25) not null,
pays varchar(25),
primary key(ident));

create table jeu(
idjeu serial,
titre varchar(25) not null unique,
datesortie date not null,
age_requis int,
description text,
iddev int, /*id developpeur*/
idedi int, /*id editeur*/
primary key(idjeu),
foreign key(iddev) references entreprise(ident)
on delete set null on update cascade,
foreign key(idedi) references entreprise(ident)
on delete set null on update cascade);

create table succes(
code serial,
intitule varchar(50) not null,
description text,
idjeu int,
primary key(code),
foreign key(idjeu) references jeu(idjeu)
on delete cascade on update cascade);

create table etre( /*definition de la categorie d'un jeu*/
idjeu int,
nom varchar(25),
primary key(idjeu, nom),
foreign key(idjeu) references jeu(idjeu)
on delete cascade on update cascade,
foreign key(nom) references categorie(nom)
on delete cascade on update cascade);

create table acheter(
idjoueur int,
idjeu int,
prix int not null,
primary key(idjoueur, idjeu),
foreign key(idjoueur) references joueur(idjoueur)
on delete cascade on update cascade,
foreign key(idjeu) references jeu(idjeu)
on delete cascade on update cascade);

create table obtenir( /*obtenir le succès d'un jeu*/
idjoueur int,
code int,
date_obtention date not null,
primary key(idjoueur, code),
foreign key(idjoueur) references joueur(idjoueur)
on delete cascade on update cascade,
foreign key(code) references succes(code)
on delete cascade on update cascade);

create table commenter(
idjoueur int, 
idjeu int,
commentaire text not null,
primary key(idjoueur, idjeu),
foreign key(idjoueur) references joueur(idjoueur)
on delete cascade on update cascade,
foreign key(idjeu) references jeu(idjeu)
on delete cascade on update cascade);

create table noter(
idjoueur int,
idjeu int,
note int not null check (note between 0 and 5), /*not null sinon impossible de faire une note moyenne*/
primary key(idjoueur, idjeu),
foreign key(idjoueur) references joueur(idjoueur)
on delete cascade on update cascade,
foreign key(idjeu) references jeu(idjeu)
on delete cascade on update cascade);

create table reapprovisionner(
idjoueur int,
date_rea timestamp, 
/*pour permettre plusieurs réapprovisionnement le meme jour*/
montant int not null,
primary key(idjoueur, date_rea),
foreign key(idjoueur) references joueur(idjoueur)
on delete cascade on update cascade);

create table amitie(
idjoueur1 int,
idjoueur2 int,
primary key(idjoueur1, idjoueur2),
foreign key(idjoueur1) references joueur(idjoueur)
on delete cascade on update cascade,
foreign key(idjoueur2) references joueur(idjoueur)
on delete cascade on update cascade);

create table partager(
idjeu int,
idjoueur_pret int,
idjoueur2 int,
primary key(idjeu, idjoueur_pret),
foreign key(idjeu) references jeu(idjeu)
on delete cascade on update cascade,
foreign key(idjoueur_pret) references joueur(idjoueur)
on delete cascade on update cascade,
foreign key(idjoueur2) references joueur(idjoueur)
on delete cascade on update cascade);


insert into joueur(nom, naissance, pseudo, mdp, mail) values ('HEUDE', '2001/01/11', 'Velaha', md5('123bdd'), 'aheude@etud.u-pem.fr');
insert into joueur(nom, naissance, pseudo, mdp, mail, argent) values ('HUYNH VAN LOC', '2000/12/14', 'Pheyl', md5('456dbb'), null, 80);
insert into joueur(nom, naissance, pseudo, mdp, mail, argent) values ('VAN', '2000/03/31', 'Cycyplay', md5('123'), 'cvan1@etud.u-pem.fr', 50);
insert into joueur(nom, naissance, pseudo, mdp, mail, argent) values ('YILDIRIM', '2000/12/14', 'Nuzylumb', md5('123'), null, 0);
insert into joueur(nom, naissance, pseudo, mdp, mail, argent) values ('HEUDE', '1972/08/04', 'Vinz', md5('123'), null, 0);
insert into joueur(nom, naissance, pseudo, mdp, mail, argent) values ('Dauss', '2010/10/10', 'Raven', md5('123'), null, 20);


insert into categorie values ('Jeu de Role'), ('Action'), ('Aventure'), ('Survival horror'), ('Plates-formes'), ('Rogue-like');


insert into entreprise(nom, pays) values ('Devolver', 'Etat-Unis'); 
insert into entreprise(nom, pays) values ('ConcernedApe', 'Etat-Unis');
insert into entreprise(nom, pays) values ('DeadToast', null);
insert into entreprise(nom, pays) values ('Chucklefish Games', null);
insert into entreprise(nom, pays) values ('The Fun Pimps', null);
insert into entreprise(nom, pays) values ('Bulkypix', null);
insert into entreprise(nom, pays) values ('TreeFortress', null);
insert into entreprise(nom, pays) values ('Free Lives', null);
insert into entreprise(nom, pays) values ('Torn Banner Studios', null);
insert into entreprise(nom, pays) values ('Red Hook Studios', null);


insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('My Friend Pedro', '2019/06/20', 18, 'Le personnage du joueur utilise différentes techniques pour progresser dans des niveaux.', 3, 1);
insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('Stardew Valley', '2016/02/26', 7, 'Le personnage doit gérer sa ferme.', 2, 4);
insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('7 Days to Die', '2013/12/13', 18, 'Le joueur doit survivre dans un monde post Troisième Guerre Mondiale.', 5, 5);
insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('Bardbarian', '2014/01/16', 7, null, 7, 6);
insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('Broforce', '2015/10/15', 16, 'Broforce consiste à parcourir des niveaux en grande partie destructibles afin d''atteindre un hélicoptère.', 8, 1);
insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('Chivalry', '2012/10/16', 18, 'Le joueur peut choisir parmi 4 classes de personnages, chacune ayant accès à différentes armes.', 9, 9);
insert into jeu(titre, datesortie, age_requis, description, iddev, idedi) values ('Darkest Dungeon', '2016/01/19', 16, 'Darkest Dungeon permet au joueur de contrôler une équipe d''aventuriers qui explore des donjons.', 10, 10);


insert into succes(intitule, description, idjeu) values ('Because video games', 'Casser 100 boites', 1);
insert into succes(intitule, description, idjeu) values ('Mumbo Combo', 'Faire un combo x10', 1);
insert into succes(intitule, idjeu) values('Combo dingo', 1);
insert into succes(intitule, description, idjeu) values ('Moving Up', 'Agrandir sa maison', 2);
insert into succes(intitule, description, idjeu) values ('The Bottom', 'Atteindre le niveau le plus profond de la mine', 2);
insert into succes(intitule, description, idjeu) values ('Best Friend', 'Devenir meilleur ami avec 5 personnes', 2);
insert into succes(intitule, description, idjeu) values ('Alexander Bell', 'Fabriquer 50 objets', 3);
insert into succes(intitule, description, idjeu) values ('Le fossoyeur', 'Tuer 10 zombies', 3);
insert into succes(intitule, description, idjeu) values ('Taillé à la hache', 'Fabriquer votre première hache de pierre', 3);
insert into succes(intitule, description, idjeu) values ('Early Bird', 'Compléter le chapitre <Morning>', 4);
insert into succes(intitule, description, idjeu) values ('Favoritism', 'Améliorer une unité au niveau maximum', 4);
insert into succes(intitule, description, idjeu) values ('Keep it Movin''', 'Ne pas se faire toucher', 4);
insert into succes(intitule, description, idjeu) values ('You''re the disease, I''m the cure', 'Finir la campagne Ironbro', 5);
insert into succes(intitule, description, idjeu) values ('There can bro only one', 'Finir la campagne sans mourir', 5);
insert into succes(intitule, description, idjeu) values ('Rest in pieces', null, 5);
insert into succes(intitule, description, idjeu) values ('Heaume du conquérant vétéran', 'Devenez un conquérant vétéran', 6);
insert into succes(intitule, description, idjeu) values ('Fabriquant d''épées', 'Débloquer toutes les épées', 6);
insert into succes(intitule, description, idjeu) values ('Niveau 5 atteint', 'Obtenez suffisamment de points pour atteindre le niveau 5', 6);
insert into succes(intitule, description, idjeu) values ('Bienvenue à la maison...', 'Atteindre le domaine', 7);
insert into succes(intitule, description, idjeu) values ('La première d''une série de victoire...', 'Terminer une quete', 7);
insert into succes(intitule, description, idjeu) values ('Et notre formation débute...', 'Améliorer une capacité de combat', 7);


insert into etre values (1, 'Action');
insert into etre values (2, 'Jeu de Role');
insert into etre values (3, 'Survival horror');
insert into etre values (4, 'Action');
insert into etre values (5, 'Plates-formes');
insert into etre values (6, 'Action');
insert into etre values (7, 'Rogue-like');


insert into acheter values (1, 1, 15);
insert into acheter values (1, 2, 10);


insert into obtenir values (1, 1, '2019/09/10');


insert into commenter values (1, 2, 'Jeu d''une qualité incroyable');


insert into noter values (1, 2, 5);


insert into reapprovisionner values (1, now(), 20);


insert into amitie values (1, 2);
insert into amitie values (1, 3);
insert into amitie values (1, 4);


insert into partager values (2, 1, 2);
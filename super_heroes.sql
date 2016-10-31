CREATE DATABASE super_heroes;

USE super_heroes;

/* TABLA DE DATOS*/
CREATE TABLE heroes(
	id_heroe int not null AUTO_INCREMENT,
	nombre varchar(20) not null,
	imagen varchar(100) not null,
	descripcion text null,
	editorial int not null,
	PRIMARY KEY(id_heroe)
) ENGINE = MyISAM DEFAULT CHARSET = utf8; 

CREATE TABLE editorial(
	id_editorial int not null AUTO_INCREMENT,
	editorial varchar(15) not null,
	PRIMARY KEY(id_editorial)
) ENGINE = MyISAM DEFAULT CHARSET = utf8;

INSERT INTO editorial(id_editorial,editorial)
VALUES(1,"DC Comics"),(2, "Marvel Comics"),(3,"Shonen Jump"),(4,"Vertigo"),(5,"Mirage Studio"),(6,"Icon Comics");


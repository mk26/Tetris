START TRANSACTION;

CREATE USER 'mk'@'localhost' IDENTIFIED BY 'mkpass';

GRANT ALL ON *.* TO 'mk'@'localhost';

CREATE DATABASE MKTETRIS;

USE MKTETRIS;

CREATE TABLE USERS
(
	username varchar(20) NOT NULL,
  	password varchar(20) NOT NULL,
  	name varchar(50) NOT NULL,
  	lastscore integer DEFAULT 0,
  	highscore integer DEFAULT 0,
	plays integer DEFAULT 0,
  	PRIMARY KEY (username)
);

# CREATE TEST USER

INSERT INTO USERS(username,password,name) VALUES ("test","test","TestUser");

COMMIT;
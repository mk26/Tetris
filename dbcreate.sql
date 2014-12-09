START TRANSACTION;

CREATE USER 'mk'@'localhost' IDENTIFIED BY 'mkpass';

GRANT ALL ON *.* TO 'mk'@'localhost';

CREATE DATABASE MKTETRIS;

USE MKTETRIS;

CREATE TABLE USERS
(
	username varchar(20) NOT NULL,
  	password TEXT NOT NULL,
  	name varchar(50) NOT NULL,
  	lastscore integer DEFAULT 0,
  	highscore integer DEFAULT 0,
	plays integer DEFAULT 0,
  	PRIMARY KEY (username)
);

# CREATE TEST USER

INSERT INTO USERS(username,password,name) VALUES ("test","$2y$10$j3HwSMewTq0q/q0./oeuEeDrAVuTi41XOdffkUPscjZCTUNjud2Ba","TestUser");

COMMIT;
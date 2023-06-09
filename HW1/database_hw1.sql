use restaurant;

create table UTENTE (
	ID integer primary key not null auto_increment,
	USERNAME varchar(255),
    PASSWORD varchar(255),
    GESTORE integer check(GESTORE >= 0 and GESTORE <= 1)
);

create table RECENSIONE (
	ID integer primary key not null auto_increment,
    UTENTE integer,
    index id1(UTENTE),
    foreign key(UTENTE) references UTENTE(ID),
    VOTO integer check (VOTO >= 0 and VOTO <= 5),
    COMMENTO varchar(255)
);

create table CATEGORIA (
	ID integer primary key not null auto_increment,
    NOME varchar(255),
    IMMAGINE varchar(255)
);

create table PIETANZA (
	ID integer primary key not null auto_increment,
    NOME varchar(255),
    PREZZO float,
    CATEGORIA integer,
    index id2(CATEGORIA),
    foreign key(CATEGORIA) references CATEGORIA(ID),
    IMMAGINE varchar(255)
);

create table PRENOTAZIONE (
	ID integer primary key not null auto_increment,
    TITOLO varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    DESCRIZIONE varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    DATA date,
    ORARIO_DA time,
    ORARIO_FINO time,
    GOOGLE_CALENDAR_EVENT_ID varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	CREATED datetime NOT NULL DEFAULT current_timestamp(),
    UTENTE integer,
    index id1(UTENTE),
    foreign key(UTENTE) references UTENTE(ID)
) DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `google_calendar_event_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO UTENTE (USERNAME, PASSWORD, GESTORE) VALUES ('gestore', 'Scudy247!', '1');

INSERT INTO CATEGORIA (NOME) VALUES('ANTIPASTI');
INSERT INTO CATEGORIA (NOME) VALUES('PRIMI');
INSERT INTO CATEGORIA (NOME) VALUES('SECONDI');
INSERT INTO CATEGORIA (NOME) VALUES('CONTORNI');
INSERT INTO CATEGORIA (NOME) VALUES('DESSERT');
INSERT INTO CATEGORIA (NOME) VALUES('BEVANDE');

INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('CAPONATA', '4.00', '1');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('FRITTELLE', '2.50', '1');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('BROCCOLI AFFOGATI', '3.50', '1');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PEPERONI CON MOLLICA', '4.00', '1');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PASTA ALLA NORMA', '10.00', '2');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PASTA AL RAGU', '9.00', '2');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PASTA AL TONNO', '11.00', '2');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PASTA AL PESTO', '9.00', '2');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PARMIGIANA', '12.00','3');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('CARNE ALLA PIZZAIOLA', '11.00', '3');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('FRITTATA DI PATATE', '9.00','3');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('COTOLETTA', '10.00', '3');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PATATE AL FORNO', '5.00', '4');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PATATE FRITTE', '3.50', '4');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('VERDURE GRIGLIATE', '4.00', '4');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('INSALATA VERDE', '3.00', '4');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('CHEESECAKE AL LAMPONE', '4.00', '5');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('CANNOLO ALLA RICOTTA', '2.00', '5');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('CASSATA', '3.00', '5');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('PROFITTEROL', '5.00', '5');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('ACQUA NATURALE', '1.50', '6');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('ACQUA FRIZZANTE', '1.50', '6');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('COCA-COLA', '2.50', '6');
INSERT INTO PIETANZA (NOME, PREZZO, CATEGORIA) VALUES('BIRRA ALLA SPINA', '4.00', '6');



SELECT * FROM UTENTE;
SELECT * FROM CATEGORIA;
SELECT * FROM PIETANZA;
SELECT * FROM PRENOTAZIONE;
SELECT * FROM RECENSIONE;
SELECT * from events;

SELECT U.USERNAME, R.VOTO, R.COMMENTO FROM RECENSIONE R JOIN UTENTE U ON R.UTENTE = U.ID order by r.voto desc;
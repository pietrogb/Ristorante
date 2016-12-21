drop table if exists Clienti;
drop table if exists Prenotazioni;
drop table if exists Log; 
drop table if exists Piatti;
drop table if exists CartaFedelta;
drop table if exists Antipasti;
drop table if exists Primi;
drop table if exists Secondi;
drop table if exists Contorni;
drop table if exists Dolci;
drop table if exists Piatti speciali;
drop table if exists Bevande;


create table Clienti (
	Id int primary key,
	Nome varchar(20) not null,
	Cognome varchar(20) not null,
	E-mail varchar(20) set null,
	Telefono int set null,
	Data registrazione date not null
	)Engine=InnoDB;

create table CartaFedelta (
	IdCliente int primary key,
	TotPunti int set null,
	FOREIGN KEY (IdCliente) REFERENCES Clienti(Id)
       	          	ON DELETE CASCADE
	)Engine=InnoDB;

create table Log (
	CodLog int primary key,
	CodCliente int,
	DataAccesso date not null,
	FOREIGN KEY (CodCliente) REFERENCES Clienti(Id)
       	          	ON DELETE CASCADE
	)Engine=InndoDB;

create table Prenotazioni (
	Codice int primary key,
	Data creazione date not null,
	Data prenotazione date not null,
	Numero posti int not null,
	Cliente int
	FOREIGN KEY (Cliente) REFERENCES Clienti(Id)
       	          	ON DELETE CASCADE,
	FOREIGN KEY (Codice) REFERENCES Clienti(Id)
       	          	ON DELETE CASCADE
	)Engine=InnoDB;

create table Piatti (
	CodPiatto int  primary key,
	Nome varchar(20) not null,
	Costo int not null,
	)Engine=InnoDB;

create table Antipasto (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;

create table Primo (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;
	
create table Secondo (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;

create table Contorni (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;

create table Dolci (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;

create table Piatti speciali (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;

create table Bevande (
	Codice int primary key,
	FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
			ON DELETE CASCADE
	)Engine=InnoDB;
	


set foreign_key_checks=0;
drop table if exists Antipasti;
drop table if exists Primi;
drop table if exists Secondi;
drop table if exists Contorni;
drop table if exists Dolci;
drop table if exists PiattiSpeciali;
drop table if exists Bevande;
drop table if exists PrenotazioniPiatti;
drop table if exists Prenotazioni;
drop table if exists Utenti;
drop table if exists Accessi;
drop table if exists Piatti;
drop table if exists CartaFedelta;
drop table if exists Clienti;
drop table if exists Ricevute;
drop table if exists Errori;

create table Errori(
  CodErr int primary key,
  Descrizione varchar(255)
  )Engine=InnoDB;

create table Clienti(
  Id int primary key,
  Nome varchar(20) not null,
  Cognome varchar(20) not null,
  Email varchar(40),
  Telefono varchar(20) not null,
  DataReg date
  )Engine=InnoDB;

create table Utenti (
  Id int primary key,
  Login varchar(20) not null unique,
  password varchar(40) not null,
  livello int default 1,
  FOREIGN KEY (Id) REFERENCES Clienti(Id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CHECK(livello IN('1','2'))
  )Engine=InnoDB;

create table CartaFedelta (
  IdCliente int primary key,
  TotPunti int default 0,
  FOREIGN KEY (IdCliente) REFERENCES Clienti(Id)
    ON DELETE CASCADE ON UPDATE CASCADE
  )Engine=InnoDB;

  DELIMITER $
  create trigger NuovaCarta
  after insert on Clienti
  for each row
  begin
    insert into CartaFedelta
      values(new.Id, 0);
  end $
  DELIMITER ;
  
create table Accessi(
  CodLog int primary key,
  CodCliente int not null,
  DataAccesso date not null,
  FOREIGN KEY (CodCliente) REFERENCES Clienti(Id)
    ON DELETE NO ACTION ON UPDATE CASCADE
  )Engine=InnoDB;

create table Prenotazioni(
  Codice int primary key,
  DataCrea date not null,
  DataPren date not null,
  NumeroPosti int not null,
  CodCliente int,
  FOREIGN KEY (CodCliente) REFERENCES Clienti(Id)
    ON DELETE NO ACTION ON UPDATE CASCADE
  )Engine=InnoDB;

create table Piatti (
  CodPiatto int  primary key,
  Nome varchar(120) not null,
  Costo decimal(15,2) not null
  )Engine=InnoDB;

create table Antipasti (
  Codice int primary key,
  FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
    ON DELETE CASCADE
  )Engine=InnoDB;

create table Primi (
  Codice int primary key,
  FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
    ON DELETE CASCADE
  )Engine=InnoDB;
	
create table Secondi (
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

create table PiattiSpeciali (
  Codice int primary key,
  FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
    ON DELETE CASCADE
  )Engine=InnoDB;

create table Bevande (
  Codice int primary key,
  FOREIGN KEY (Codice) REFERENCES Piatti(CodPiatto)
    ON DELETE CASCADE
  )Engine=InnoDB;

create table PrenotazioniPiatti (
  CodPrenotazione int not null,
  CodPiatto int not null,
  NPorzioni int,
  PRIMARY KEY (CodPrenotazione,CodPiatto),
  FOREIGN KEY (CodPrenotazione) REFERENCES Prenotazioni(Codice)
    ON DELETE CASCADE,
  FOREIGN KEY (CodPiatto) REFERENCES Piatti(CodPiatto)
    ON DELETE CASCADE ON UPDATE CASCADE
  )Engine=InnoDB;

create table Ricevute (
  Codice int primary key,
  Totale decimal(15,2),
  TotPunti int default 0,
  FOREIGN KEY (Codice) REFERENCES Prenotazioni(Codice)
    ON DELETE NO ACTION ON UPDATE CASCADE
  )Engine=InnoDB;

  DELIMITER $
  create trigger ControlloPunti
  before insert on Ricevute
  for each row
    begin
    declare PuntiCliente INT;
      select cf.TotPunti into PuntiCliente
      from CartaFedelta cf
      where cf.IdCliente=(select CodCliente from Prenotazioni where Codice=new.Codice);
      if new.TotPunti>PuntiCliente
	then 
	  insert into Errori
	  values('01','Non ci sono sufficienti punti nella tessera');
      end if;
  end $
  
  create trigger InserisciPunti
  after insert on Ricevute
  for each row
    begin
    declare TPunti int;
    declare CodC int;
    select CodCliente into CodC
    from Prenotazioni
    where Codice=new.Codice;
    Update CartaFedelta 
    set TotPunti=TotPunti+(new.Totale/10)
    where IdCliente=CodC;
  end $
  
  drop function if exists PrezzoPrenotazione;
  create function PrezzoPrenotazione(IdP INT)
  returns decimal(15,2)
  begin
    declare total decimal(15,2);
    
    select sum(pr.NPorzioni*pi.Costo) into total
    from PrenotazioniPiatti pr natural join Piatti pi
    where pr.CodPrenotazione=IdP;
    
    return total;
  end $
  
  
  drop function if exists PuntiTotali;
  create function PuntiTotali(IdC INT)
  returns INT
  begin
    declare nric, punti, tot INT;
    
    select count(*) into nric
    from Ricevute r natural join Prenotazioni p
    where p.CodCliente=IdC;
    
    if (nric=0)
    then
      select 0 as Ptotali into punti;
    else
      select sum(r.Totale/10) into punti
      from Ricevute r natural join Prenotazioni p
      where p.CodCliente=IdC;
    end if;
    select c.TotPunti into tot
    from CartaFedelta c
    where c.IdCliente=IdC;
    
    return punti+tot;
  end $
  
  drop procedure if exists InserisciRicevuta;
  create procedure InserisciRicevuta(IdP INT, TPunti INT)
  begin
    declare Totale decimal(15,2);
    select PrezzoPrenotazione(IdP)-(2*Tpunti) into Totale
    from Prenotazioni
    where Codice=IdP;
    insert into Ricevute values(IdP, Totale, TPunti);
  end $
  
  drop function if exists MaxPunti;
  create function MaxPunti(IdP INT)
  returns INT
  begin
    declare M1, M2, MaxP INT;
    
    select PrezzoPrenotazione(IdP)/10 into M1;
    
    select c.TotPunti into M2
    from Prenotazioni p join CartaFedelta c on p.CodCliente=c.IdCliente
    where p.Codice=IdP;
    
    if(M1<M2)
    then
      return M1;
    else
      return M2;
    end if;
  end $
  
  DELIMITER ;
  
SOURCE insert.sql;

SET FOREIGN_KEY_CHECKS=1;
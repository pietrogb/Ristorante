-- Nome dei clienti che hanno cenato al ristorante lo stesso giorno dell'anno in cui si sono iscritti
drop view if exists PrenGM;
create view PrenGM as(
  select p.Codice, p.CodCliente, day(p.DataPren) as Giorno, month(p.DataPren) as Mese, DataPren
  from Prenotazioni p natural join Ricevute
);

drop view if exists ClGM;
create view ClGM as(
  select Id, Nome, Cognome, day(DataReg) as Giorno, month(DataReg) as Mese, DataReg
  from Clienti 
);

select c.Id, c.Nome, c.Cognome, c.DataReg, pg.Codice as CodPren, pg.DataPren
from ClGM c join PrenGM pg on c.Id=pg.CodCliente
where c.Mese=pg.Mese and c.Giorno=pg.Giorno;
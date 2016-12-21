-- Voci di costo massimo all'interno delle prenotazioni di ogni cliente, accompagnati dal

drop view if exists PreP;
Create view PreP as(
  select pp.CodPrenotazione, max(p.Costo*pp.Nporzioni) as 'Subtot', pp.Nporzioni, p.Nome
  from PrenotazioniPiatti pp natural join Piatti p
  group by pp.CodPrenotazione
);

select p.CodPrenotazione as CodPren, p.Nporzioni as 'Q.ta', p.Nome as NomeP, max(p.Subtot) as Subtot, pr.DataPren, c.Nome as NomeC, c.Cognome, c.Id
from PreP p, Prenotazioni pr join Clienti c on pr.CodCliente=c.Id
where p.CodPrenotazione=pr.Codice
group by c.Id

union

select '-' as CodPren, '-' as 'Q.ta', '-' as NomeP, 0 as Subtot, '-' as DataPren, Nome as NomeC, Cognome, Id
from Clienti
where Id not in (select distinct CodCliente from Prenotazioni join Ricevute)

order by Subtot desc;
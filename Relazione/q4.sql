-- Piatto di costo massimo tra le prenotazioni di costo minimo d'ogni cliente
drop view if exists CostoPren;
create view CostoPren as(
  select Codice, CodCliente, PrezzoPrenotazione(Codice) as Totale
  from Prenotazioni
);

drop view if exists MinPren;
create view MinPren as(
select cp.Codice, min(Totale) as MinTot, cp.CodCliente
from CostoPren cp
group by cp.CodCliente
);

select c.Nome as NomeC, c.Cognome ,mp.MinTot as 'MinPren(E)', max(p.costo) as 'MaxPiat(E)', p.Nome as NomeP, p.Costo
from MinPren mp join PrenotazioniPiatti pp on mp.Codice=pp.CodPrenotazione natural join Piatti p join Clienti c on mp.CodCliente=c.Id
group by mp.Codice;
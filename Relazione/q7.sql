-- Clienti che hanno ordinato bevande per una cifra superiore alla somma del totale delle pietanze in prenotazioni con piu` di 4 porzioni in cui sono stati utilizzati piu` di 10 punti
drop view if exists PrenB;
create view PrenB as
  select pp.CodPrenotazione, sum(pp.Nporzioni*p.Costo) as TBev
  from PrenotazioniPiatti pp join Bevande b on pp.CodPiatto=b.Codice natural join Piatti p
  group by pp.CodPrenotazione

  union all

  select distinct pp.CodPrenotazione, 0.00 as TBev
  from PrenotazioniPiatti pp where pp.CodPrenotazione not in 
    (select distinct p.CodPrenotazione from PrenotazioniPiatti p join Bevande b on p.CodPiatto=b.Codice)
  
  order by CodPrenotazione;

drop view if exists Temp;
create view Temp as
select pp.CodPrenotazione, sum(pp.Nporzioni*p.Costo) as TCib
from PrenotazioniPiatti pp natural join Piatti p
where not exists(
  select *
  from Bevande b 
  where pp.CodPiatto=b.Codice)
group by pp.CodPrenotazione
;

drop view if exists PrenC;
create view PrenC as
  select CodPrenotazione, TCib 
  from Temp
  
  union
  
  select distinct pp.CodPrenotazione as CodPrenotazione, 0.00 as TCib
  from PrenotazioniPiatti pp where pp.CodPrenotazione not in 
    (select distinct pp.CodPrenotazione from Temp);

select cl.Id,cl.Nome,cl.Cognome, b.Tbev as TotBevande, c.TCib as TotNonBevande
from PrenB b natural join PrenC c join Prenotazioni p on b.CodPrenotazione=p.Codice join Clienti cl on p.CodCliente=cl.Id
where b.Tbev > c.Tcib;
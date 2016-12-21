-- Nome, Cognome, indirizzo e-mail di clienti che non hanno fatto nessuna prenotazione e dei clienti che hanno effettuato una prenotazione prima del 2008
select c.*
from Clienti c
where c.Id not in (select distinct CodCliente from Prenotazioni join Ricevute)

union

select c.*
from Clienti c
where c.Id in (select distinct CodCliente from Prenotazioni join Ricevute where DataCrea<'2008-01-01')
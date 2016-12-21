-- Primi 10 clienti clienti che hanno effettuato una prenotazione al ristorante
select distinct c.*, p.Codice, p.DataCrea as Creazione
from Prenotazioni p join Clienti c on p.CodCliente=c.Id
order by DataCrea 
LIMIT 10;
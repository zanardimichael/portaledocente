# Gestione Verifiche

## Struttura Verifiche
- vero-falso (con possibilità di inserire il testo di ogni domanda e la soluzione)
- domande a risposta multipla (con possibilità di inserire la domanda e le soluzioni)
- domande a risposta aperta (con possibilità di inserire il testo della domanda)
- esercizi (con possibilità di inserire l'esercizio e le soluzioni)
- aggiunta di verifica associata personalizzata per PEI/PDP
- possibilità di generazione esercizio su LaTeX

## Risultati
- caricamento risultati alunno e possibilità di ignorare alcuni esercizi e segnare parziali i vero-falso
- calcolo voto basandosi su punteggio pieno o personalizzato
- possibilità di inserimento di un'eventuale penalità (per continui richiami)

## Possibile struttura:

### classe

| name                | type        | altro                               | indice  |
|---------------------|-------------|-------------------------------------|---------|
| ID                  | int(11)     | auto_increment                      | primary |
| anno                | int(11)     | `//primo anno dell'anno scolastico` |
| classe              | int(11)     |
| sezione             | varchar(4)  |
| note                | varchar(64) |
| timestamp_modifica  | timestamp   |
| timestamp_creazione | varchar(64) |

### alunno

| name                | type        | altro                               | indice  |
|---------------------|-------------|-------------------------------------|---------|
| ID                  | int(11)     | auto_increment                      | primary |
| ID_classe           | int(11)     |
| nome                | varchar(64) |
| cognome             | varchar(64) |
| email               | varchar(64) |
| note                | varchar(64) |
| timestamp_modifica  | timestamp   |
| timestamp_creazione | timestamp   |

### verifica

| name          | type         | altro                                                      | indice  |
|---------------|--------------|------------------------------------------------------------|---------|
| ID            | int(11)      | auto_increment                                             | primary |
| ID_professore | int(11)      |
| ID_materia    | int(11)      |
| ID_verifica   | int(11)      | `//se impostato segnala che è una verifica personalizzata` |
| titolo        | varchar(512) |
| note          | varchar(64)  |
| ordine        | tinyint(3)   |

### verofalso

| name        | type         | altro                | indice  |
|-------------|--------------|----------------------|---------|
| ID          | int(11)      | auto_increment       | primary |
| ID_verifica | int(11)      |
| testo       | varchar(512) |
| risultato   | tinyint(1)   | `//1 vero - 0 falso` |         |
| note        | varchar(64)  |
| punteggio   | tinyint(2)   |
| ordine      | tinyint(3)   |

### rispostamultipla

| name        | type         | altro                | indice  |
|-------------|--------------|----------------------|---------|
| ID          | int(11)      | auto_increment       | primary |
| ID_verifica | int(11)      |
| testo       | varchar(512) |
| note        | varchar(64)  |
| punteggio   | tinyint(2)   |
| ordine      | tinyint(3)   |

### rispostamultipla_risposte

| name                | type         | altro                    | indice  |
|---------------------|--------------|--------------------------|---------|
| ID                  | int(11)      | auto_increment           | primary |
| ID_rispostamultipla | int(11)      |
| testo               | varchar(512) |                      
| corretto            | tinyint(1)   | `//1 corretto - 0 falso` | 
| punteggio           | tinyint(2)   |
| ordine              | tinyint(3)   |

### rispostaaperta

| name        | type         | altro              | indice  |
|-------------|--------------|--------------------|---------|
| ID          | int(11)      | auto_increment     | primary |
| ID_verifica | int(11)      |
| testo       | varchar(512) |
| note        | varchar(64)  |
| punteggio   | tinyint(2)   |
| ordine      | tinyint(3)   |

### esercizio

| name        | type         | altro              | indice  |
|-------------|--------------|--------------------|---------|
| ID          | int(11)      | auto_increment     | primary |
| ID_verifica | int(11)      |
| testo       | varchar(512) |
| note        | varchar(64)  |
| punteggio   | tinyint(2)   |
| ordine      | tinyint(3)   |
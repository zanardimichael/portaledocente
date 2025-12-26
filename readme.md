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

### classe <span style="color:green">(implementata)</span>

| name                | type        | altro                               | indice  |
|---------------------|-------------|-------------------------------------|---------|
| ID                  | int(11)     | auto_increment                      | primary |
| anno                | int(11)     | `//primo anno dell'anno scolastico` |
| classe              | int(11)     |
| sezione             | varchar(4)  |
| note                | varchar(64) |
| timestamp_modifica  | timestamp   |
| timestamp_creazione | varchar(64) |

### alunno <span style="color:green">(implementata)</span>

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

### professore <span style="color:green">(implementata)</span>

| name                | type        | altro                               | indice  |
|---------------------|-------------|-------------------------------------|---------|
| ID                  | int(11)     | auto_increment                      | primary |
| ID_utente           | int(11)     |
| note                | varchar(64) |
| timestamp_modifica  | timestamp   |
| timestamp_creazione | timestamp   |

### materia <span style="color:green">(implementata)</span>

| name                | type         | altro                               | indice  |
|---------------------|--------------|-------------------------------------|---------|
| ID                  | int(11)      | auto_increment                      | primary |
| nome                | varchar(128) |
| note                | varchar(64)  |
| timestamp_modifica  | timestamp    |
| timestamp_creazione | timestamp    |

### materia_professore_classe <span style="color:green">(implementata)</span>

| name          | type    | altro | indice |
|---------------|---------|-------|--------|
| ID_materia    | int(11) |
| ID_professore | int(11) |
| ID_classe     | int(11) |

### verifica <span style="color:green">(implementata)</span>

| name                | type         | altro                                                      | indice  |
|---------------------|--------------|------------------------------------------------------------|---------|
| ID                  | int(11)      | auto_increment                                             | primary |
| ID_professore       | int(11)      |
| ID_materia          | int(11)      |
| ID_classe           | int(11)      |
| ID_verifica         | int(11)      | `//se impostato segnala che è una verifica personalizzata` |
| titolo              | varchar(128) |
| note                | varchar(64)  |
| ordine              | tinyint(3)   |
| timestamp_modifica  | timestamp    |
| timestamp_creazione | timestamp    |

### verifica_sezione <span style="color:green">(implementata)</span>

| name                | type         | altro                | indice  |
|---------------------|--------------|----------------------|---------|
| ID                  | int(11)      | auto_increment       | primary |
| ID_verifica         | int(11)      |
| titolo              | varchar(128) |
| ordine              | tinyint(3)   |
| timestamp_modifica  | timestamp    |
| timestamp_creazione | timestamp    |

### verifica_verofalso <span style="color:green">(implementata)</span>

| name                | type         | altro                | indice  |
|---------------------|--------------|----------------------|---------|
| ID                  | int(11)      | auto_increment       | primary |
| ID_verifica         | int(11)      |
| ID_sezione          | int(11)      |
| testo               | varchar(512) |
| risultato           | tinyint(1)   | `//1 vero - 0 falso` |         |
| note                | varchar(64)  |
| punteggio           | tinyint(2)   |
| ordine              | tinyint(3)   |
| timestamp_modifica  | timestamp    |
| timestamp_creazione | timestamp    |

### verifica_rispostamultipla <span style="color:green">(implementata)</span>

| name                | type         | altro                | indice  |
|---------------------|--------------|----------------------|---------|
| ID                  | int(11)      | auto_increment       | primary |
| ID_verifica         | int(11)      |
| ID_sezione          | int(11)      |
| testo               | varchar(512) |
| note                | varchar(64)  |
| punteggio           | tinyint(2)   |
| ordine              | tinyint(3)   |
| timestamp_modifica  | timestamp    |
| timestamp_creazione | timestamp    |

### verifica_rispostamultipla_risposte <span style="color:green">(implementata)</span>

| name                | type         | altro                    | indice  |
|---------------------|--------------|--------------------------|---------|
| ID                  | int(11)      | auto_increment           | primary |
| ID_rispostamultipla | int(11)      |
| testo               | varchar(512) |                      
| corretto            | tinyint(1)   | `//1 corretto - 0 falso` | 
| punteggio           | tinyint(2)   |
| ordine              | tinyint(3)   |
| timestamp_modifica  | timestamp    |
| timestamp_creazione | timestamp    |

### verifica_rispostaaperta

| name        | type         | altro              | indice  |
|-------------|--------------|--------------------|---------|
| ID          | int(11)      | auto_increment     | primary |
| ID_verifica | int(11)      |
| ID_sezione  | int(11)      |
| testo       | varchar(512) |
| note        | varchar(64)  |
| punteggio   | tinyint(2)   |
| ordine      | tinyint(3)   |

### verifica_esercizio

| name        | type         | altro              | indice  |
|-------------|--------------|--------------------|---------|
| ID          | int(11)      | auto_increment     | primary |
| ID_verifica | int(11)      |
| ID_sezione  | int(11)      |
| testo       | varchar(512) |
| note        | varchar(64)  |
| punteggio   | tinyint(2)   |
| ordine      | tinyint(3)   |
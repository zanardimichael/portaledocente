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

## Possibile struttura:

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
<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Classe.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Professore.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Materia.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Sezione.php";
	
	class Verifica extends Base {
		
		static $sqlNames = [
			"id",
			"ID_professore",
			"ID_materia",
			"ID_classe",
			"ID_verifica",
			"titolo",
			"note",
			"ordine",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "verifica";
		static $classiEsercizi = [
			"Verofalso",
			"RispostaAperta",
			"RispostaMultipla",
			"Esercizio"
		];
		
		public int $id;
		public int $ID_professore;
		public Professore $professore;
		public int $ID_materia;
		public Materia $materia;
		public int $ID_classe;
		public Classe $classe;
		public ?int $ID_verifica;
		public Verifica $verifica;
		public string $titolo;
		public ?string $note;
		public int $ordine;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			parent::__construct($id, $sql);
			if(isset($this->ID_professore)){
				$this->professore = new Professore($this->ID_professore);
			}
			if(isset($this->ID_materia)){
				$this->materia = new Materia($this->ID_materia);
			}
			if(isset($this->ID_classe)){
				$this->classe = new Classe($this->ID_classe);
			}
			if(isset($this->ID_verifica)){
				$this->verifica = new Verifica($this->ID_verifica);
			}
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Verifica[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*", $professore = true): array {
			global $mysql;
			global $current_prof;
			$array = [];
			$result = $mysql->select(static::$sqlTable, $professore && $current_prof ? "ID_professore='".$current_prof->id."'": "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Verifica($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}
		
		static function create($data): int|string|false {
			global $mysql;
			if($mysql->insert(static::$sqlTable, $data))
				return $mysql->getInsertId();
			return false;
		}
		
		static function edit($id, $data): bool{
			global $mysql;
			return $mysql->update(static::$sqlTable, "ID='$id'", $data);
		}
		
		static function delete($id): bool{
			global $mysql;
			return $mysql->delete(static::$sqlTable, "ID='$id'");
		}
		
		public function getTimestampCreazioneTime() : int {
			return strtotime($this->timestamp_creazione);
		}
		
		public function getTimestampModificaTime() : int {
			return strtotime($this->timestamp_modifica);
		}
		
		public function getPunteggioVerifica(): int {
			$sezioni = $this->getSezioni();
			$punteggio = 0;
			foreach($sezioni as $sezione){
				$punteggio += $sezione->getPunteggioSezione();
			}
			return $punteggio;
		}
		
		public function getSottoverifiche(bool $object = true, $sql = "*"): array {
			global $mysql;
			$verifiche_array = array();
			
			$verifiche = $mysql->select(Verifica::$sqlTable, "ID_verifica='$this->id'", "id");
			
			while($row = mysqli_fetch_assoc($verifiche)){
				$verifiche_array[] = $object ? new Verifica($row["id"]) : $row["id"];
			}
			return $verifiche_array;
		}
		
		/**
		 * @param bool $object
		 * @return Sezione[]|int[]
		 */
		public function getSezioni(bool $object = true) : array {
			global $mysql;
			$array = [];
			
			$result = $mysql->select(Sezione::$sqlTable, "ID_verifica='$this->id' ORDER BY ordine ASC", "id");
			while($row = mysqli_fetch_assoc($result)){
				$array[] = $object ? new Sezione($row["id"]) : $row["id"];
			}
			return $array;
		}
		
		public function getLatex(): string{
			$latex = "\documentclass{article}

\usepackage[italian]{babel}
\usepackage{float}

% Set page size and margins
% Replace `letterpaper' with `a4paper' for UK/EU standard size
\usepackage[a4paper,top=2cm,bottom=2cm,left=3cm,right=3cm,marginparwidth=1.75cm]{geometry}

% Useful packages
\usepackage{amsmath}
\usepackage{graphicx}
\usepackage{fancyhdr}
\usepackage{enumitem,amssymb}
\usepackage[colorlinks=true, allcolors=blue]{hyperref}

\def\classe{".$this->classe->getNomeClasse()."}
\def\data{//2026}
\def\materia{".$this->materia->nome."}
\def\as{".$this->classe->anno."/".($this->classe->anno+1)."}

\begin{document}

\pagestyle{fancy}
\\fancyhead[LO]{Nome:\hspace{2.5cm}Cognome:\hspace{2.5cm}Data: \data}
\\fancyhead[RO]{Verifica \materia\:\classe\\\\ A.S. \as}

\\newlist{todolist}{itemize}{2}
\setlist[todolist]{label=$\square$}

";
			
			$sezioni = $this->getSezioni();
			foreach($sezioni as $ordine => $sezione){
				$testo_sezione = $sezione->renderLatex();
				$latex .= $testo_sezione;
			}
			$latex .= "\\end{document}";
			
			return $latex;
		}
		
		public function copyFromVerificaMadre(): void {
			$verifica_madre = $this->verifica;
			$sezioni = $verifica_madre->getSezioni();
			
			foreach($sezioni as $sezione){
				$domande = $sezione->getDomande();
				$nuova_sezione = Sezione::create([
					"ID_verifica" => $this->id,
					"titolo" => $sezione->titolo,
					"ordine" => $sezione->ordine
				]);
				
				foreach($domande["domande"] as $domanda){
					$class = get_class($domanda);
					print_r($domanda);
					$data = [
						"ID_verifica" => $this->id,
						"ID_sezione" => $nuova_sezione,
						"testo" => $domanda->testo,
						"ordine" => $domanda->ordine,
						"punteggio" => $domanda->punteggio,
						"note" => $domanda->note,
					];
					switch($class){
						case "Verofalso":
							$data["risultato"] = $domanda->risultato ? "1": "0";
							break;
						case "RispostaMultipla":
							$risposte = $domanda->getRisposte();
							foreach ($risposte as $risposta){
								RispostaMultipla::createRisposta([
									"ID_rispostamultipla" => $risposta->id,
									"testo" => $risposta["testo"],
									"corretto" => $risposta["corretto"] ? "1": "0",
									"punteggio" => $risposta["punteggio"],
									"ordine" => $risposta["ordine"],
								]);
							}
							break;
						case "Esercizio":
							$data["titolo"] = $domanda->titolo;
							break;
					}
					$class::create($data);
				}
			}
		}
	}
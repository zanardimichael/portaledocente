<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Base.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verifica.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Verofalso.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Rispostamultipla.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Rispostaaperta.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/class/Esercizio.php";
	
	class Sezione extends Base {
		
		static $sqlNames = [
			"id",
			"ID_verifica",
			"titolo",
			"ordine",
			"timestamp_modifica",
			"timestamp_creazione"
		];
		
		static $sqlTable = "verifica_sezione";
		
		public int $id;
		public ?int $ID_verifica;
		public Verifica $verifica;
		public string $titolo;
		public int $ordine;
		public string $timestamp_modifica;
		public string $timestamp_creazione;
		
		public function __construct(?int $id, $sql = "*"){
			parent::__construct($id, $sql);
			if(isset($this->ID_verifica)){
				$this->verifica = new Verifica($this->ID_verifica);
			}
		}
		
		/**
		 * @param bool $object
		 * @param string|array $sql
		 * @return Sezione[]
		 */
		static function getAll(bool $object = false, string|array $sql = "*", $professore = true): array {
			global $mysql;
			global $current_prof;
			$array = [];
			$result = $mysql->select(static::$sqlTable, "", "id");
			while($row = mysqli_fetch_assoc($result)){
				$object ? $array[] = new Sezione($row["id"], $sql) : $array[] = $row["id"];
			}
			return $array;
		}
		
		static function create($data): bool{
			global $mysql;
			return $mysql->insert(static::$sqlTable, $data);
		}
		
		static function edit($id, $data): bool{
			global $mysql;
			return $mysql->update(static::$sqlTable, "ID='$id'", $data);
		}
		
		static function delete($id): bool{
			global $mysql;
			$sezione = new Sezione($id, ["ordine", "ID_verifica"]);
			$delete = $mysql->delete(static::$sqlTable, "ID='$id'");
			Sezione::updateOrdineSezione($sezione->ordine, $sezione->ID_verifica);
			return $delete;
		}
		
		public function invertiOrdineConSuccessivo(): bool{
			global $mysql;
			
			$prossima_sezione_sql = $mysql->select(static::$sqlTable, "ordine='".($this->ordine+1)."' LIMIT 1", "id");
			if(!$prossima_sezione_sql) return false;
			$prossima_sezione_id = $prossima_sezione_sql->fetch_assoc()["id"];
			$prossima_sezione = new Sezione($prossima_sezione_id, ["ordine"]);
			return $prossima_sezione->setData("ordine", $this->ordine) &&
				$this->setData("ordine", $this->ordine+1);
		}
		
		/**
		 * @param int $ID_verifica
		 * @return int
		 */
		public static function getUltimoOrdineVerifica(int $ID_verifica): int{
			global $mysql;
			
			$ordine_sql = $mysql->select(static::$sqlTable, "ID_verifica='$ID_verifica' ORDER BY ordine DESC LIMIT 1", "ordine");
			if($ordine_sql->num_rows == 0){
				return 0;
			}
			return $ordine_sql->fetch_assoc()["ordine"];
		}
		
		public static function getUltimoOrdineSezione(int $ID_sezione) {
			global $mysql;
			
			$mysql->escape($ID_sezione);
			
			$ordine_sql = $mysql->query("SELECT COALESCE(MAX(ordine), 0) AS max_ordine
				FROM (
				    SELECT ordine FROM verifica_verofalso WHERE ID_sezione = '$ID_sezione'
				    UNION ALL
				    SELECT ordine FROM verifica_rispostamultipla WHERE ID_sezione = '$ID_sezione'
				    UNION ALL
				    SELECT ordine FROM verifica_rispostaaperta WHERE ID_sezione = '$ID_sezione'
				    UNION ALL
				    SELECT ordine FROM verifica_esercizio WHERE ID_sezione = '$ID_sezione'
				) AS tutte_le_domande;");
			return $ordine_sql->fetch_assoc()["max_ordine"];
		}
		
		public function getTimestampCreazioneTime() : int {
			return strtotime($this->timestamp_creazione);
		}
		
		public function getTimestampModificaTime() : int {
			return strtotime($this->timestamp_modifica);
		}
		
		public function getDomande(): array {
			global $mysql;
			
			$array = [];
			$array["punteggio"] = 0;
			$array["domande"] = [];
			$verofalso = $mysql->select(Verofalso::$sqlTable, "ID_sezione='$this->id' ORDER BY ordine ASC", ["id", "ordine"]);
			while($row = mysqli_fetch_assoc($verofalso)){
				$verofalso_object = new Verofalso($row["id"]);
				$array["domande"][$row["ordine"]] = $verofalso_object;
				$array["punteggio"] += $verofalso_object->punteggio;
			}
			
			$rispostamultipla = $mysql->select(Rispostamultipla::$sqlTable, "ID_sezione='$this->id' ORDER BY ordine ASC", ["id", "ordine"]);
			while($row = mysqli_fetch_assoc($rispostamultipla)){
				$rispostamultipla_object = new Rispostamultipla($row["id"]);
				$array["domande"][$row["ordine"]] = $rispostamultipla_object;
				$array["punteggio"] += $rispostamultipla_object->punteggio;
			}
			
			$rispostaaperta = $mysql->select(Rispostaaperta::$sqlTable, "ID_sezione='$this->id' ORDER BY ordine ASC", ["id", "ordine"]);
			while($row = mysqli_fetch_assoc($rispostaaperta)){
				$rispostaaperta_object = new Rispostaaperta($row["id"]);
				$array["domande"][$row["ordine"]] = $rispostaaperta_object;
				$array["punteggio"] += $rispostaaperta_object->punteggio;
			}
			
			$esercizio = $mysql->select(Esercizio::$sqlTable, "ID_sezione='$this->id' ORDER BY ordine ASC", ["id", "ordine"]);
			while($row = mysqli_fetch_assoc($esercizio)){
				$esercizio_object = new Esercizio($row["id"]);
				$array["domande"][$row["ordine"]] = $esercizio_object;
				$array["punteggio"] += $esercizio_object->punteggio;
			}
			
			return $array;
		}
		
		static function updateOrdineEsercizi(int $ordineRimosso, $ID_sezione): void {
			global $mysql;
			
			$mysql->update(Verofalso::$sqlTable, "ordine > $ordineRimosso AND ID_sezione='$ID_sezione'", "ordine=ordine-1");
			$mysql->update(Rispostamultipla::$sqlTable, "ordine > $ordineRimosso AND ID_sezione='$ID_sezione'", "ordine=ordine-1");
			$mysql->update(Rispostaaperta::$sqlTable, "ordine > $ordineRimosso AND ID_sezione='$ID_sezione'", "ordine=ordine-1");
			$mysql->update(Esercizio::$sqlTable, "ordine > $ordineRimosso AND ID_sezione='$ID_sezione'", "ordine=ordine-1");
		}
		
		static function updateOrdineSezione(int $ordineRimosso, $ID_verifica): void {
			global $mysql;
			
			$mysql->update(Sezione::$sqlTable, "ordine > $ordineRimosso AND ID_verifica='$ID_verifica'", "ordine=ordine-1");
		}
		
		public function invertiOrdineEsercizioConSuccessivo($ordine_esercizio): bool {
			global $mysql;
			$ordine_successivo = $ordine_esercizio+1;
			
			$verofalso = $mysql->update(Verofalso::$sqlTable, "ordine IN ('$ordine_esercizio', '$ordine_successivo') AND ID_sezione='$this->id'",
				"ordine = (case when ordine = '$ordine_esercizio' then '$ordine_successivo' when ordine = '$ordine_successivo' then '$ordine_esercizio' end)", false);
			$rispostamultipla = $mysql->update(Rispostamultipla::$sqlTable, "ordine IN ('$ordine_esercizio', '$ordine_successivo') AND ID_sezione='$this->id'",
				"ordine = (case when ordine = '$ordine_esercizio' then '$ordine_successivo' when ordine = '$ordine_successivo' then '$ordine_esercizio' end)", false);
			$rispostaaperta = $mysql->update(Rispostaaperta::$sqlTable, "ordine IN ('$ordine_esercizio', '$ordine_successivo') AND ID_sezione='$this->id'",
				"ordine = (case when ordine = '$ordine_esercizio' then '$ordine_successivo' when ordine = '$ordine_successivo' then '$ordine_esercizio' end)", false);
			$esercizio = $mysql->update(Esercizio::$sqlTable, "ordine IN ('$ordine_esercizio', '$ordine_successivo') AND ID_sezione='$this->id'",
				"ordine = (case when ordine = '$ordine_esercizio' then '$ordine_successivo' when ordine = '$ordine_successivo' then '$ordine_esercizio' end)", false);
			
			return true;
		}
		
		public function renderLatex(): string {
			$domande = $this->getDomande();
			
			$testo_sezione = "\n\section*{".$this->titolo."}\n";
			foreach ($domande["domande"] as $domanda){
				$testo_sezione .= $domanda->renderLatex();
			}
			
			$testo_sezione .= "\n\n\begin{flushright}\\textbf{Punteggio:} \underline{\hspace{1cm}}/".$domande["punteggio"]."\\end{flushright}\n";
			
			return $testo_sezione;
		}
	}
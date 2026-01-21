<?php
	
	abstract class UnsafeReasons {
		public const string Redirect = "Redirect";
		public const string NotFound = "NotFound";
		public const string Unauthorized = "Unauthorized";
	}
	
	class PageHandler {
		
		public bool $redirect = false;
		public string $redirect_url = "";
		
		public string $current_page = "";
		public array $current_page_array = [];
		public bool $subpage = false;
		
		public string $title;
		public Message $message;
		
		public string $previous_page = "";
		public array $unsafeReasons = [
			UnsafeReasons::Redirect => false,
			UnsafeReasons::NotFound => false,
			UnsafeReasons::Unauthorized => false,
		];
		
		public array $javascriptVariables = [];
		public array $globalVariables = [];
		
		public function __construct($current_page) {
			global $pages;
			$this->current_page = $current_page;
			$this->message = new Message();
			
			if(isset($pages[$this->current_page])) {
				
				$this->current_page_array = explode("/", $current_page);
				if (count($this->current_page_array) > 1) {
					$this->subpage = true;
				}
				$previous_page_exploded = explode("/", $current_page);
				array_pop($previous_page_exploded);
				$this->previous_page = implode($previous_page_exploded);
				
				$this->title = $pages[$this->current_page]["title"];
			}else{
				$this->setRedirect("/");
				$this->setUnsafe(UnsafeReasons::NotFound);
			}
		}
		
		public function setRedirect(string $redirect_url): PageHandler {
			$this->redirect = true;
			$this->redirect_url = $redirect_url;
			$this->unsafeReasons[UnsafeReasons::Redirect] = true;
			return $this;
		}
		
		public function unsetRedirect(): PageHandler {
			$this->redirect = false;
			$this->redirect_url = "";
			$this->unsafeReasons[UnsafeReasons::Redirect] = false;
			return $this;
		}
		
		public function isSafeToProceed(): bool {
			return !array_any($this->unsafeReasons, fn($reason) => $reason);
		}
		
		public function isBackButtonEnabled(): bool {
			global $pages;
			return $pages[$this->current_page]["back_button"] ?? false;
		}
		
		public function getPagesBreadcrumb(): array {
			global $pages;
			$array = [];
			$incrementing = "";
			for($i = 0; $i < count($this->current_page_array); $i++) {
				$page = $this->current_page_array[$i];
				$incrementing .= $i == 0 ? $page : "/" . $page;
				$array[] = $incrementing;
			}
			return $array;
		}
		
		public function getPageScripts(): array|false {
			global $pages;
			return $pages[$this->current_page]["script_js"] ?? false;
		}
		
		public function renderRedirect(): string {
			return ($this->redirect) ? "
				<script>
					window.location = '" . $this->redirect_url . "';
				</script>
			" : "";
		}
		
		public function setUnsafe(string $unsafeReason): void {
			$this->unsafeReasons[$unsafeReason] = true;
		}
		
		public function setJavascriptVariable(string $key, mixed $value): void {
			$this->javascriptVariables[$key] = $value;
		}
		
		public function setGlobalVariable(string $key, mixed $value): void {
			$this->globalVariables[$key] = $value;
		}
		
		public function getGlobalVariable(string $key) {
			return $this->globalVariables[$key] ?? null;
		}
		
		public function getRequestMethod(): string {
			return $_SERVER["REQUEST_METHOD"];
		}
		
		public function renderJavascriptVariables(): string {
			$script = "<script type='text/javascript'>\n";
			foreach($this->javascriptVariables as $variableName => $variableValue) {
				if(gettype($variableValue) == "string") {
					$variableValue = "'" . $variableValue . "'";
				}
				if(gettype($variableValue) == "array") {
					$variableValue = json_encode($variableValue);
				}
				$script .= "let $variableName = $variableValue;\n";
			}
			$script .= "</script>\n";
			return $script;
		}
	}
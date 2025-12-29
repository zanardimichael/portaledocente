<?php
	
	class Message {
		
		public string $message;
		public array $style;
		public int $duration = 3000;
		public bool $show = false;
		
		public function setMessageType(MessageType $type): Message {
			switch ($type){
				case MessageType::Success:
					$this->style["background"] = "var(--bs-success)";
					break;
				case MessageType::Error:
					$this->style["background"] = "var(--bs-danger)";
					break;
				case MessageType::Warning:
					$this->style["background"] = "var(--bs-warning)";
					break;
				case MessageType::Info:
					$this->style["background"] = "var(--bs-info)";
					break;
			}
			return $this;
		}
		
		public function setMessage(string $message): Message {
			$this->message = $message;
			return $this;
		}
		
		public function setDuration(int $time): Message {
			$this->duration = $time;
			return $this;
		}
		
		public function show(): Message {
			$this->show = true;
			return $this;
		}
		
		public function hide(): Message {
			$this->show = false;
			return $this;
		}
		
		public function render(bool $script): string {
			if($this->show) {
				return ($script ? "<script>" : "").'
						Toastify({
							text: "'.$this->message.'",
							duration: 3000,
							style: '.json_encode($this->style).',
						}).showToast();
					    if ( window.history.replaceState ) {
					        window.history.replaceState( null, null, window.location.href );
					    }
					'.($script ? "</script>" : "");
			}
			return "";
		}
	}
	
	
	enum MessageType {
		case Success;
		case Info;
		case Warning;
		case Error;
	}
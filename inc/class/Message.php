<?php
	
	class Message {
		
		public string $messaggio;
		public array $style;
		
		public function __construct(string $messaggio, array $style) {
			$this->messaggio = $messaggio;
			$this->style = $style;
		}
		
		public function setMessageType(MessageType $type): void {
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
		}
	}
	
	
	enum MessageType {
		case Success;
		case Info;
		case Warning;
		case Error;
	}
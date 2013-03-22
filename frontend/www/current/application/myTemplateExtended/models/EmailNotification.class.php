<?php
/*
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
?>
<?php
/**
 * For test only, this notification class send e-mails instead of calling or sending texts to people.
 * E-mail are sent in HTML AND Text format. The web client display the one he support.
 * @author David Da Silva
 */
class EmailNotification {

	public /*String*/ $mail;
	public /*String*/ $subject;
	public /*String*/ $message;
	public /*String*/ $header;
	
	
	
	public function __construct($receiver,$subject,$text){
		
		$exp_name = "myTemplateExtended E-mail Monkey";
		$exp_mail = "mymed.subscribe@gmail.com";
		$ret_name = "Do Not Reply";
		$ret_mail = "infomymed@gmail.com";
		
		
		/*
		 * Destination e-mail
		 */
		$this->mail = $receiver;
		
		/*
		 * TXT and HTML message
		 */
		$txt_message	= $text;
		$html_message	= "<html><head></head><body>".$text."</body></html>";
		
		// Creation of the boundary
		$boundary = "-----=".md5(rand());
		
		/*
		* E-mail header
		*/
		$this->header		 = "From: \"{$exp_name}\"<{$exp_mail}>\r\n";
		$this->header		.= "Reply-to: \"{$ret_name}\" <{$ret_mail}>\r\n";
		$this->header		.= "MIME-Version: 1.0\r\n";
		$this->header		.= "X-Priority: 1\r\n";	// Max priority
		$this->header		.= "Content-Type: multipart/alternative;\r\n boundary=\"{$boundary}\"\r\n";
		
		/*
		 * E-mail subject
		 * 
		 */
		$this->subject = $subject;
		
		/*
		 * E-mail message
		 */
		// TXT version
		$this->message	 = "\r\n--{$boundary}\r\n"; //open boundary
		$this->message	.= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
		$this->message	.= "Content-Transfer-Encoding: 8bit\r\n";
		$this->message	.= "\r\n{$txt_message}\r\n";
		
		$this->message	.= "\r\n--{$boundary}\r\n"; // open boundary
		
		//HTML version
		
		$this->message	.= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
		$this->message	.= "Content-Transfer-Encoding: 8bit\r\n";
		$this->message	.= "\r\n{$html_message}\r\n";
		
		$this->message	.= "\r\n--{$boundary}--\r\n"; // close boundary
		$this->message	.= "\r\n--{$boundary}--\r\n"; // close boundary
		
		
	}
	
	
	public function send(){
		
		return mail($this->mail, $this->subject, $this->message, $this->header);

	}
	
	
	
}
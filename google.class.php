<?php
	include_once('parser.class.php');

	class Google extends Parser
	{
		private $search;
		private $url;
		private $responce;
		private $content;

		public function __construct($_search)
		{
			$this->search = $_search;
			$this->url = 'http://google.com/search?q=';
			$this->responce = null;
			$this->content = array();
		}

		public function _parseResponce()
		{
			$dom = new DOMDocument();
			$dom->loadHTML($this->responce);
			$iterator = 0;
			foreach($dom->getElementsByTagName('cite') as $link) {
				if ($iterator >= AMOUNT_OF_RESULTS) {
					break;
				}
				$iterator++;
				$this->content[] = $this->_generateCorrectLinks($link->textContent);
			}
		}

		public function _curlRequest()
	    {
			$curl = curl_init();
			$search_string = $this->url . urlencode($this->search);
			curl_setopt($curl, CURLOPT_URL, $search_string);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$this->responce = curl_exec($curl);
			$error = curl_error($curl);
			curl_close($curl);

			if (!empty($error)) {
	            return $error;
	        }
	        return $this->responce;
	    }

	    public function _execute()
	    {
	    	$this->_curlRequest();
	    	if (!empty($this->responce)) {
	    		$this->_parseResponce();
	    	}
	    	return array($this->content, implode(', ', $this->errors));
	    }

	    private function _generateCorrectLinks($link)
	    {
	    	if (!preg_match("%http%", $link)) {
	    		$link = "http://" . $link;
	    	}
	    	return $link;

	    }


	}

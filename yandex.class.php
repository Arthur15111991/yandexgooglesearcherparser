<?php
	include_once('parser.class.php');

	class Yandex extends Parser
	{
		private $search;
		private $url;
		private $responce;
		private $content;
		private $errors;

		public function __construct($_search)
		{
			$this->search = $_search;
			$this->url = 'http://yandex.ru/search/';
			$this->responce = null;
			$this->content = null;
			$this->errors = array();
		}	

		public function _parseResponce()
		{
			//$this->responce = file_get_contents('temp_file.html'); //TEST MODE
			$pattern = "/(link_cropped_no\" target=\"_blank\" href=\")(.{0,255})(\" )/i";
			preg_match_all($pattern, $this->responce, $out);
			$this->content = (!empty($out['2'])) ? array_splice($out['2'], false, AMOUNT_OF_RESULTS) : array();
		}

		public function _curlRequest()
	    {
			$curl = curl_init();
			$search_string = 'http://yandex.ru/search?text=' . urlencode($this->search);
			curl_setopt($curl, CURLOPT_URL, $search_string);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$this->responce = curl_exec($curl);
			$errors = curl_error($curl);
			curl_close($curl);

			if (!empty($errors)) {
	            $this->errors[] = $errors;
	        }
	        return $this->responce;
	    }

	    public function _execute()
	    {
	    	$this->_curlRequest();
	    	if (!empty($this->responce)) {
	    		if ($this->_issetSearchResult()) {
	    			$this->_parseResponce();
	    		}
	    		return array($this->content, implode(', ', $this->errors));
	    	} else {
	    		$this->errors[] = 'No results';
	    		return array($this->content, implode(', ', $this->errors));
	    	}
	    }

	    public function _issetSearchResult()
	    {
	    	$pattern = "(action=\"/checkcaptcha)";
	    	if (preg_match($pattern, $this->responce)) {
	    		$this->errors[] = 'No results';
	    		return false;
	    	}
	    	return true;
	    }


	}
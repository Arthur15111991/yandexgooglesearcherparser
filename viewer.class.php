<?php

	class Viewer
	{
		public static function view($view, $params = array())
		{
			$file = 'views/'. $view . '.php';
			include $file;			
		}
	}
<style>
<?php include 'styles/style.css'; ?>
</style>
<?php
    include_once('viewer.class.php');
    include_once('config.php');

	Viewer::view('form');

	if (!empty($_POST['search_text'])) {
		$search_text = $_POST['search_text'];
		$content = "";
		if ($_POST['searcher'] == 'G') {
			include_once('google.class.php');
			$searcher = new Google($search_text);
		} elseif ($_POST['searcher'] == 'Y') {
			include_once('yandex.class.php');
			$searcher = new Yandex($search_text);
		}

		list($links, $errors) = $searcher->_execute();

		if (empty($errors)) {
			foreach ($links as $key => $link) {
				Viewer::view('result_list', $link);
			}
		} else {
			Viewer::view('errors_list', $errors);
		}
		
	}


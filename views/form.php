<div class="wrapper">
	<form id="request" method="POST" name="request">
		<input name="search_text" class="search_text" maxlength="20" placeholder="Введите ваш запрос">
		<div class="searcher-wrapper">
			<span>Выберете поисковик: </span>
			<select class="searcher" name="searcher">
				<option value="G">Google</option>
				<option value="Y">Yandex</option>
			</select>
		</div>
		<button id="submit_form" class="request-button" type="submit" form="request">Отправить запрос</button>
	</form>
</div>
<h1>Туры соревнований в чемпионате</h1>
	<center>
		<h2>Количество туров в чемпионате: {$championship_item.ch_tours}.</h2>
		<br>
		{if $championship_item.ch_chc_id == 2}
		<form method="post" onsubmit="if (!confirm('Вы уверены?')) return false">
			<input type="hidden" name="ch_id" value="{$championship_item.ch_id}">
			<input type="submit" name="add_new_tour" id="submitsave" value="Добавить еще один тур" style="width: 200px;">
		</form>
		<br>
		{/if}
	</center>
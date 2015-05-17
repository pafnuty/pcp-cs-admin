<div class="content">
	<div class="content">
		<div class="col col-mb-12">
		<div class="content">
			<div class="col col-mb-12 ta-right">
				<form method="GET">
					{if $.get.filter}
						{foreach $.get.filter as $key => $value}
							<input type="hidden" name="filter[{$key}]" value="{$value}">
						{/foreach}
					{/if}
					<input type="hidden" name="page" value="logs">
					<input type="text" name="search" class="input" placeholder="Поиск по логам" value="{$searchInputText}">
				</form>
			</div>
		</div>
			<div class="list" data-list-id="logs-list">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Дата</th>
							<th scope="col">Ключ</th>
							<th scope="col">Домен</th>
							<th scope="col">IP</th>
							<th scope="col">Имя сервера</th>
						</tr>
					</thead>
					<tbody>
						{foreach $arResult.list as $key => $item}
							{set $eventData = $item.event_data|json_decode}
							<tr>
								<th scope="row">{$item.date|date:"d.m.Y H:i:s"}</th>
								<td data-title="Ключ"><a href="{$homeUrl}/?filter[l_key]={$eventData->key}">{$eventData->key}</a></td>
								<td data-title="Домен">{$eventData->domain}</td>
								<td data-title="IP">{$eventData->server_ip}</td>
								<td data-title="Имя сервера">{$eventData->server_hostname}</td>				
							</tr>							
						{/foreach}
					</tbody>
				</table>
				{$arResult.pages}
			</div> <!-- .list -->
		</div> <!-- .col col-mb-12 -->
	</div>
</div>

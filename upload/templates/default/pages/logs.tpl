<div class="content">
	<div class="content">
		<div class="col col-mb-12">
			<div class="list" data-list-id="logs-list">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Дата</th>
							<th scope="col">Ключ</th>
							<th scope="col">Домен и IP</th>
							<th scope="col">Корень сайта</th>
							<th scope="col">Имя и IP сервера</th>
						</tr>
					</thead>
					<tbody>
						{foreach $arResult.list as $key => $item}
							{set $eventData = $item.event_data|json_decode}
							<tr>
								<th scope="row">{$item.date|date:"d.m.Y H:i:s"}</th>
								<td data-title="Ключ"><a href="{$homeUrl}/?filter[l_key]={$eventData->key}">{$eventData->key}</a></td>
								<td data-title="Домен/IP">{$eventData->domain} / {$eventData->ip}</td>
								<td data-title="Корень сайта">{$eventData->directory}</td>
								<td data-title="Имя и IP сервера">{$eventData->server_hostname} / {$eventData->server_ip}</td>				
							</tr>							
						{/foreach}
					</tbody>
				</table>
				{$arResult.pages}
			</div> <!-- .list -->
		</div> <!-- .col col-mb-12 -->
	</div>
</div>

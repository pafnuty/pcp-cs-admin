{* Сюда пишем всё, что должно выводиться в контенте *}

<div class="content">
	<div class="content">
		<div class="col col-mb-12">
			<p>
				<span class="btn mfp-open-ajax" data-mfp-src="/admin/ajax/add.php?do=newlicence"><i class="fa fa-plus"></i> Добавить лицензию</span>
			</p>
			<div class="list" data-list-id="licence-list">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Юзер</th>
							<th scope="col">Ключ активации</th>
							<th scope="col">Домен</th>
							<th scope="col">Статус</th>
							<th scope="col">Дата начала</th>
							<th scope="col">Дата окончания</th>
						</tr>
					</thead>
					<tbody>
						{foreach $arResult.list as $key => $item}
							{set $expires = $item.l_started + ($item.l_expires*60*60*24)}
							{set $lastWeek = time() - (7*60*60*24)}
							<tr>
								<th scope="row">{$item.id}</th>
								<td data-title="Юзер">{$item.user_name}</td>
								<td data-title="Ключ активации">{$item.l_key}</td>
								<td data-title="Домен">
									{$item.l_domain}
								</td>
								<td data-title="Статус">
									{switch $item.l_status}
										{case '0'}
											<span class="text-orange">Ожидает активации</span>
										{case '1'}
											Активна
											<span class="btn btn-gray btn-mini">Активировать</span>
										{case '2'}
											<span class="text-red">Истек срок</span>
											<span class="btn btn-gray btn-mini">Напомнить клиенту</span>
										{case '3'}
											Ожидает повторной активации
										{case '4'}
											Приостановлена
											<span class="btn btn-gray btn-mini">Активировать</span>
									{/switch}
								</td>
								<td data-title="Дата начала">{$item.l_started|date:"d.m.Y H:i:s"}</td>
								<td data-title="Дата окончания">
									{set $expireClass}
										{if $expires <= time()}
											text-red
										{elseif $expires <= $lastWeek}
											text-orange
										{else}
											text-muted
										{/if}
									{/set}
									<span class="{$expireClass}">{$expires|date:"d.m.Y H:i:s"}</span>
								</td>
							</tr>
							
						{/foreach}
					</tbody>
				</table>
				{$arResult.pages}
			</div> <!-- .list -->
		</div> <!-- .col col-mb-12 -->
	</div>
</div>

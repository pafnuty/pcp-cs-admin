{* Сюда пишем всё, что должно выводиться в контенте *}

<div class="content">
	<div class="content">
		<div class="col col-mb-12">
			<p>
				<span class="btn mfp-open-ajax" data-mfp-src="/admin/ajax/add.php?page=addkey&ajax=Y"><i class="fa fa-plus"></i> Добавить новый ключ</span>
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
							{if $item.l_expires !== 'never'}
								{set $expires = $item.l_expires}
								{set $lastWeek = time() - (7*60*60*24)}
								{set $expireClass}
									{if $expires <= time()}
										text-red
									{elseif $expires <= $lastWeek}
										text-orange
									{else}
										text-muted
									{/if}
								{/set}
								{set $expiresFormated}
									{$expires|date:"d.m.Y H:i:s"}
								{/set} 
							{else}
								{set $expiresFormated = 'Никогда'}
								{set $expireClass = 'text-muted'}
							{/if}
							<tr>
								<th scope="row">{$item.id}</th>
								<td data-title="Юзер">
									{$item.user_name}
									{set $userInfo = $item.id|getUserInfo}
									{if $userInfo.email}
										<br><a href="mailto:{$userInfo.email}">{$userInfo.email}</a>										
									{/if}
								</td>
								<td data-title="Ключ активации">
									<input type="text" class="input onfocus-select fz14 mb0" readonly value="{$item.l_key}">
									<a href="{$homeUrl}/?page=methods&filter[id]={$item.l_method_id}" class="btn btn-small btn-gray" title="метод проверки"><i class="fa fa-filter"></i> {$item.l_method_id}</a>
								</td>
								<td data-title="Домен">
									{$item.l_domain}
								</td>
								<td data-title="Статус">
									<select class="styler status-change ta-left" name="status" data-key="{$item.l_key}">
										<option value="0" {if $item.l_status == '0'}selected{/if}>Ожидает активации</option>
										<option value="1" {if $item.l_status == '1'}selected{/if}>Активна</option>
										<option value="2" {if $item.l_status == '2'}selected{/if}>Истек срок</option>
										<option value="3" {if $item.l_status == '3'}selected{/if}>Ожидает повторной активации</option>
										<option value="4" {if $item.l_status == '4'}selected{/if}>Приостановлена</option>
									</select>
								</td>
								<td data-title="Дата начала">{$item.l_started|date:"d.m.Y H:i:s"}</td>
								<td data-title="Дата окончания">
									<span class="{$expireClass}">{$expiresFormated}</span>
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

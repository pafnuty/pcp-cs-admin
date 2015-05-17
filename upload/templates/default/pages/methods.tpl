<div class="content">
	<div class="content">
		<div class="col col-mb-12">
			<p>
				<span class="btn mfp-open-ajax" data-mfp-src="/admin/ajax/add.php?page=addmethod&ajax=Y"><i class="fa fa-plus"></i> Добавить новый метод</span>
			</p>
			<div class="list" data-list-id="methods-list">
				<table class="responsive-table">
					<thead>
						<tr>
							{if $.request['order'] == 'desc'}
								{set $order = 'asc'}
							{else}
								{set $order = 'desc'}
							{/if}
							<th scope="col" class="order-col {selected request="orderby" value="id" class="selected"} {$order}" >
								<span 
									class="sort-span"
									data-sort='{
											"orderby": "id", "order": "{$order}"
									}'>
									ID
								</span>
							</th>
							<th scope="col" class="order-col {selected request="orderby" value="name" class="selected"} {$order}"> 
								<span
									class="sort-span"
									data-sort='{
											"orderby": "name", "order": "{$order}"
									}'>
									Имя
								</span>
							</th>
							<th scope="col">
								Секретный ключ
							</th>
							<th scope="col" class="order-col {selected request="orderby" value="check_period" class="selected"} {$order}">
								<span 
									class="sort-span"
									data-sort='{
											"orderby": "check_period", "order": "{$order}"
									}'>
									Период проверки
								</span>
							</th>
							<th scope="col" class="order-col {selected request="orderby" value="enforce" class="selected"} {$order}">
								<span 
									class="sort-span"
									data-sort='{
											"orderby": "enforce", "order": "{$order}"
									}'>
									Что проверять
								</span>
							</th>
							<th scope="col">&nbsp;</th>
							<th scope="col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						{foreach $arResult.list as $key => $item}
							<tr data-method-id="{$item.id}">
								<th scope="row">{$item.id}</th>
								<td data-title="Имя">{$item.name}</td>
								<td data-title="Секретный ключ">{$item.secret_key}</td>
								<td data-title="Период проверки">{$item.check_period}</td>
								<td data-title="Что проверять">{$item.enforce}</td>
								<td data-title=""><span class="btn btn-gray btn-small mfp-open-ajax" data-mfp-src="/admin/ajax/edit.php?page=editmethod&ajax=Y&id={$item.id}">Изменить</span></td>
								<td data-title=""><a href="{$homeUrl}/?filter[l_method_id]={$item.id}" class="btn btn-small"><i class="fa fa-filter"></i> ключи</a></td>
							</tr>							
						{/foreach}
					</tbody>
				</table>
				{$arResult.pages}
			</div> <!-- .list -->
		</div> <!-- .col col-mb-12 -->
	</div>
</div>

<div class="content">
	<div class="content">
		<div class="col col-mb-12">
			<p>
				<span class="btn mfp-open-ajax" data-mfp-src="/admin/ajax/add.php?page=addmethod&ajax=Y&do=newmethod"><i class="fa fa-plus"></i> Добавить новый метод</span>
			</p>
			<div class="list" data-list-id="methods-list">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Имя</th>
							<th scope="col">Секретный ключ</th>
							<th scope="col">Период проверки</th>
							<th scope="col">Что проверять</th>
							<th scope="col">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						{foreach $arResult.list as $key => $item}
							<tr data-method-id="{$item.id}">
								<th scope="row">{$item.id}</th>
								<td data-title="Имя">{$item.name }</td>
								<td data-title="Секретный ключ">{$item.secret_key}</td>
								<td data-title="Период проверки">{$item.check_period}</td>
								<td data-title="Что проверять">{$item.enforce}</td>
								<td data-title=""><span class="btn btn-gray btn-small btn-ajax-edit" data-do="editmethod" data-id="{$item.id}">Изменить</span></td>
							</tr>							
						{/foreach}
					</tbody>
				</table>
				{$arResult.pages}
			</div> <!-- .list -->
		</div> <!-- .col col-mb-12 -->
	</div>
</div>

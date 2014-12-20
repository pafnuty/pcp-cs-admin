{if $add == 'y'}
	<div class="mfp-close cn-modal-close">&times;</div>
	<div class="cn-modal-header">
		<div class="h2">{$title}</div>
	</div> <!-- .cn-modal-header -->
	<div class="cn-modal-content clearfix">
		<p><input class="input input-block onfocus-select" type="text" readonly value="{$addResult.key}" autofocus></p>
		<p>Дата начала действия: {$addResult.started|date:"d.m.Y H:i:s"}</p>
		{set $expires}
			{if $addResult.expires == 'never'}
				<b>никогда</b>
			{else}
				{$addResult.expires|date:"d.m.Y H:i:s"}
			{/if}
		{/set}
		<p>Дата окончания действия: {$expires}</p>
		<hr>
		<span class="btn btn-gray">Отправить на email</span>	
	</div>	
{else}
	<form action="/admin/ajax/add.php?page=addkey&ajax=Y" data-ajax-submit class="cn-modal col-8 col-dt-6">
		<input type="hidden" name="add" value="y">
		<div class="cn-modal">
			<div class="mfp-close cn-modal-close">&times;</div>
			<div class="cn-modal-header">
				<div class="h2">{$title}</div>
			</div> <!-- .cn-modal-header -->
			<div class="cn-modal-content clearfix">

				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Название ключа <small class="text-muted">(необязательно)</small>
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="text" name="l_name" value="">
					</div>
				</div>
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Пользователь <small class="text-muted">(необязательно)</small>
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<div class="content" style="max-width: 380px;">
							<div class="col col-mb-12 col-3">
								<input class="input input-block" type="number" name="user_id" id="user_id" value="" placeholder="ID">
							</div>
							<div class="col col-mb-12 col-9">
								<input class="input input-block" type="text" name="user_name" id="user_name" value="" placeholder="Логин">
							</div>
						</div>
					</div>
				</div>

				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Срок действия
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="date" name="expires" value="" id="expires">
						<br><input class="checkbox" type="checkbox" name="never" id="never" value="y"><label for="never"><span></span> вечный ключ</label>
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Метод проверки
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<select class="styler" name="method" id="method">
							{foreach $arResult.methods as $method}
								<option value="{$method.id}">{$method.name}</option>
							{/foreach}
						</select>
					</div>
				</div>

				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Использование на поддоменах
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<select class="styler" name="domain_wildcard" id="domain_wildcard">
							<option value="0" selected>запрещено</option>
							<option value="1">разрешено на разных подоменах</option>
							<option value="2">разрешено на разных поддоменах включая разные доменные зоны</option>
							<option value="3">разрешено на разных доменных зонах основного домена</option>
						</select>
					</div>
				</div>
			
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						&nbsp;
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<button class="btn ladda-button" type="submit" data-style="expand-left"><span class="ladda-label">Создать ключ</span></button>
					</div>
				</div>
			</div> <!-- .cn-modal-content -->
		</div>
	</form>
{/if}

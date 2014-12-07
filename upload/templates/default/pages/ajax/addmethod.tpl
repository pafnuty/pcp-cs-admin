{if $add == 'y'}
	<div class="mfp-close cn-modal-close">&times;</div>
	<div class="cn-modal-header">
		<div class="h2">{$title}</div>
	</div> <!-- .cn-modal-header -->
	<div class="cn-modal-content clearfix">
		Метод №{$addResult.id} <b>{$addResult.name}</b> успешно создан!
		<p>Секретный ключ: {$addResult.secret_key}</p>
		<p>Период проверки: {$addResult.check_period}</p>
		<p>Проверяемые данные: {$addResult.enforce}</p>			
	</div>	
{else}
	<form action="/admin/ajax/add.php?page=addmethod&ajax=Y&do=newmethod" data-ajax-submit class="cn-modal col-6">
		<input type="hidden" name="add" value="y">
		<div class="cn-modal">
			<div class="mfp-close cn-modal-close">&times;</div>
			<div class="cn-modal-header">
				<div class="h2">{$title}</div>
			</div> <!-- .cn-modal-header -->
			<div class="cn-modal-content clearfix">

				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Название
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="text" name="name" value="">
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Секретный ключ
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="text" name="secret_key" value="">
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Период проверки (дни)
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="number" name="check_period" value="">
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Что проверять
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<select class="styler" name="enforce[]" id="enforce" multiple>
							<option value="domain">домен</option>
							<option value="user_id">ID пользователя</option>
							<option value="user_name">имя пользователя</option>
							<option value="ip">ip сервера</option>
							<option value="server_hostname">хост сервера</option>
						</select>
					</div>
				</div>
			
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						&nbsp;
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<button class="btn ladda-button" type="submit" data-style="expand-left"><span class="ladda-label">Добавить метод</span></button>
					</div>
				</div>
			</div> <!-- .cn-modal-content -->
		</div>
	</form>
{/if}

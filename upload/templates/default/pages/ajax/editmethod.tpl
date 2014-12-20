{if $edit == 'y'}
	<div class="mfp-close cn-modal-close">&times;</div>
	<div class="cn-modal-header">
		<div class="h2">{$title}</div>
	</div> <!-- .cn-modal-header -->
	<div class="cn-modal-content clearfix">
		Метод №{$arResult.id} <b>{$arResult.name}</b> отредактирован.			
	</div>	
{else}
	<form action="/admin/ajax/edit.php?page=editmethod&ajax=Y&id={$arResult.id}" data-ajax-submit class="cn-modal col-6">
		<input type="hidden" name="edit" value="y">
		<div class="cn-modal">
			<div class="mfp-close cn-modal-close">&times;</div>
			<div class="cn-modal-header">
				<div class="h2">{$title}: "{$arResult.name}"</div>
			</div> <!-- .cn-modal-header -->
			<div class="cn-modal-content clearfix">

				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Название
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="text" name="name" value="{$arResult.name}">
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Секретный ключ
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="text" name="secret_key" value="{$arResult.secret_key}">
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Период проверки (дни)
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<input class="input" type="number" name="check_period" value="{$arResult.check_period}">
					</div>
				</div>
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						Что проверять
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						{set $enforce = $arResult.enforce|split}
						<select class="styler" name="enforce[]" id="enforce" multiple>
							<option value="domain" {if 'domain' in $enforce} selected{/if}>домен</option>
							<option value="user_id" {if 'user_id' in $enforce} selected{/if}>ID пользователя</option>
							<option value="user_name" {if 'user_name' in $enforce} selected{/if}>имя пользователя</option>
							<option value="ip" {if 'ip' in $enforce} selected{/if}>ip сервера</option>
							<option value="server_hostname" {if 'server_hostname' in $enforce} selected{/if}>хост сервера</option>
						</select>
					</div>
				</div>
			
			
				<div class="content">
					<div class="col col-mb-12 col-5 col-dt-4 form-label">
						&nbsp;
					</div>
					<div class="col col-mb-12 col-7 col-dt-8 form-control">
						<button class="btn ladda-button" type="submit" data-style="expand-left"><span class="ladda-label">Сохранить метод</span></button>
					</div>
				</div>
			</div> <!-- .cn-modal-content -->
		</div>
	</form>
{/if}

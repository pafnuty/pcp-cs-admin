<form method="post" data-ladda-submit>
	{if $arResult.error}
		<div class="content">
			<div class="col col-mb-12 col-6 center-col">
				<div class="alert ta-center">
					{$arResult.error_text}
				</div>
			</div>
		</div>
	{/if}
	<div class="content">
		<div class="col col-mb-12 col-4 col-ld-3 center-col">
			
			<h2>Авторизация</h2>
			<div class="mb0">
				<p class="m0">
					<input type="text" name="name" class="input input-block" placeholder="Логин" autofocus>
				</p>
				<p class="m0">
					<input type="password" name="password" class="input input-block" placeholder="Пароль"/>
				</p>
			</div>
			<div class="clearfix">
				<div class="fleft">
					<input class="checkbox" name="remember_me" type="checkbox" value="1" checked id="remember_me"><label for="remember_me"><span></span> Запомнить</label>
				</div>
				<div class="fright">
					<button class="btn ladda-button" type="submit" name="submit" data-style="expand-left"><span class="ladda-label">Войти</span></button>
				</div>
			</div>
			<p class="text-muted ta-right">
				<small>Ваш ip адрес: {$arResult.userIp}</small>
			</p>
		</div>
	</div> <!-- .content -->
</form>

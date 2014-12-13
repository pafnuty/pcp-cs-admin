{*
=============================================================================
Независимая админка для php-cs
=============================================================================
Автор:   Павел Белоусов 
URL:     https://github.com/pafnuty/pcp-cs-admin
email:   pafnuty10@gmail.com
=============================================================================
*}
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>{$title}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="{$templateFolder}/css/style.css">
	</head>
	<body>
		{if !$logged}
			<div class="container">
				{include $templateName}
			</div> <!-- .container -->
		{else}
			<header class="container top_nav-container container-blue">
				<div class="content">
					<div class="col col-mb-12 col-2 logo-block">
						<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAaCAYAAAB8WJiDAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAslJREFUeNrcWotx2zAMpXxewCu4I7AjqCMoI9gjuCPYI1gjRCNUI1gjVCNEG1gFXdjRKSDDD0STfndIcjmJwtMjQBBicb1eD0KIo7BHB9bj7xpscLh3A1aBSbDtxEwoJn/H9JUDoXzn45R4v7R8/k+hBAYbPe0D7xcWdsDrXca/EGPE8JXDOPj6jnMzgFgzzNB7RJ0M153Bdh7jd8zRZOMrB7j4+o6j0KofKyZCR0Pq2QU42S/w8o8WaTIEXHzLgHEeY60M64DO3jQvvtKMddDM1D3Yj2+eZRNpnL5ygIsv5WNzW1cn9xignueVohssVv7M/l8SglREtLT44mMUPC6+coCT77yQGnAcJ/im6JaIDKqyo1Jh7GrW1lcOcPKVHPVIyBrcE0WMICKFiqrYsPGVA6nwZRHYtnJdqipOEUvy3aYoMLWOpIjR0/4uyLcjBD67Zp8V44zqE44sk6+SUQROdJot2AfYBcU+jONYgmlF9210lBkJXGYqcGPYB8u53yByjwVlDVukzjeCN/jQd13nJLH10MbXbaICt45buS3yvYDY7/eoXhvWJJ8Z96z1M8TXVCNY4Tdmm6Pj2lvh9b/WTI7sE1+DTb4OntlniFQ01mjVJDVLC8HV2lyFCjzgLKszEFbn61smE7OZZZ77p0eJdQa1B/cS+N4Z6sRnKzBV5OSrKxSvHgqq21qtopWoN6RO4IKx1JdMBY1Y2Ndc+NIvoSgaEPnL85dudAxEpbcRr4tn8x04Gx22KZKq8F4VyfFdWuBe04151Sh+Gl/c9863fP3SAjeC/lR3QeLyxQR+Cl/VrhR0n7pdRSB90nRdzkj8u4Z+mZnIXHx3FteOt5N1/w80kCdAYghcB+6T28wE5uIbmtZVTzpKBCvsscngug/N9fsxB9+QzHUKOZMVkrqmLTebg+A5HxAI5SsdJ8ajoQPiPuqAfwIMAJ59qPQZ8YkFAAAAAElFTkSuQmCC" alt="" />
					</div>
					<div class="col col-mb-12 col-10">
						<nav>
							<ul class="top_nav">
								<li class="{selected}">
									<a href="/">Лицензии</a> 
								</li>
								<li class="{selected get="methods"}">
									<a href="/?page=methods">Методы</a>
								</li>
								<li class="{selected get="logs"}">
									<a href="/?page=logs">Логи</a>
								</li>
								<li class="buy-nav-item last">
									<a href="/?action=logout">Выход</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
			</header>
			<div class="container pb0">
				<div class="content">
					<div class="col col-mb-12">
						<h1 class="h1 m0">{$h1}</h1>
					</div>
				</div>
			</div>
			<div class="container">
				{include $templateName}
			</div> <!-- .container -->
		{/if}

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="{$templateFolder}/js/cn.custom.form.js"></script>
		<script src="{$templateFolder}/js/jquery.form.min.js"></script>
		<script src="{$templateFolder}/js/jquery.ladda.production.min.js"></script>
		<script src="{$templateFolder}/js/jquery.magnificpopup.min.js"></script>
		<script src="{$templateFolder}/js/jquery.selectize.min.js"></script>
		<script src="{$templateFolder}/js/sweet-alert.min.js"></script>
		<script src="{$templateFolder}/js/url.min.js"></script>
		<script src="{$templateFolder}/js/main.js"></script>
	</body>
</html>
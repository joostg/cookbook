<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">

	<title>{% block title %}{% endblock %}</title>

	<link rel="stylesheet" href="/css/libs/bootstrap/dist/css/bootstrap.min.css">

	<style>
		body {
			padding-top: 54px;
		}
		@media (min-width: 992px) {
			body {
				padding-top: 56px;
			}
		}

	</style>
	<!-- Custom styles for this template -->
	<link href="/css/recept.css" rel="stylesheet">
</head>

<body role="document">
{% block menu %}
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#">Start Bootstrap</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active">
						<a class="nav-link" href="/">Home
							<span class="sr-only">(current)</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ data.global.base_url }}/recepten">Recepten</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ data.global.base_url }}/ingredienten">Ingrediënten</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ data.global.base_url }}/hoeveelheden">Hoeveelheden</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ data.global.base_url }}/logout">Uitloggen</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
{% endblock %}

{% block content %}{% endblock %}

<script src="/css/libs/jquery-3.3.1.min.js"></script>
<script src="/css/libs/popper.min.js"></script>
<script src="/css/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

{% if js %}{% for jsfile in js %}
	<script src="{{ jsfile }}"></script>
{% endfor %}{% endif %}
</body>
</html>

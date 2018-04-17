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
		<link rel="stylesheet" href="/css/libs/fontawesome/css/fontawesome-all.min.css">

		<style>
			body {
				padding-top: 84px;
			}
			@media (min-width: 992px) {
				body {
					padding-top: 86px;
				}
			}

		</style>
		<!-- Custom styles for this template -->
		<link href="/css/recept.css" rel="stylesheet">

        {% if data.css|length %}{% for cssData in data.css %}
			<link type="text/css" rel="stylesheet" href="{{ cssData }}" />
		{% endfor %}{% endif %}
	</head>

	<body role="document">
		{% block menu %}
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
			<div class="container">
				<a class="navbar-brand" href="#">Onsreceptenboek.nl</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ml-auto">
						{% for menuItem in data.menu_items %}
							<li class="nav-item">
								<a class="nav-link{% if menuItem.active == true %} active{% endif %}" href="{{ data.global.base_url }}{{ menuItem.link }}">{{ menuItem.label }}</a>
							</li>
						{% endfor %}
						<li class="nav-item">
							<a class="nav-link" href="{{ data.global.base_url }}/logout">Uitloggen</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		{% endblock %}
        {% if data.flash %}
		<div class="container">
			{% for type, flashMessages in data.flash %}
				{% for flashMessage in flashMessages %}
					<div class="alert {% if type == 'alert' %}alert-danger {% elseif type == 'info' %} alert-warning{% endif %} alert-dismissible fade show" role="alert">
                        {{ flashMessage }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
                {% endfor %}
			{% endfor %}
		</div>
        {% endif %}

		{% block content %}{% endblock %}

		<script src="/css/libs/jquery-3.3.1.min.js"></script>
		<script src="/css/libs/popper.min.js"></script>
		<script src="/css/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

		{% if data.js %}{% for jsfile in data.js %}
			<script src="{{ jsfile }}"></script>
		{% endfor %}{% endif %}
	</body>
</html>

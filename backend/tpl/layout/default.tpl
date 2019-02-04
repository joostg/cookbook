<!DOCTYPE html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Een verzameling van onze favoriete recepten.">
	<meta name="author" content="Joost Ganzeveld">

		<title>{% block title %}{% endblock %}</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
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

        {% if data.css|length %}{% for cssData in data.css %}
			<link type="text/css" rel="stylesheet" href="{{ cssData }}" />
		{% endfor %}{% endif %}
	</head>

	<body role="document">
		{% block menu %}
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
			<div class="container">
				<a class="navbar-brand" href="http://www.onsreceptenboek.nl">Onsreceptenboek.nl</a>
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

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

		{% if data.js %}{% for jsfile in data.js %}
			<script src="{{ jsfile }}"></script>
		{% endfor %}{% endif %}
	</body>
</html>

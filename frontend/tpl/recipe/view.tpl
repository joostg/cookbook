{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}Onsreceptenboek.nl - {{ data.name }}{% endblock %}

{% block content %}
	<div class="container" role="main">
		<div class="row mb-4">
			<a class="btn btn-light" href="{{ data.global.return_url }}">Terug</a>
		</div>

		<div class="row">
			<div class="col-md-4">
				<h2>{{ data.name }}</h2>
				<p>{{ data.intro }}</p>
			</div>

			<div class="col-md-8">
				{% if data.path_recipe_page %}
					<img src="{{ data.global.base_url }}/pics/{{ data.path_recipe_page }}" alt="{{ data.title }}" class="img-fluid">
				{% endif %}
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col-md-4 order-md-1 mb-4">
				<div class="card mb-6 bg-light box-shadow">
					<div class="card-header"><h3>Ingrediënten</h3></div>
					<ul class="list-group list-group-flush">
						{% for ingredient in data.ingredients %}
							<li class="list-group-item">{{ ingredient }}</li>
						{% endfor %}
					</ul>
				</div>
			</div>

			<div class="col-md-8 order-md-0 mb-4">
				<h3>Bereiding</h3>
                {{ data.description }}
			</div>
		</div>
	</div>
{% endblock %}

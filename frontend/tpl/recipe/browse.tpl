{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}{{ name }}{% endblock %}

{% block content %}
	<div class="container theme-showcase" role="main">
		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron">
			<h2>{{ name }}</h2>
			<p>{{ intro }}</p>
		</div>

		<div class="row">
			<div class="col-md-4 col-md-push-8">
				<h3>Ingrediënten</h3>
				<ul>
					{% for ingredient in ingredients %}
						<li>{{ ingredient.quantity }} {{ ingredient.quantity_name }} {{ ingredient.ingredient_name }}</li>
					{% endfor %}
				</ul>
			</div>

			<div class="col-md-8 col-md-pull-4">
				{% if image %}
					<img src="/images/{{ image }}" class="img-responsive" alt="{{ name }}">
				{% endif %}
				<h3>Bereiding</h3>
				{{ description }}
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">

			</div>
		</div>

	</div> <!-- /container -->

{% endblock %}

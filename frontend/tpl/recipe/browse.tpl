{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}{{ name }}{% endblock %}

{% block content %}
	<div class="container theme-showcase" role="main">
		<div class="row">
			<div class="col-md-4">
				<h2>{{ name }}</h2>
				<p>{{ intro }}</p>
			</div>

			<div class="col-md-8">
				{% if path_recipe_page %}
					<img src="/pics/{{ path_recipe_page }}" alt="{{ title }}" class="img-fluid">
				{% endif %}
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col-md-4 order-md-1">
				<div class="card mb-6 box-shadow" style="background-color: lightblue">
					<div class="card-body">
				<h3>Ingrediënten</h3>
				<ul>
					{% for ingredient in ingredients %}
						<li>{{ ingredient.quantity }} {{ ingredient.quantity_name }} {{ ingredient.ingredient_name }}</li>
					{% endfor %}
				</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6 order-md-0">
				<h3>Bereiding</h3>
                {{ description }}
			</div>
		</div>
	</div>
{% endblock %}

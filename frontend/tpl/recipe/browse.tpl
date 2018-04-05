{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}{{ name }}{% endblock %}

{% block content %}
	<div class="container theme-showcase" role="main">
		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="row">
			<div class="col-md-4">
				<h2>{{ name }}</h2>
				<p>{{ intro }}</p>
			</div>

			<div class="col-md-8">
				{#<img src="/pics/c55c21d0dc0a1e6005a72903981c2420_.png" class="img-fluid" alt="{{ name }}">#}
				<img class="img-fluid" data-src="holder.js/730x548?theme=thumb&bg=55595c&fg=eceeef&text=onsreceptenboek.nl" alt="Card image cap">
			</div>
		</div>

		<div class="row">
			<div class="col-md-4 order-md-1">
				<div class="card mb-4 box-shadow" style="background-color: lightblue">
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

			<div class="col-md-8 order-md-0">
				<h3>Bereiding</h3>
                {{ description }}
			</div>
		</div>
	</div> <!-- /container -->

{% endblock %}

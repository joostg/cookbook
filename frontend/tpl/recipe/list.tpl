{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}Onsreceptenboek.nl - Recepten{% endblock %}

{% block content %}
	<div class="container theme-showcase" role="main">
		<div class="row">
			<div class="col-12">
				<div class="card mb-4">
					<h3 class="card-header">Filter op tag</h3>
					<div class="card-body">
						<h4 class="mr-2">
							{% for tag in data.tag_filter %}
								<a href="{{ data.global.base_url }}/recepten{% if tag.selected is not defined %}?tag={{ tag.path }}{% endif %}"
								   class="badge {% if tag.selected is defined %}badge-primary{% else %}badge-secondary{% endif %}">
									{{ tag.name }}
								</a>
							{% endfor %}
						</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			{% for recipe in data.items %}
			<div class="col-md-4">
				<div class="card mb-4 box-shadow imagebox">
					<a href="{{ data.global.base_url }}/recepten/{{ recipe.path }}" title="{{ recipe.name }}">
						{% if recipe.path_thumb %}
							<img class="card-img-top" class="img-responsive" src="{{ data.global.base_url }}/pics/{{ recipe.path_thumb }}" alt="{{ recipe.title }}">
						{% else %}
							<img class="card-img-top" class="img-responsive" src="holder.js/348x261?auto=yes&theme=social&text=www.onsreceptenboek.nl" alt="{{ recipe.title }}">
						{% endif %}

						<span class="imagebox-desc">{{ recipe.name }}</span>
					</a>
				</div>
			</div>
			{% endfor %}
		</div>

		{% include '/backend/tpl/system/paging.tpl' %}
	</div>
{% endblock %}

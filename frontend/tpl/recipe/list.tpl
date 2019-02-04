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
				<div class="card mb-4 box-shadow">
					{% if recipe.path_thumb %}
						<img class="card-img-top" src="{{ data.global.base_url }}/pics/{{ recipe.path_thumb }}" alt="{{ recipe.title }}">
					{% endif %}
					<div class="card-body">
						<h3 class="card-title">{{ recipe.name }}</h3>
						<p class="card-text">
							{{ recipe.intro }}
						</p>
						<div class="d-flex justify-content-between align-items-center">
							<div class="btn-group">
								<a type="button" class="btn btn-sm btn-outline-secondary" href="{{ data.global.base_url }}/recepten/{{ recipe.path }}">Bekijken</a>
							</div>
							<small class="text-muted">{% if recipe.created_at %}{{ recipe.created_at|date('d-m-Y') }}{% endif %}</small>
						</div>
					</div>
				</div>
			</div>
			{% endfor %}
		</div>

		{% include '/backend/tpl/system/paging.tpl' %}
	</div>
{% endblock %}

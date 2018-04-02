{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Dashboard{% endblock %}

{% block content %}
	<div class="container theme-showcase" role="main">
		<div class="col-md-6">
			<h1>Dashboard</h1>

			<p>Welkom, {{ user }}.</p>
		</div>
	</div>

{% endblock %}
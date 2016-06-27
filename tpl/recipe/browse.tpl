{% extends 'layout/default.tpl' %}

{% block title %}Recept{% endblock %}

{% block content %}
	<div class="container theme-showcase" role="main">
		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron">
			<h1>Theme example</h1>
			<p>This is a template showcasing the optional theme stylesheet included in Bootstrap. Use it as a starting point to create something more unique by building on or modifying it.</p>
			<p>hallo? Je naam is {{data}}</p>
		</div>

		<!-- Stack the columns on mobile by making one full-width and the other half-width -->
		<div class="row">
			<div class="col-xs-12 col-md-8"><p>.col-xs-12 .col-md-8</p></div>
			<div class="col-xs-6 col-md-4"><p>.col-xs-6 .col-md-4</p></div>
		</div>

		<!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
		<div class="row">
			<div class="col-xs-6 col-md-4"><p>.col-xs-6 .col-md-4</p></div>
			<div class="col-xs-6 col-md-4"><p>.col-xs-6 .col-md-4</p></div>
			<div class="col-xs-6 col-md-4"><p>.col-xs-6 .col-md-4</p></div>
		</div>

		<!-- Columns are always 50% wide, on mobile and desktop -->
		<div class="row">
			<div class="col-xs-6"><p>.col-xs-6</p></div>
			<div class="col-xs-6"><p>.col-xs-6</p></div>
		</div>

	</div> <!-- /container -->

{% endblock %}

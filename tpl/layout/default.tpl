<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link type="text/css" rel="stylesheet"
		  href="/min/?f={{data.defaultCss}}&amp;{{data.global.build}}" />
	{% if data.css|length %}
		<link type="text/css" rel="stylesheet"
			  href="/min/?f={{data.css}}&amp;{{data.global.build}}" />
	{% endif %}
	<title>{% block title %}{% endblock %}</title>
</head>
<body>
<div id="content" class="clearfix">
	{% block content %}{% endblock %}
</div>
</body>
</html>

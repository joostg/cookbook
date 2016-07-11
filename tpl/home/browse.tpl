{% extends 'layout/default.tpl' %}

{% block title %}Cookbook.dev{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <h2>Cookbook.dev</h2>
            <p>Tekst ter introductie.</p>
        </div>

        {% for recipe in recipes %}
            <div class="col-md-4">
                <h3>{{ recipe.name }}</h3>
                <p>{{ recipe.intro }}</p>
                <p><a href="/recept/{{ recipe.path }}">Bekijk recept</a></p>
            </div>
        {% endfor %}
    </div>
{% endblock %}
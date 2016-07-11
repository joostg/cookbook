{% extends 'layout/dashboard.tpl' %}

{% block title %}Recepten{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <ol>
               {% for recipe in recipes %}
                   <li><a href="/achterkant/recipe/edit/{{ recipe.id }}">{{ recipe.name }}</a></li>
               {% endfor %}
            </ol>
        </div>
    </div>
{% endblock %}
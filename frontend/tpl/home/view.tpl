{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}Onsreceptenboek.nl - Home{% endblock %}

{% block content %}
    <div class="container" role="main">
        <div class="row">
            {% for recipe in data.recipes %}
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow imagebox">
                        <a href="{{ data.global.base_url }}/recepten/{{ recipe.path }}" title="{{ recipe.name }}">
                            {% if recipe.path_thumb %}
                                <img class="card-img-top" class="img-responsive" src="{{ data.global.base_url }}/pics/{{ recipe.path_thumb }}" alt="{{ recipe.title }}">
                            {% else %}
                                <img class="card-img-top" class="img-responsive" src="holder.js/348x261?auto=yes&bg=e3f2fd&fg=292f33&text=www.onsreceptenboek.nl" alt="{{ recipe.title }}">
                            {% endif %}

                            <span class="imagebox-desc">{{ recipe.name }}</span>
                        </a>
                    </div>
                </div>
            {% endfor %}
        </div>
        <div class="row">
            <div class="col-12">
                <a class="btn btn-primary" href="{{ data.global.base_url }}/recepten">Bekijk alle recepten</a>
            </div>
        </div>
    </div>
{% endblock %}
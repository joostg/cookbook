{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}Cookbook.dev{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="row">
            {% for recipe in recipes %}
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        {% if recipe.path_thumb %}
                            <img class="card-img-top" src="/pics/{{ recipe.path_thumb }}" alt="{{ recipe.title }}">
                        {% else %}
                            {#<img class="card-img-top" data-src="holder.js/348x261?theme=thumb&bg=55595c&fg=eceeef&text=onsreceptenboek.nl" alt="onsreceptenboek.nl">#}
                        {% endif %}
                        <div class="card-body">
                            <h3>{{ recipe.name }}</h3>
                            <p class="card-text">
                                {{ recipe.intro }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-outline-secondary" href="/recept/{{ recipe.path }}">Bekijken</a>
                                </div>
                                <small class="text-muted">{% if recipe.created_at %}{{ recipe.created_at|date('d-m-Y') }}{% endif %}</small>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
{% endblock %}
{% extends 'frontend/tpl/layout/default.tpl' %}

{% block title %}Cookbook.dev{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <h2>Cookbook.dev</h2>
            <p>Tekst ter introductie.</p>
        </div>

        <div class="row">
            {% for recipe in recipes %}
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" data-src="holder.js/100px225?theme=thumb&bg=55595c&fg=eceeef&text=onsreceptenboek.nl" alt="Card image cap">
                        <div class="card-body">
                            <h3>{{ recipe.name }}</h3>
                            <p class="card-text">
                                {{ recipe.intro }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-outline-secondary" href="/recept/{{ recipe.path }}">Bekijken</a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                </div>
                                <small class="text-muted">{{ recipe.created|date('d-m-Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
{% endblock %}
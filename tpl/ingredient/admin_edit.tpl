{% extends 'layout/dashboard.tpl' %}

{% block title %}Ingrediënt {% if ingredient.id %}wijzigen{% else %}toevoegen{% endif %}{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Ingrediënt {% if ingredient.id %}wijzigen{% else %}toevoegen{% endif %}</h1>

            <form method="post" action="/achterkant/ingredienten/opslaan" id="recipe">
                <div class="col-md-6">
                    {% if ingredient.id %}
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{ ingredient.id }}">
                        </div>
                    {% endif %}
                    <div class="form-group">
                        <label for="name">Naam</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Naam" value="{% if ingredient.name %}{{ ingredient.name }}{% endif %}" required="required">
                    </div>

                    <div class="form-group">
                        <label for="plural">Meervoud (optioneel)</label>
                        <input type="text" class="form-control" id="plural" name="plural" placeholder="Meervoud" value="{% if ingredient.plural %}{{ ingredient.plural }}{% endif %}">
                    </div>

                    <button type="submit" class="btn btn-default">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}
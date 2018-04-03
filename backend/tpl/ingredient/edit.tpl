{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Ingrediënt {% if  data.ingredient.id %}wijzigen{% else %}toevoegen{% endif %}{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Ingrediënt {% if data.ingredient.id %}wijzigen{% else %}toevoegen{% endif %}</h1>

            <form method="post" action="{{ data.global.base_url }}/ingredienten/opslaan" id="recipe">
                <div class="col-md-6">
                    {% if  data.ingredient.id %}
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{ data.ingredient.id }}">
                        </div>
                    {% endif %}
                    <div class="form-group">
                        <label for="name">Naam</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Naam" value="{% if data.ingredient.name %}{{ data.ingredient.name }}{% endif %}" required="required">
                    </div>

                    <div class="form-group">
                        <label for="plural">Meervoud (optioneel)</label>
                        <input type="text" class="form-control" id="plural" name="plural" placeholder="Meervoud" value="{% if data.ingredient.plural %}{{ data.ingredient.plural }}{% endif %}">
                    </div>

                    <button type="submit" class="btn btn-default">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}
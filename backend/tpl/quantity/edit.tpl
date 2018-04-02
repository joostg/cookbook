{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Hoeveelheid {% if quantity.id %}wijzigen{% else %}toevoegen{% endif %}{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Hoeveelheid {% if quantity.id %}wijzigen{% else %}toevoegen{% endif %}</h1>

            <form method="post" action="{{ data.global.base_url }}/hoeveelheden/opslaan" id="recipe">
                <div class="col-md-6">
                    {% if quantity.id %}
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{ quantity.id }}">
                        </div>
                    {% endif %}
                    <div class="form-group">
                        <label for="name">Naam</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Naam" value="{% if quantity.name %}{{ quantity.name }}{% endif %}" required="required">
                    </div>

                    <div class="form-group">
                        <label for="plural">Meervoud (optioneel)</label>
                        <input type="text" class="form-control" id="plural" name="plural" placeholder="Meervoud" value="{% if quantity.plural %}{{ quantity.plural }}{% endif %}">
                    </div>

                    <button type="submit" class="btn btn-default">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
{% endblock %}
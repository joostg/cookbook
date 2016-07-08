{% extends 'layout/default.tpl' %}

{% block title %}Recept editor{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-6">
            <h1>Recept editor</h1>

            <form method="post" action="/achterkant/recipe/save">
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Naam" value="{% if name %}{{ name }}{% endif %}" required="required">
                </div>
                <div class="form-group">
                    <label for="intro">Intro</label>
                    <textarea class="form-control" id="intro" name="intro" required="required" rows="3">{% if intro %}{{ intro }}{% endif %}</textarea>
                </div>
                <div class="form-group">
                    <label for="description">Beschrijving</label>
                    <textarea class="form-control" id="description" name="description" required="required" rows="10">{% if description %}{{ description }}{% endif %}</textarea>
                </div>
                <div class="form-group">
                    <label for="image">Afbeelding</label>
                    <select class="form-control" id="image" name="image">
                        <option value=""></option>
                        <option value="1">Afbeelding 1</option>
                        <option value="2">Afbeelding 2</option>
                        <option value="3">Afbeelding 3</option>
                        <option value="4">Afbeelding 4</option>
                        <option value="5">Afbeelding 5</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-default">Opslaan</button>
            </form>
        </div>
    </div>
{% endblock %}
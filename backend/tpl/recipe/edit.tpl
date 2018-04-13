{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Recept editor{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="row">
            <div class="col-md-12">
                <h1>Recept editor</h1>

                <form method="post" action="{{ data.global.base_url }}/recepten/opslaan" id="recipe">
                    <div class="form-row">
                        <div class="col-md-6">
                            {% if data.recipe.id %}
                                <div class="form-group">
                                    <input type="hidden" name="id" id="id" value="{{  data.recipe.id }}">
                                </div>
                            {% endif %}
                            <div class="form-group">
                                <label for="name">Naam</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Naam" value="{% if data.recipe.name %}{{ data.recipe.name }}{% endif %}" required="required">
                            </div>
                            <div class="form-group">
                                <label for="intro">Intro</label>
                                <textarea class="form-control" id="intro" name="intro" rows="3">{% if data.recipe.intro %}{{ data.recipe.intro }}{% endif %}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="description">Beschrijving</label>
                                <textarea  class="form-control" id="description" name="description" rows="10">{% if data.recipe.description %}{{ data.recipe.description }}{% endif %}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Afbeelding</label>
                                <select class="form-control image" name="image">
                                    <option value="0"></option>
                                    {% for image in data.image_list %}
                                        <option value="{{ image.id }}"
                                                {% if data.recipe.image_id == image.id %}selected="selected"{% endif %}>
                                            {{ image.title }}
                                        </option>
                                    {% endfor %}
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" onclick="addSortDataToIngredientrows()">Opslaan</button>
                        </div>

                        <div class="col-md-6 form-group">
                            <button type="button" class="btn btn-primary add-ingredient" aria-label="Left Align">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ingrediënt toevoegen
                            </button>

                            <hr>

                            <fieldset name="ingredients" id="ingredients">
                                {% if data.ingredients %}
                                    {% for ingredientrow in data.ingredients %}
                                        <fieldset>
                                        <div class="form-row ingredient-row" data-unique-identifier="[{{ ingredientrow.id }}]">
                                            <div class="form-group col-xs-1 col-sm-1">
                                                <i class="fas fa-arrows-alt move-ingredient"></i>
                                            </div>

                                            <input type="hidden" name="ingredient[{{ ingredientrow.id }}][id]" value="{{  ingredientrow.id }}">

                                            <div class="form-group col-xs-4 col-sm-3">
                                                <label class="sr-only" for="amount">Hoeveelheid</label>
                                                <input type="number" min="0" step="0.01" class="form-control amount" name="ingredient[{{ ingredientrow.id }}][amount]" value="{{ ingredientrow.amount }}">
                                            </div>
                                            <div class="form-group col-xs-6 col-sm-3">
                                                <label class="sr-only" for="quantity_id">Kwantiteit</label>
                                                <select class="form-control quantity_id" name="ingredient[{{ ingredientrow.id }}][quantity_id]">
                                                    <option value=""></option>
                                                    {% for quantity in data.quantity_list %}
                                                        <option value="{{ quantity.id }}"
                                                        {% if ingredientrow.quantity_id == quantity.id %}selected="selected"{% endif %}>
                                                            {{ quantity.name }}
                                                        </option>
                                                    {% endfor %}
                                                </select>
                                            </div>
                                            <div class="form-group col-xs-10 col-sm-4">
                                                <label class="sr-only" for="ingredient_id">Ingrediënt</label>
                                                <select class="form-control ingredient_id" name="ingredient[{{ ingredientrow.id }}][ingredient_id]">
                                                    <option value=""></option>
                                                    {% for ingredient in data.ingredient_list %}
                                                        <option value="{{ ingredient.id }}"
                                                                {% if ingredientrow.ingredient_id == ingredient.id %}selected="selected"{% endif %}>
                                                            {{ ingredient.name }}
                                                        </option>
                                                    {% endfor %}
                                                </select>
                                            </div>
                                            <div class="form-group col-xs-1 col-sm-1">
                                                <i class="fas fa-trash-alt delete-ingredient"></i>
                                            </div>
                                        </div>
                                        </fieldset>
                                    {% endfor %}
                                {% endif %}
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {% set ingredientRow %}
        <div class="form-row ingredient-row" data-unique-identifier="[unique_identifier]">

        <div class="form-group col-xs-1 col-sm-1">
            <i class="fas fa-arrows-alt move-ingredient"></i>
        </div>
        <div class="form-group col-xs-4 col-sm-3">
            <label class="sr-only" for="amount">Hoeveelheid</label>
            <input type="number" min="0.00" name="ingredient[unique_identifier][amount]" class="form-control amount">
        </div>
        <div class="form-group col-xs-6 col-sm-3">
            <label class="sr-only" for="quantity_id">Kwantiteit</label>
            <select name="ingredient[unique_identifier][quantity_id]" class="form-control quantity_id">
                <option value=""></option>
                {% for quantity in data.quantity_list %}
                    <option value="{{ quantity.id }}">{{ quantity.name }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="form-group col-xs-10 col-sm-4">
            <label class="sr-only" for="ingredient_id">Ingrediënt</label>
            <select  name="ingredient[unique_identifier][ingredient_id]" class="form-control ingredient_id">
                <option value=""></option>
                {% for ingredient in data.ingredient_list %}
                    <option value="{{ ingredient.id }}">{{ ingredient.name }}</option>
                {% endfor %}
            </select>
        </div>
        <div class="form-group col-xs-1 col-sm-1">
            <i class="fas fa-trash-alt delete-ingredient"></i>
        </div>
    </div>
    {% endset %}

    <script type="text/javascript">
        var ingredientRow = '{{ ingredientRow|e('js') }}';
    </script>
{% endblock %}
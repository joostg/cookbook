{% extends 'layout/dashboard.tpl' %}

{% block title %}Recept editor{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Recept editor</h1>


            <form method="post" action="/achterkant/recipe/save" id="recipe">
                <div class="col-md-6">
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
                </div>

                <div class="col-md-6 list-group" id="ingredients">
                    <div class="row list-group-item">
                        <div class="col-xs-4 col-sm-3">
                            <label class="sr-only" for="quantity">Ingrediënten</label>
                            <input type="number" min="0.00" class="form-control quantity">
                        </div>
                        <div class="col-xs-8 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                    </div>
                    <div class="row list-group-item">
                        <div class="col-xs-4 col-sm-3">
                            <label class="sr-only" for="quantity">Ingrediënten</label>
                            <input type="number" min="0.00" class="form-control quantity">
                        </div>
                        <div class="col-xs-8 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                    </div>
                    <div class="row list-group-item">
                        <div class="col-xs-4 col-sm-3">
                            <label class="sr-only" for="quantity">Ingrediënten</label>
                            <input type="number" min="0.00" class="form-control quantity">
                        </div>
                        <div class="col-xs-8 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                    </div>
                    <div class="row list-group-item">
                        <div class="col-xs-4 col-sm-3">
                            <label class="sr-only" for="quantity">Ingrediënten</label>
                            <input type="number" min="0.00" class="form-control quantity">
                        </div>
                        <div class="col-xs-8 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                    </div>
                    <div class="row list-group-item">
                        <div class="col-xs-4 col-sm-3">
                            <label class="sr-only" for="quantity">Ingrediënten</label>
                            <input type="number" min="0.00" class="form-control quantity">
                        </div>
                        <div class="col-xs-8 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <select class="form-control" id="image" name="image">
                                <option value=""></option>
                                <option value="1">Afbeelding 1</option>
                                <option value="2">Afbeelding 2</option>
                                <option value="3">Afbeelding 3</option>
                                <option value="4">Afbeelding 4</option>
                                <option value="5">Afbeelding 5</option>
                            </select>
                        </div>
                        <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
                    </div>

                </div>

                <button type="button" class="btn btn-default add-ingredient" aria-label="Left Align">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>
{% endblock %}
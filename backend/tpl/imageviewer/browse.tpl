{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Afbeelingen{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Afbeelingen</h1>
            <form id="productimg" name="upload" method="POST" enctype="multipart/form-data" action="/achterkant/afbeeldingen/upload">
                Afbeelding uploaden: <input type="file" name="image" id="uploadimg" required>
                Titel <input type="text" name="title" id="title" required>
                <input type="submit" name="upload" value="upload" id="upload">
            </form>
        </div>

        <div class="row">
            {% for image in data.images %}
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" src="/pics/{{ image.path_thumb }}" alt="Card image cap">
                        <div class="card-body">
                            <h3>{{ image.title }}</h3>
                            <p class="card-text">
                                {{ recipe.intro }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-outline-secondary" href="/recept/{{ recipe.path }}">Bekijken</a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                </div>
                                <small class="text-muted">{{ image.created|date('d-m-Y H:i:s') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
{% endblock %}
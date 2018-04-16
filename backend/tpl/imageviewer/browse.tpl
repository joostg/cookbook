{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Afbeelingen{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h1>Afbeelingen</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <form id="productimg" name="upload" method="POST" enctype="multipart/form-data" action="{{ data.global.base_url }}/afbeeldingen/upload">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Uploaden</span>
                        </div>
                        <div class="custom-file">
                            <input class="custom-file-input" type="file" name="image" id="uploadimg" required>
                            <label class="custom-file-label" for="uploadimg">Kies een bestand</label>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input class="form-control" type="text" name="title" id="title" placeholder="Afbeeldingstitel" required>
                            <div class="input-group-append">
                            <input class="btn btn-secondary" type="submit" name="upload" value="upload" id="upload">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <h2>Geüploade afbeeldingen</h2>
            </div>
        </div>

        <div class="row">
            {% for image in data.images %}
                <div class="col-md-4">
                    <div class="card mb-4 box-shadow">
                        <img class="card-img-top" src="/pics/{{ image.path_thumb }}" alt="Card image cap">
                        <div class="card-body">
                            <h3>{{ image.title }}</h3>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a type="button" class="btn btn-sm btn-outline-secondary"  href="{{ data.global.base_url }}/afbeeldingen/verwijderen/{{ image.id }}">Verwijderen</a>
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
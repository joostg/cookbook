{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Hoeveelheden{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="row">
            <div class="col-md-4">
                <h1 class="mb-4">Hoeveelheden</h1>
            </div>
            <div class="col-mb-3 offset-md-5">
                <a href="{{ data.global.base_url }}/hoeveelheden/wijzigen" class="btn btn-primary" >
                    <i class="fas fa-plus"></i> Hoeveelheid toevoegen
                </a>
            </div>
        </div>

        <form method="get" action="" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input class="form-control" placeholder="Zoekterm" type="search" name="q" id="q" value="{{ data.query }}" />
                </div>
                <div class="col-md-2">
                    <input class="btn btn-secondary" type="submit" id="submit" value="Zoeken" />
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped mb-4">
                    <tr>
                        <th>Wijzigen</th>
                        {% include '/backend/tpl/system/listHeaders.tpl' %}
                        <th>Verwijderen</th>
                    </tr>
                   {% for item in data.items %}
                       <tr>
                           <td>
                               <a href="{{ data.global.base_url }}/hoeveelheden/wijzigen/{{ item.id }}">
                                   <i class="fas fa-edit "></i>
                               </a>
                           </td>
                           <td>{{ item.name }}</td>
                           <td>{{ item.plural }}</td>
                           <td>{% if item.updated_at %}{{ item.updated_at|date('d-m-Y H:i:s') }}{% endif %}</td>
                           <td>{{ item.updated_by }}</td>
                           <td>
                               <a href="{{ data.global.base_url }}/hoeveelheden/verwijderen/{{ item.id }}"
                                  onclick="return confirm('Weet je zeker dat je hoeveelheid `{{ item.name }}` wilt verwijderen?')">
                                   <i class="fas fa-trash "></i>
                               </a>
                           </td>
                       </tr>
                   {% endfor %}
                </table>
            </div>
        </div>

        {% include '/backend/tpl/system/paging.tpl' %}
    </div>
{% endblock %}
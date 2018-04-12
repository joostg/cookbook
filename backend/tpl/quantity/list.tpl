{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Hoeveelheden{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Hoeveelheden</h1>
            <a href="{{ data.global.base_url }}/hoeveelheden/wijzigen" class="btn btn-default active" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Hoeveelheid toevoegen
            </a>
        </div>

        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>Wijzigen</th>
                    <th>Hoeveelheid</th>
                    <th>Meervoud</th>
                    <th>Gewijzigd</th>
                    <th>Gewijzigd door</th>
                    <th>Verwijderen</th>
                </tr>
               {% for quantity in data.quantities %}
                   <tr>
                       <td>
                           <a href="{{ data.global.base_url }}/hoeveelheden/wijzigen/{{ quantity.id }}">
                               <i class="fas fa-edit "></i>
                           </a>
                       </td>
                       <td>
                           {{ quantity.name }}
                       </td>
                       <td>
                           {{ quantity.plural }}
                       </td>
                       <td>{% if quantity.updated_at %}{{ quantity.updated_at|date('d-m-Y H:i:s') }}{% endif %}</td>
                       <td>{{ quantity.updated_by }}</td>
                       <td>
                           <a href="{{ data.global.base_url }}/hoeveelheden/verwijderen/{{ quantity.id }}"
                              onclick="return confirm('Weet je zeker dat je hoeveelheid `{{ quantity.name }}` wilt verwijderen?')">
                               <i class="fas fa-trash "></i>
                           </a>
                       </td>
                   </tr>
               {% endfor %}
            </table>
        </div>
    </div>
{% endblock %}
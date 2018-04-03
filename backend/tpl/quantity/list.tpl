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

        <div class="col-md-6">
            <table class="table">
                <tr>
                    <th>Wijzigen</th>
                    <th>Hoeveelheid</th>
                    <th>Meervoud</th>
                </tr>
               {% for quantity in data.quantities %}
                   <tr>
                       <td>
                           <a href="{{ data.global.base_url }}/hoeveelheden/wijzigen/{{ quantity.id }}">
                                <span class="glyphicon glyphicon-pencil"></span>
                               wijzigen
                           </a>
                       </td>
                       <td>
                           {{ quantity.name }}
                       </td>
                       <td>
                           {{ quantity.plural }}
                       </td>
                   </tr>
               {% endfor %}
            </table>
        </div>
    </div>
{% endblock %}
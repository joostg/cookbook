{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Ingrediënten{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Ingrediënten</h1>
            <a href="{{ data.global.base_url }}/ingredienten/wijzigen" class="btn btn-default active" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ingrediënt toevoegen
            </a>
        </div>

        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>Wijzigen</th>
                    <th>Ingrediënt</th>
                    <th>Meervoud</th>
                    <th>Gewijzigd</th>
                    <th>Gewijzigd door</th>
                    <th>Verwijderen</th>
                </tr>
               {% for ingredient in data.ingredients %}
                   <tr>
                       <td>
                           <a href="{{ data.global.base_url }}/ingredienten/wijzigen/{{ ingredient.id }}">
                                <i class="fas fa-edit "></i>
                           </a>
                       </td>
                       <td>
                           {{ ingredient.name }}
                       </td>
                       <td>
                           {{ ingredient.plural }}
                       </td>
                       <td>{% if ingredient.modified %}{{ ingredient.modified|date('d-m-Y H:i:s') }}{% endif %}</td>
                       <td>{{ ingredient.modifier }}</td>
                       <td>
                           <a href="{{ data.global.base_url }}/ingredienten/verwijderen/{{ ingredient.id }}"
                              onclick="return confirm('Weet je zeker dat je ingredient `{{ ingredient.name }}` wilt verwijderen?')">
                               <i class="fas fa-trash "></i>
                           </a>
                       </td>
                   </tr>
               {% endfor %}
            </table>
        </div>
    </div>
{% endblock %}
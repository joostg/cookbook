{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Recepten{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Recepten</h1>
            <a href="{{ data.global.base_url }}/recepten/wijzigen" class="btn btn-default active" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Recept toevoegen
            </a>
        </div>


        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>Wijzigen</th>
                    <th>Recept</th>
                    <th>Gewijzigd</th>
                    <th>Gewijzigd door</th>
                    <th>Verwijderen</th>
                </tr>
               {% for recipe in data.recipes %}
                   <tr>
                       <td>
                           <a href="{{ data.global.base_url }}/recepten/wijzigen/{{ recipe.id }}">
                               <i class="fas fa-edit "></i>
                           </a>
                       </td>
                       <td>
                           {{ recipe.name }}
                       </td>
                       <td>{% if recipe.modified %}{{ recipe.modified|date('d-m-Y H:i:s') }}{% endif %}</td>
                       <td>{{ recipe.modifier }}</td>
                       <td>
                           <a href="{{ data.global.base_url }}/recepten/verwijderen/{{ recipe.id }}"
                              onclick="return confirm('Weet je zeker dat je recept `{{ recipe.name }}` wilt verwijderen?')">
                               <i class="fas fa-trash "></i>
                           </a>
                       </td>
                   </tr>
               {% endfor %}
            </table>
        </div>
    </div>
{% endblock %}
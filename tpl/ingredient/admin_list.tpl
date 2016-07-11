{% extends 'layout/dashboard.tpl' %}

{% block title %}Ingrediënten{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Ingrediënten</h1>
            <a href="/achterkant/ingredienten/wijzigen" class="btn btn-default active" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Ingrediënt toevoegen
            </a>
        </div>

        <div class="col-md-6">
            <table class="table">
                <tr>
                    <th>Wijzigen</th>
                    <th>Ingrediënt</th>
                    <th>Meervoud</th>
                </tr>
               {% for ingredient in ingredients %}
                   <tr>
                       <td>
                           <a href="/achterkant/ingredienten/wijzigen/{{ ingredient.id }}">
                                <span class="glyphicon glyphicon-pencil"></span>
                           </a>
                       </td>
                       <td>
                           {{ ingredient.name }}
                       </td>
                       <td>
                           {{ ingredient.plural }}
                       </td>
                   </tr>
               {% endfor %}
            </table>
        </div>
    </div>
{% endblock %}
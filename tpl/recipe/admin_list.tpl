{% extends 'layout/dashboard.tpl' %}

{% block title %}Recepten{% endblock %}

{% block content %}
    <div class="container theme-showcase" role="main">
        <div class="col-md-12">
            <h1>Recepten</h1>
            <a href="/achterkant/recepten/wijzigen" class="btn btn-default active" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Recept toevoegen
            </a>
        </div>


        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>Wijzigen</th>
                    <th>Recept</th>
                    <th>Aangemaakt</th>
                </tr>
               {% for recipe in recipes %}
                   <tr>
                       <td>
                           <a href="/achterkant/recepten/wijzigen/{{ recipe.id }}">
                               <span class="glyphicon glyphicon-pencil"></span>
                           </a>
                       </td>
                       <td>
                           {{ recipe.name }}
                       </td>
                       <td>
                           {{ recipe.created }}
                       </td>
                   </tr>
               {% endfor %}
            </table>
        </div>
    </div>
{% endblock %}
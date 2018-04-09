{% extends '/backend/tpl/layout/default.tpl' %}

{% block title %}Inloggen{% endblock %}

{% block menu %}{% endblock %}

{% block content %}
<div class="container theme-showcase" role="main">
    <div class="row">
        <div class="col-md-4">

            <form method="post" class="form-signin" action="{{ data.global.base_url }}/login">

                <h1 class="h3 mb-3 font-weight-normal">Inloggen</h1>
                <input class="form-control" type="text" id="user" name="user" value="" placeholder="Gebruikersnaam" required="required">
                <input class="form-control" type="password" id="pass" name="pass" value="" placeholder="Wachtwoord" required="required">

                <p class="submit"><input type="submit" name="commit" value="Login"></p>
            </form>
        </div>
    </div>
</div>

{% endblock %}
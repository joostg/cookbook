{% extends 'layout/default.tpl' %}

{% block title %}Inloggen{% endblock %}

{% block content %}
<div class="container theme-showcase" role="main">
    <div class="row">
        <div class="col-md-12">
            <h1>Inloggen</h1>

            <form method="post" action="/login">
                <p><input type="text" id="user" name="user" value="" placeholder="Email"></p>
                <p><input type="password" id="pass" name="pass" value="" placeholder="Wachtwoord"></p>

                <p class="submit"><input type="submit" name="commit" value="Login"></p>
            </form>
        </div>
    </div>
</div>

{% endblock %}
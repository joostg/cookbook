{% for header in data.listHeaders %}
<th>
    <a href="{{header.qs}}">{{header.label}}
        {% if header.active %}
            {% if header.activeDirection == 'desc' %}
                <i class="fas fa-caret-up"></i>
            {% elseif header.activeDirection == 'asc' %}
                <i class="fas fa-caret-down"></i>
            {% endif %}
        {% endif %}
    </a>
</th>
{% endfor %}
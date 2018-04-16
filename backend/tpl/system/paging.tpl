{% set paging = data.paging %}
<nav aria-label="paginering">
	<ul class="pagination">
		{% if (paging.current > 1) and (paging.total > paging.limit) %}
			<li class="page-item first"><a class="page-link" href="?{{paging.qsFirst}}">«« Eerste</a></li>
			<li class="page-item previous"><a class="page-link" href="?{{paging.qsPrevious}}">« Vorige</a></li>
		{% endif %}
		{% for key, item in paging.pages %}
			{% set class = 'page' %}
			{% if item == paging.current %}
				{% set class = ' active' %}
			{% endif %}

			<li class="page-item {{class}}"><a class="page-link" href="?{{paging.qsPages[key]}}">{{item}}</a></li>
		{% endfor %}
		{% if paging.current < paging.next %}
			<li class="page-item next"><a class="page-link" href="?{{paging.qsNext}}">Volgende »</a></li>
			<li class="page-item last"><a class="page-link" href="?{{paging.qsLast}}">Laatste »»</a></li>
		{% endif %}

		<li class="page-item disabled"><a class="page-link">{{paging.total}} items</a></li>

{#		<span class="itemsPerPage">
			<select id="l" name="l" form="filterForm" title="Items per pagina">
				{% for limit in paging.limits %}
				<option value="{{limit}}"{% if paging.limit == limit %} selected="selected"{% endif %}>{{limit}}</option>
				{% endfor %}
			</select>
		</span>#}
	</ul>
</nav>
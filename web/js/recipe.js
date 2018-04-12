$('document').ready(function() {
    /**
     * Make the ingredient list sortable
     */
    var el = document.getElementById('ingredients');
    Sortable.create(el,  {
        handle: '.move-ingredient',
        animation: 150,
    });

    /**
     * Add a new ingredient row to the form. Gives them a unique identifier by creating a time string containing the
     * minutes, seconds and milliseconds.
     */
    $('.add-ingredient').on('click', function() {
        var d = new Date();
        var dateString = d.getMinutes().toString()+d.getSeconds().toString()+d.getMilliseconds().toString();

        var container = document.createElement("div");
        container.innerHTML = ingredientRow.replace(/\[unique_identifier\]/g,'[' + dateString + ']');
        document.getElementById('ingredients').appendChild(container);
    });

    /**
     * delete the specific ingredient row
     */
    $('.delete-ingredient').on('click', function() {
        $(this).closest('.ingredient-row').remove();
    });
});

function addSortDataToIngredientrows()
{
    var ingredientRows = document.getElementsByClassName("ingredient-row");
    for(var i = 0; i < ingredientRows.length; i++)
    {
        var row = ingredientRows.item(i);
        var identifier = row.getAttribute('data-unique-identifier');

        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ingredient" + identifier + "[position]";
        input.value = i;

        row.appendChild(input);
    }
}
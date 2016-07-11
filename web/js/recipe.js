$('document').ready(function() {


    var el = document.getElementById('ingredients');
    Sortable.create(el,  {
        handle: '.glyphicon-move',
        animation: 150,
    });

    $('.add-ingredient').on('click', function() {
        document.getElementById('ingredients').innerHTML += ingredientRow;
    });
});

function createHiddenDiv() {
    var form = document.getElementById("recipe");
    var i = 1;

    $(".quantity:input").each(function(){
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ingredient-quantity-" + i;
        input.value = $(this).val();

        i++;
        form.appendChild(input);
    });

    var i = 1;
    $(".quantity_id").each(function(){
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ingredient-quantity-id-" + i;
        input.value = $(this).val();

        i++;
        form.appendChild(input);
    });

    var i = 1;
    $(".ingredient_id").each(function(){
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ingredient-ingredient-id-" + i;
        input.value = $(this).val();

        i++;
        form.appendChild(input);
    });
}
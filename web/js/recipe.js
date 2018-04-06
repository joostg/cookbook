$('document').ready(function() {
   /* $('#editor').wysiwyg().html($('#description').val());*/

    var el = document.getElementById('ingredients');
    Sortable.create(el,  {
        handle: '.move-ingredient',
        animation: 150,
    });

    $('.add-ingredient').on('click', function() {
        var container = document.createElement("div");
        container.innerHTML = ingredientRow;
        document.getElementById('ingredients').appendChild(container);
    });


    $('.delete-ingredient').on('click', function() {
        $(this).closest('.ingredient-row').remove();
    });
});

function createHiddenDiv() {
    /*$('#description').val($('#editor').html());*/

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
$('document').ready(function() {
   /* $('#editor').wysiwyg().html($('#description').val());*/

    var el = document.getElementById('ingredients');
    Sortable.create(el,  {
        handle: '.move-ingredient',
        animation: 150,
    });

    $('.add-ingredient').on('click', function() {
        var d = new Date();
        console.log(d.getMinutes().toString()+d.getSeconds().toString()+d.getMilliseconds().toString());return false;

        // TODO: replace array keys with above timestring
        var container = document.createElement("div");
        container.innerHTML = ingredientRow;
        document.getElementById('ingredients').appendChild(container);
    });


    $('.delete-ingredient').on('click', function() {
        $(this).closest('.ingredient-row').remove();
    });
});

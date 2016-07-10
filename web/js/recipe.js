$('document').ready(function() {


    var el = document.getElementById('ingredients');
    Sortable.create(el,  {
        handle: '.glyphicon-move',
        animation: 150,
        onSort: function (/**Event*/evt) {
            createHiddenDiv();
        },
    });

    $('.add-ingredient').on('click', function() {
    /*    var divs = document.getElementsByClassName("list-group-item");
        for(var i = 0; i < divs.length; i++){
            var child = divs[i];
            console.log(child.getValue('quantity'));
        }*/

        //console.log( $('.list-group-item').serialize() );

        $(".quantity:input").each(function(){
            var input = $(this); // This is the jquery object of the input, do what you will
            console.log(input.val())
        });
        
        
        /*var html = document.getElementById('ingredient-row').innerHTML;

         document.getElementById('ingredient-div').innerHTML += html;*/

    });
});

function createHiddenDiv() {
    var form = document.getElementById("recipe");
    var i = 1;

    $(".quantity:input").each(function(){
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ingredient" + i + "-quantity";
        input.value = $(this).val();

        i++;
        form.appendChild(input);
    });
}
var $collectionHolder;

var maxticket = 6;
var numberofticket = 1;

var $addTagButton = $('<button type="button" class="btn btn-info add_billet_button">Ajouter un billet</button>');
var $newLinkLi = $('<li></li>').append($addTagButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.billets');

    $collectionHolder.append($newLinkLi);


    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagButton.on('click', function(e) {
        numberofticket ++;
        if (numberofticket >= maxticket) { $('.add_billet_button').hide() }

        addTagForm($collectionHolder, $newLinkLi);

    });
});


function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $("#model_billet").html();

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="billet"></li>').append(newForm);

    $newLinkLi.before($newFormLi);

    addTagFormDeleteLink($newFormLi);
}


function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button class="btn btn-warning" type="button">Supprimer billet</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        numberofticket --;
        if (numberofticket < maxticket) { $('.add_billet_button').show() }

        $tagFormLi.remove();
    });
}


// bouton affichage tarifs

$( "#afficher_tarifs" ).click(function(e) {
    e.preventDefault();
    $(".infos_billets").slideToggle('3000', function () {
        if (document.getElementById('afficher_tarifs').innerHTML === 'Masquer les tarifs') {
            document.getElementById('afficher_tarifs').innerHTML = 'Afficher les tarifs';
        }
        else {
            document.getElementById('afficher_tarifs').innerHTML = 'Masquer les tarifs';
        }
    });
});
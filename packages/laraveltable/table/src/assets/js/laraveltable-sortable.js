var getUrl = window.location;
var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
var currentUrl = $(location).attr('href');
var currentUrlWithoutParams = document.location.protocol + "//" + document.location.hostname + document.location.pathname;
$(document).ready(function () {
    sortChange();
    pageChange();
    search();
});

function search() {
    $('#search-button').on('click',function(){
        var search = $('#search-input').val();
        var params = getparams();
        params.search = search;
        params.page = 1;
        window.location.href = currentUrlWithoutParams + generateGetParams(params);
    });
}

/**
 * Au moment du click sur la pagination
 * on va on va mettre a jour les parametres GET page
 */
function pageChange() {
    $('.a-pagination').on('click', function () {
        var page = $(this).attr('data-page');
        var params = getparams();
        params.page = page;
        window.location.href = currentUrlWithoutParams + generateGetParams(params);
    });
}

/**
 * Au moment du click sur une entete
 * on va on va mettre a jour les parametres GET
 */
function sortChange() {

    $('.sortable').on('click', function () {
        var columnClicked = $(this).attr('data-column');
        var params = getparams();
        //On se replace sur la premiere page
        params.page = 1;
        //on met a jour la column et la direction
        console.log(params.orderby);
        if (params.orderby != columnClicked) {
            params.orderby = columnClicked;
            params.direction = 'asc';
        } else {
            if (params.direction == null || params.direction == 'desc') {
                params.direction = 'asc';
            } else {
                params.direction = 'desc';
            }
        }
        window.location.href = currentUrlWithoutParams + generateGetParams(params);

    });
}
/**
 * recupere dans le GET le parametre preciser
 */
$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    }
    else {
        return results[1] || 0;
    }
};

/**
 * recupere nos parametres de config
 */

function getparams() {
    return {
        page: $.urlParam('page'),
        orderby: $.urlParam('orderby'),
        direction: $.urlParam('direction'),
        search: $.urlParam('search')
    };
}

/**
 * Genere un string GET
 */
function generateGetParams(array) {
    var paramsString = '?';
    $.each(array, function (key, value) {
        if(value != undefined && value != '' && value !=null&& value !=false) {
            paramsString += key + '=' + value + '&';
        }
    });
    return paramsString.slice(0,-1)
}


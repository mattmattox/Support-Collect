$(document).ready(function(){

    // when a 'search environment' button was clicked
    $(document).on('submit', '#search_environment_form', function(){

        // get search keywords
        var keywords = $(this).find(":input[name='keywords']").val();

        // get data from the api based on search keywords
        var json_url="api/environment/search_paging.php?s=" + keywords;
        searchenvironmentPage(json_url, keywords);

        // prevent whole page reload
        return false;
    });

    // when a 'page' button was clicked - for search
    $(document).on('click', '#environment_pagination_search li', function(){

        // get json url
        var json_url=$(this).find('a').attr('data-page');

        // get search keywords
        var keywords = $("#keywords").val();

        // show selected page
        searchenvironmentPage(json_url, keywords);

        // prevent whole page reload
        return false;
    });

});

function searchenvironmentPage(json_url, keywords){

    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(json_url, JSON.stringify({ jwt:jwt })).done(function(response){

        // template in environment.js
        readenvironmentTemplate(response, keywords);
        clearResponse();
    })

    // show login page on error
    .fail(function(result){
        showLoginPage();
        $('#response').html("<div class='alert alert-danger'>Please login as admin to search environment.</div>");
    });
}

$(document).ready(function(){

    // show home page
    $(document).on('click', '#read_users, #read_users_button', function(){
        showUsersFirstPage();
    });

    // when a 'page' button was clicked
    $(document).on('click', '#users_pagination_normal li', function(){
        // get json url
        var json_url=$(this).find('a').attr('data-page');
        
        // show list of products
        showUsersPage(json_url);
    });

});

function showUsersFirstPage(){
    showUsersPage("api/user/read_paging.php");
}

function showUsersPage(json_url){

    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post(json_url, JSON.stringify({ jwt:jwt })).done(function(response){
        
        // template in users.js
        readUsersTemplate(response, "");
        clearResponse();
    })

    // show login page on error
    .fail(function(result){
        showLoginPage();
        $('#response').html("<div class='alert alert-danger'>Please login as admin to access the users page.</div>");
    });
}
$(document).ready(function(){

    // logout the user
    $(document).on('click', '#logout', function(){
        showLoginPage();
        $('#response').html("<div class='alert alert-info'>You are logged out.</div>");
    });
});

// if the user is logged out
function showLoggedOutMenu(){
    // show login and sign up from navbar & hide logout button
    $("#login, #sign_up").show();
    $("#logout, #read_users").hide();
}
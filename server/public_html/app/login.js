$(document).ready(function(){

    // show login form
    $(document).on('click', '#login', function(){
        showLoginPage();
    });

    // trigger when login form is submitted
    $(document).on('submit', '#login_form', function(){

        // get form data
        var login_form=$(this);
        var form_data=JSON.stringify(login_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/login.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result){

                // store jwt to cookie
                setCookie("jwt", result.jwt, 1);

                // show home page & tell the user it was a successful login
                showHomePage();
                $('#response').html("<div class='alert alert-success'>Successful login.</div>");

            },
            error: function(xhr, resp, text){

                // on error, tell the user login has failed & empty the input boxes
                $('#response').html("<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>");
                login_form.find('input').val('');
            }
        });

        return false;
    });

});

// show login page
function showLoginPage(){

    // remove jwt
    setCookie("jwt", "", 1);

    // login page html
    var html = `
        <h2>Login</h2>
        <form id='login_form'>
            <div class='form-group'>
                <label for='email'>Email address</label>
                <input type='email' class='form-control' id='email' name='email' placeholder='Enter email'>
            </div>

            <div class='form-group'>
                <label for='password'>Password</label>
                <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
            </div>

            <button type='submit' class='btn btn-primary'>Login</button>
        </form>
        `;

    $('#content').html(html);
    clearResponse();
    showLoggedOutMenu();
}

// if the user is logged in
function showLoggedInMenu(access_level){
    // hide login and sign up from navbar & show logout button
    $("#login, #sign_up").hide();
    $("#Rancher").show();
    $("#logout").show();

    if(access_level=="Admin"){ $("#read_users").show(); }
}

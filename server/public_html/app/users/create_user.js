$(document).ready(function(){

    $(document).on('click', '#create_user_button', function(){

        var create_user_html=`

            <div class="row">
                <div class="col">
                    <button id='read_users_button' class='btn btn-primary float-right mb-3'>
                        Read Users
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <form id='create_user_form' action='#' method='post' border='0'>
                        <table class='table table-bordered table-hover'>
                            <tr>
                                <td>Firstname</td>
                                <td><input type='text' name='firstname' class='form-control' required /></td>
                            </tr>

                            <tr>
                                <td>Lastname</td>
                                <td><input type='text' name='lastname' class='form-control' required /></td>
                            </tr>

                            <tr>
                                <td>Email</td>
                                <td><input type='text' name='email' class='form-control' required /></td>
                            </tr>

                            <tr>
                                <td>Password</td>
                                <td><input type='password' name='password' class='form-control' required /></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <button type='submit' class='btn btn-primary'>
                                        <span class='glyphicon glyphicon-plus'></span> Create User
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </form>
                </div>
            </div>`;

        // inject to app
        $("#content").html(create_user_html);
    });

    $(document).on('submit', '#create_user_form', function(){

        // add jwt to object
        var jwt = getCookie('jwt');
        var form_object=$(this).serializeObject();
        form_object["jwt"] = jwt;

        // get form data
        var form_data=JSON.stringify(form_object);

        // submit form data to api
        $.ajax({
            url: "api/user/create.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // user was created, go back to users list
                showUsersFirstPage();
            },
            error: function(xhr, resp, text) {
                console.log(xhr, resp, text);
                showLoginPage();
                $('#response').html("<div class='alert alert-danger'>Please login as admin to create a user.</div>");
            }
        });

        return false;
    });

});
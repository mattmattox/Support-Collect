$(document).ready(function(){

    $(document).on('click', '.update_user_button', function(){

        // user id
        var id = $(this).attr('data-id');
        
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        
        $.post("api/user/read_one.php?id=" + id, JSON.stringify({ jwt:jwt })).done(function(result) {

            var update_user_html=`
                <div class="row">
                    <div class="col">
                        <button id='read_users_button' class='btn btn-primary float-right mb-3'>
                            Read Users
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <form id='update_user_form' action='#' method='post' border='0'>
                            <table class='table table-bordered table-hover'>
                                <tr>
                                    <td>Firstname</td>
                                    <td><input type='text' name='firstname' value='` + result.firstname + `' class='form-control' required /></td>
                                </tr>

                                <tr>
                                    <td>Lastname</td>
                                    <td><input type='text' name='lastname' value='` + result.lastname + `' class='form-control' required /></td>
                                </tr>

                                <tr>
                                    <td>Email</td>
                                    <td><input type='text' name='email' value='` + result.email + `' class='form-control' required /></td>
                                </tr>

                                <tr>
                                    <td>Password</td>
                                    <td><input type='password' name='password' class='form-control' /></td>
                                </tr>

                                <tr>
                                    <td><input type='hidden' name='id' value='` + result.id + `' required /></td>
                                    <td>
                                        <button type='submit' class='btn btn-primary'>
                                            Update User
                                        </button>
                                    </td>
                                </tr>

                            </table>
                        </form>
                    </div>
                </div>`;

            // inject to app
            $("#content").html(update_user_html);
        })

        // show login page on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login as admin to update user.</div>");
        });

    });

    $(document).on('submit', '#update_user_form', function(){

        // add jwt to object
        var jwt = getCookie('jwt');
        var form_object=$(this).serializeObject();
        form_object["jwt"] = jwt;

        // get form data
        var form_data=JSON.stringify(form_object);

        // submit form data to api
        $.ajax({
            url: "api/user/update.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result){
                // user was created, go back to users list
                showUsersFirstPage();
            },
            error: function(xhr, resp, text) {
                console.log(xhr, resp, text);
                showLoginPage();
                $('#response').html("<div class='alert alert-danger'>Please login as admin to update a user.</div>");
            }
        });

        return false;
    });

});
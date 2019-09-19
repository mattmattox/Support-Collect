$(document).ready(function(){

    $(document).on('click', '.update_environment_button', function(){

        // environment id
        var id = $(this).attr('data-id');

        // validate jwt to verify access
        var jwt = getCookie('jwt');

        $.post("api/environment/read_one.php?id=" + id, JSON.stringify({ jwt:jwt })).done(function(result) {

            var update_environment_html=`
                <div class="row">
                    <div class="col">
                        <button id='read_environments_button' class='btn btn-primary float-right mb-3'>
                            Read environments
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <form id='update_environment_form' action='#' method='post' border='0'>
                            <table class='table table-bordered table-hover'>
                                <tr>
                                    <td>name</td>
                                    <td><input type='text' name='name' value='` + result.name + `' class='form-control' required /></td>
                                </tr>

                                <tr>
                                    <td>endpoint</td>
                                    <td><input type='text' name='endpoint' value='` + result.endpoint + `' class='form-control' required /></td>
                                </tr>

                                <tr>
                                    <td>accesskey</td>
                                    <td><input type='text' name='accesskey' value='` + result.accesskey + `' class='form-control' required /></td>
                                </tr>

                                <tr>
                                    <td>Secret Key</td>
                                    <td><input type='password' name='secretkey' class='form-control' /></td>
                                </tr>

                                <tr>
                                    <td><input type='hidden' name='id' value='` + result.id + `' required /></td>
                                    <td>
                                        <button type='submit' class='btn btn-primary'>
                                            Update environment
                                        </button>
                                    </td>
                                </tr>

                            </table>
                        </form>
                    </div>
                </div>`;

            // inject to app
            $("#content").html(update_environment_html);
        })

        // show login page on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login as admin to update environment.</div>");
        });

    });

    $(document).on('submit', '#update_environment_form', function(){

        // add jwt to object
        var jwt = getCookie('jwt');
        var form_object=$(this).serializeObject();
        form_object["jwt"] = jwt;

        // get form data
        var form_data=JSON.stringify(form_object);

        // submit form data to api
        $.ajax({
            url: "api/environment/update.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result){
                // environment was created, go back to environments list
                showenvironmentsFirstPage();
            },
            error: function(xhr, resp, text) {
                console.log(xhr, resp, text);
                showLoginPage();
                $('#response').html("<div class='alert alert-danger'>Please login as admin to update a environment.</div>");
            }
        });

        return false;
    });

});

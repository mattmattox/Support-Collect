$(document).ready(function(){

    $(document).on('click', '.read_one_user_button', function(){
        
        // user id
        var id = $(this).attr('data-id');
        
        // validate jwt to verify access
        var jwt = getCookie('jwt');
        
        $.post("api/user/read_one.php?id=" + id, JSON.stringify({ jwt:jwt })).done(function(result) {
            
            // when clicked, it will show the user's list
            var read_one_user_html = `
                <button id="read_users_button" class="btn btn-primary float-right mb-3">
                    <span class="glyphicon glyphicon-list"></span> Read users
                </button>

                <!-- user data will be shown in this table -->
                <table class="table table-bordered table-hover">

                    <tr>
                        <td class="w-30-pct">Firstname</td>
                        <td class="w-70-pct">` + result.firstname + `</td>
                    </tr>

                    <tr>
                        <td>Lastname</td>
                        <td>` + result.lastname + `</td>
                    </tr>

                    <tr>
                        <td>Email</td>
                        <td>` + result.email + `</td>
                    </tr>

                    <tr>
                        <td>Access Level</td>
                        <td>` + result.access_level + `</td>
                    </tr>

                </table>`;

            // inject to app
            $("#content").html(read_one_user_html);
        })

        // show login page on error
        .fail(function(result){
            showLoginPage();
            $('#response').html("<div class='alert alert-danger'>Please login as admin to read one user.</div>");
        });


    });

});

$(document).ready(function(){

    // show sign up / registration form
    $(document).on('click', '#create_environment', function(){

        var html = `
            <h2>Register Rancher Environment</h2>
            <form id='create_environment_form'>
            	<div class="form-group">
            		<label for="name">Name</label>
            		<input type="text" class="form-control" name="name" id="name" required />
            	</div>

            	<div class="form-group">
            		<label for="endpoint">Endpoint</label>
            		<input type="text" class="form-control" name="endpoint" id="endpoint" required />
            	</div>

              <div class="form-group">
            		<label for="accesskey">Access Key</label>
            		<input type="text" class="form-control" name="accesskey" id="accesskey" required />
            	</div>

              <div class="form-group">
            		<label for="secretkey">Secret Key</label>
            		<input type="text" class="form-control" name="secretkey" id="secretkey" required />
            	</div>

                <button type='submit' class='btn btn-primary'>Register</button>
            </form>
            `;

        clearResponse();
        $('#content').html(html);
    });

    // trigger when registration form is submitted
    $(document).on('submit', '#create_environment_form', function(){

        // get form data
        var create_environment_form=$(this);
        var form_data=JSON.stringify(create_environment_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/environment/create.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // if response is a success, tell the user it was a successful sign up & empty the input boxes
                $('#response').html("<div class='alert alert-success'>Successful registered environment</div>");
                create_environment_form.find('input').val('');
            },
            error: function(xhr, resp, text){
                // on error, tell the user sign up failed
                $('#response').html("<div class='alert alert-danger'>Unable to register environment. Please contact admin.</div>");
            }
        });

        return false;
    });
});

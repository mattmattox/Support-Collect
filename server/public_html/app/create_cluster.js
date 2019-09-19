$(document).ready(function(){

    // show sign up / registration form
    $(document).on('click', '#create_cluster', function(){

        var html = `
            <h2>Register Cluster</h2>
            <form id='create_cluster_form'>
            	<div class="form-group">
            		<label for="name">Name</label>
            		<input type="text" class="form-control" name="name" id="name" required />
            	</div>

            	<div class="form-group">
            		<label for="environmentid">Environment ID</label>
            		<input type="text" class="form-control" name="environmentid" id="environmentid" required />
            	</div>

              <div class="form-group">
            		<label for="type">Type</label>
            		<input type="text" class="form-control" name="type" id="type" required />
            	</div>

                <button type='submit' class='btn btn-primary'>Register</button>
            </form>
            `;

        clearResponse();
        $('#content').html(html);
    });

    // trigger when registration form is submitted
    $(document).on('submit', '#create_cluster_form', function(){

        // get form data
        var create_cluster_form=$(this);
        var form_data=JSON.stringify(create_cluster_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/cluster/create.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // if response is a success, tell the user it was a successful sign up & empty the input boxes
                $('#response').html("<div class='alert alert-success'>Successful registered cluster</div>");
                create_cluster_form.find('input').val('');
            },
            error: function(xhr, resp, text){
                // on error, tell the user sign up failed
                $('#response').html("<div class='alert alert-danger'>Unable to register cluster. Please contact admin.</div>");
            }
        });

        return false;
    });
});

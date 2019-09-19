$(document).ready(function(){

    // show sign up / registration form
    $(document).on('click', '#create_node', function(){

        var html = `
            <h2>Register node</h2>
            <form id='create_node_form'>
            	<div class="form-group">
            		<label for="name">Name</label>
            		<input type="text" class="form-control" name="name" id="name" required />
            	</div>

              <div class="form-group">
            		<label for="environmentid">Environment ID</label>
            		<input type="text" class="form-control" name="environmentid" id="environmentid" required />
            	</div>

              <div class="form-group">
            		<label for="clusterid">Cluster ID</label>
            		<input type="text" class="form-control" name="clusterid" id="clusterid" required />
            	</div>

              <div class="form-group">
            		<label for="type">OS Family</label>
            		<input type="text" class="form-control" name="os_family" id="os_family" required />
            	</div>

              <div class="form-group">
            		<label for="type">OS Version</label>
            		<input type="text" class="form-control" name="os_version" id="os_version" required />
            	</div>

              <div class="form-group">
            		<label for="type">Docker Version</label>
            		<input type="text" class="form-control" name="docker_version" id="docker_version" required />
            	</div>

              <div class="form-group">
            		<label for="type">Role - etcd</label>
            		<input type="text" class="form-control" name="role_etcd" id="role_etcd" required />
            	</div>

              <div class="form-group">
            		<label for="type">Role - Control</label>
            		<input type="text" class="form-control" name="role_control" id="role_control" required />
            	</div>

              <div class="form-group">
            		<label for="type">Role - Worker</label>
            		<input type="text" class="form-control" name="role_worker" id="role_worker" required />
            	</div>

                <button type='submit' class='btn btn-primary'>Register</button>
            </form>
            `;

        clearResponse();
        $('#content').html(html);
    });

    // trigger when registration form is submitted
    $(document).on('submit', '#create_node_form', function(){

        // get form data
        var create_node_form=$(this);
        var form_data=JSON.stringify(create_node_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/node/create.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // if response is a success, tell the user it was a successful sign up & empty the input boxes
                $('#response').html("<div class='alert alert-success'>Successful registered node</div>");
                create_node_form.find('input').val('');
            },
            error: function(xhr, resp, text){
                // on error, tell the user sign up failed
                $('#response').html("<div class='alert alert-danger'>Unable to register node. Please contact admin.</div>");
            }
        });

        return false;
    });
});

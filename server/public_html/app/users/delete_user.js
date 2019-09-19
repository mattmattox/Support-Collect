$(document).ready(function(){

    // will run if the delete button was clicked
    $(document).on('click', '.delete_user_button', function(){

        // get the id
		var user_id = $(this).attr('data-id');

        // bootbox for good looking 'confirm pop up'
        bootbox.confirm({

		    message: "<h4>Are you sure?</h4>",
		    buttons: {
		        confirm: {
		            label: '<span class="glyphicon glyphicon-ok"></span> Yes',
		            className: 'btn-danger'
		        },
		        cancel: {
		            label: '<span class="glyphicon glyphicon-remove"></span> No',
		            className: 'btn-primary'
		        }
		    },
		    callback: function (result) {

		        if(result==true){

                    // validate jwt to verify access
                    var jwt = getCookie('jwt');
                    
                    $.ajax({
                        url: "api/user/delete.php", // url where to submit the request
                        type : "POST", // type of action POST || GET
                        dataType : 'json', // data type
                        data : JSON.stringify({ id: user_id, jwt: jwt }), // post data || get data
                        success : function(result) {
                            showUsersFirstPage();
                        },
                        error: function(xhr, resp, text) {
                            console.log(xhr, resp, text);
                            showLoginPage();
                            $('#response').html("<div class='alert alert-danger'>Please login as admin to delete user.</div>");
                        }
                    });

                }
            }
        });
    });

});

function readUsersTemplate(response, keywords){

    // html for listing users
    read_users_html=``;

    // search users form
    read_users_html+=`
        <div class="row">
            <div class="col">
                <form id='search_users_form' action='#' method='post'>
                    <div class="input-group mb-3">
                        <input type="text" name="keywords" id="keywords" class="form-control" placeholder="Type keyword here..." value="` + keywords + `">
                        <div class="input-group-append">
                            <button type='submit' class='btn btn-primary' type='button'>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- when clicked, it will load the create user form -->
            <div class="col">
                <div id='create_user_button' class='btn btn-primary float-right m-b-15px create-user-button'>
                    <span class='glyphicon glyphicon-plus'></span> Create user
                </div>
            </div>
        </div>
    `;

    // tell the user if no users found
    if(response.message=="No users found."){
        read_users_html+=`<div class='overflow-hidden w-100-pct'>
            <div class='alert alert-danger'>No users found.</div>
        </div>`;
    }

    // display users if they exist
    else{

        // start table
        read_users_html+=`<table class='table table-bordered table-hover'>
            <tr>
                <th class='w-25-pct'>Name</th>
                <th class='w-10-pct'>Email</th>
                <th class='w-15-pct'>Access Level</th>
                <th class='w-25-pct text-align-center'>Action</th>
            </tr>`;

        // loop through returned list of data
        $.each(response.records, function(key, val) {

            // creating new table row per record
            read_users_html+=`
                <tr>
                    <td>` + val.firstname + ` ` + val.lastname + `</td>
                    <td>` + val.email + `</td>
                    <td>` + val.access_level + `</td>
                    <td>

                        <!-- read user button -->
                        <button class='btn btn-primary m-r-10px read_one_user_button' data-id='` + val.id + `'>
                            <span class='glyphicon glyphicon-eye-open'></span> Read
                        </button>

                        <!-- edit button -->
                        <button class='btn btn-info edit-btn m-r-10px update_user_button' data-id='` + val.id + `'>
                            <span class='glyphicon glyphicon-edit'></span> Edit
                        </button>

                        <!-- delete button -->
                        <button class='btn btn-danger delete_user_button' data-id='` + val.id + `'>
                            <span class='glyphicon glyphicon-remove'></span> Delete
                        </button>

                    </td>
                </tr>`;

        });

        //end table
        read_users_html+="</table>";

        // paging
        var pagination_id = keywords==""
                            ? "users_pagination_normal"
                            : "users_pagination_search";
        read_users_html+=getPagination(pagination_id, response);
    }

    // inject to app
    $('#content').html(read_users_html);

}

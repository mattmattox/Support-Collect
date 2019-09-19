$(document).ready(function(){

    // try to show the home page
    showHomePage();

    // show home page
    $(document).on('click', '#home', function(){
        showHomePage();
        clearResponse();
    });

});

// show home page
function showHomePage(){

    // validate jwt to verify access
    var jwt = getCookie('jwt');

    $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
        
        // if valid, show homepage
        var html = `
            <div class="card">
                <div class="card-header">Welcome to Home!</div>
                <div class="card-body">
                    <h5 class="card-title">You are logged in.</h5>
                    <p class="card-text">You won't be able to access the home and account pages if you are not logged in.</p>
                </div>
            </div>
            `;

        $('#content').html(html);
        showLoggedInMenu(result.data.access_level);
    })

    // show login page on error
    .fail(function(result){
        showLoginPage();
        $('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
    });
}

// remove any prompt messages
function clearResponse(){
    $('#response').html('');
}

// pagination
function getPagination(pagination_id, response){

    var pagination_html="";
    pagination_html+="<ul class='pagination' id='" + pagination_id + "' float-left margin-zero padding-bottom-2em'>";

        // first page
        if(response.paging.first!=""){
            pagination_html+="<li class='page-item'><a class='page-link' data-page='" + response.paging.first + "'>First Page</a></li>";
        }

        // loop through pages
        $.each(response.paging.pages, function(key, val) {
            var active_page=val.current_page=="yes" ? "active" : "";
            pagination_html+="<li class='page-item " + active_page + "'><a class='page-link' data-page='" + val.url + "'>" + val.page + "</a></li>";
        });

        // last page
        if(response.paging.last!=""){
            pagination_html+="<li class='page-item'><a class='page-link' data-page='" + response.paging.last + "'>Last Page</a></li>";
        }
    pagination_html+="</ul>";

    return pagination_html;
}

// function to make form values to json format
$.fn.serializeObject = function(){

    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

// function to set cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// get or read cookie
function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' '){
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
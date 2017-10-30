var host = window.location.host;
if(host=="demo.com")
    baseUrl = "http://demo.com/";
else
    baseUrl = "http://ec2-34-236-61-80.compute-1.amazonaws.com/";
//baseUrl = "http://stratuscm-live.us-west-2.elasticbeanstalk.com/";

$(document).ready(function() {

	var u_id 			= localStorage.getItem('u_id');
	var u_first_name 	= localStorage.getItem('u_first_name');
	var u_last_name 	= localStorage.getItem('u_last_name');
	var role 			= localStorage.getItem('u_role');
	$("#my_profile_link").html('<a href="'+baseUrl+'dashboard/users/'+u_id+'">Profile</a>');
	$("#login_username").html(u_first_name+' '+u_last_name+'<span class=" fa fa-angle-down"></span>');

	if(role == 'owner'){
		// alert('admin');
        $('.hide_owner').remove();
    }
    else if(role == 'admin'){
        $('.hide_admin').remove();
    }
    else {
        // alert(role);
        $('.hide_owner_user').remove();
        $('.hide_user').remove();
    }

    if(role == 'owner' || role == 'user'){
        $('.hide_owner_user').remove();
    }

	$('.side-navigation').show();

	// $('.wysihtml5').wysihtml5();
    // $('.summernote').summernote({
    //     height: 500,                 // set editor height
    //     minHeight: null,             // set minimum height of editor
    //     maxHeight: null,             // set maximum height of editor
    //     focus: true                 // set focus to editable area after initializing summernote
    // });


    	var url = window.location.pathname,
        // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
        urlRegExp = new RegExp(url.replace(/\/$/,'') + "$");
        // now grab every link from the navigation
        $('.nav-pills li a').each(function(){
            // and test its normalized href against the url pathname regexp
            // if(urlRegExp.test(this.href.replace(/\/$/,''))){
            //     $(this).parent().addClass('active');
            //     $(this).parent().siblings().removeClass('active');
            // }

            if(window.location.href.indexOf("users") > -1) {
                $('.nav-pills').find('li:nth-child(4)').addClass('active');
            }
            else if(window.location.href.indexOf("projects") > -1) {
                $('.nav-pills').find('li:nth-child(2)').addClass('active');
            }
            else if(window.location.href.indexOf("firms") > -1) {
                $('.nav-pills').find('li:nth-child(5)').addClass('active');
            }
            else if(window.location.href.indexOf("improvement") > -1) {
                $('.nav-pills').find('li:nth-child(6)').addClass('active');
            }
            else if(window.location.href.indexOf("company_type") > -1) {
                $('.nav-pills').find('li:nth-child(7)').addClass('active');
            }
            else if(window.location.href.indexOf("currency") > -1) {
                $('.nav-pills').find('li:nth-child(8)').addClass('active');
            }

        });



});
    
    function ReplaceNumberWithCommas(yourNumber) {
        if(yourNumber != null){
            //Seperates the components of the number
            var components = yourNumber.toString().split(".");
            //Comma-fies the first part
            components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //Combines the two sections
            return components.join(".");    
        }
    }

    $("#logout").click(function(event) {
        event.preventDefault();
        localStorage.clear();
        window.location.href = baseUrl;
    });

@extends('layouts.full_width')

@section('content')

	<div class="col-sm-12">
	 	<section class="content mb-5" id="group">

		    <h1 class="mb-5"><b>
                {{ $user->name }}
            </b></h1>

	    	<div class="container-fluid mb-4">
	    		<div class="row mb-4">
	    			<div class="col-md-3">
				    	<div class="img_group_default">
							<img src="{{ url($user->avatar) }}" id="avatar">
						</div>
					</div>
					<div class="col-md-5">
                        <div class="d-flex flex-row border border-top-0 border-left-0 border-right-0 py-1">
                            <div class="text-left w-50">
                                <b>{{ "Name" }}</b>
                            </div>
                            {{ $user->name }}
                        </div>
						<div class="d-flex flex-row border border-top-0 border-left-0 border-right-0 py-1">
                            <div class="text-left w-50">
                                <b>{{ t("Kobotoolbox Account:") }}</b>
                            </div>
                            {{$user->kobo_id}}
                        </div>
						<div class="d-flex flex-row border border-top-0 border-left-0 border-right-0 py-1">
                            <div class="text-left w-50">
                                <b>{{ t("Number of Projects:") }}</b>
                            </div>
                            {{$user->projects->count()}}
                        </div>
                        @can('update', $user)
                            <div class="d-flex flex-row border border-top-0 border-left-0 border-right-0 py-1">
                                <div class="text-left w-50">
                                    <b>{{ t("Email:") }}</b>
                                </div>
                                {{$user->email}}
                            </div>
                        @endcan
						<div class="d-flex flex-row border border-top-0 border-left-0 border-right-0 py-1">
                            <div class="text-left w-50">
                                <b>{{ t("Created:") }}</b>
                            </div>
                            {{$user->created_at->diffForHumans()}}
                        </div>
					</div>
				</div>
                @can('update', $user)
                    <a href=" {{ route('users.edit', $user->slug) }}" class="btn btn-primary">
                        {{ t("Edit Details") }}
                    </a>
                    <a href=" {{ route('users.password.edit', $user->slug) }}" class="btn btn-primary">
                        {{ t("Change Your Password") }}
                    </a>
                @endcan
			</div>

            @include('users.projects')

	</div>


@endsection

@section('script')
<script type="text/javascript">


//check file image validation

// jQuery(document).ready(function(){
// 	jQuery("#success").hide();
// 	jQuery("#error").hide();

// 	jQuery("#Upload").click(function(event){
// 		event.preventDefault();
// 		var form = document.getElementById('upload_image');
// 		var form_data = new FormData(form);

//         $.ajax({
// 	        url : '{{$user->id}}/upload',
// 	        type : 'POST',
// 	        data : form_data,
// 	        processData: false,
// 	        contentType: false,
// 	        success : function(result){
// 	        	var type = result.type;
// 	        	var message = result.message;
// 	        	if(type=='empty'){
// 	        		jQuery("#success").hide();
// 	        		jQuery('#error').show();
// 	        		jQuery("#error").html(message);
// 	    			//console.log(message);
// 	    		} else if (type=='error'){
// 	    			jQuery("#success").hide();
// 	    			jQuery('#error').show();
// 	    			jQuery("#error").html(message);
// 	    			//console.log(message);
// 	    		} else if (type=='success'){
// 	    			jQuery("#error").hide();
// 	    			jQuery('#success').show();
// 	    			jQuery("#success").html(message);
// 	   				var url = window.location.origin+'/'+result.image_path;
// 	    			jQuery("#image").attr('src', url);
// 	    			jQuery("#avatar").attr('src', url);
// 	    			jQuery("#avatar_card").attr('src', url);

// 	    		}
// 			}
// 		});
// 	});
// });

// //validation profile details
// jQuery(document).ready(function(){
// 	jQuery('#validate_danger').hide();
// 	jQuery('#validate_success').hide();
// 	jQuery("#prof_details").click(function(event){
// 		event.preventDefault();
// 		var form = document.getElementById('profile_details');
// 		var form_data = new FormData(form);

// 		$.ajax({
// 	        url : '{{$user->id}}/validateDetails',
// 	        type : 'POST',
// 	        data : form_data,
// 	        processData: false,
// 	        contentType: false,
// 	        success : function(result){
// 	        	if(!result.success){
// 	        		jQuery('#validate_danger').show();
// 	        		jQuery('#validate_success').hide();
// 	        		jQuery("#validate_danger").html(result.message);
// 				} else {
// 					jQuery('#validate_success').show();
// 					jQuery('#validate_danger').hide();
// 	        		jQuery("#validate_success").html('Account updated');
// 	        		location.reload();
// 				}
// 	        }

// 	    });
// 	});
// });

// //validation password
// jQuery(document).ready(function(){
// 	jQuery('#password_danger').hide();
// 	jQuery('#password_success').hide();

// 	jQuery("#change_password").click(function(event){
// 		event.preventDefault();
// 		var form = document.getElementById('password');
// 		var form_data = new FormData(form);

// 		$.ajax({
// 	        url : '{{$user->id}}/changePassword',
// 	        type : 'POST',
// 	        data : form_data,
// 	        processData: false,
// 	        contentType: false,
// 	        success : function(result){

// 	        	var type = result.type;
// 	        	var message = result.message;
// 	        	console.log(result);

// 	        	if(type == 'error'){
// 	        		jQuery('#password_danger').show();
// 	        		jQuery('#password_success').hide();
// 	        		jQuery("#password_danger").html(message);
// 				} else {
// 					jQuery('#password_success').show();
// 					jQuery('#password_danger').hide();
// 					jQuery("#password_success").html('New pasword updated');

// 	        		//location.reload();

// 				}
// 	        }

// 	    });
// 	});
// });

// //Delete user
// jQuery(document).ready(function(){
// 	jQuery("#delete_profile").click(function(event){
// 		event.preventDefault();

// 		if (confirm('Are you sure to delete your profile {{$user->username}}?')) {
// 		    $.ajax({
// 	        url : '{{$user->id}}/deleteProfile',
// 	        type : 'POST',
// 	        processData: false,
// 	        contentType: false,
// 	        success : function(result){
// 		        	if(result.type=="success")
// 		        	{
// 		        		window.location.replace("/en/home");
// 		        	}
// 		        }
// 		    });
// 		}
// 	});
// });

// //Kobotoolbox account
// jQuery(document).ready(function(){
// 	jQuery("#kobo-user").click(function(event){
// 		event.preventDefault();
// 		var form = document.getElementById('kobo');
// 		var form_data = new FormData(form);

// 		$.ajax({
// 	        url : '{{$user->id}}/kobo-user',
// 	        type : 'POST',
// 	        data : form_data,
// 	        processData: false,
// 	        contentType: false,
// 	        success : function(result){

// 	        	var type = result.type;
// 	        	var message = result.message;
// 	        	jQuery("#current_account").html(message);

// 	        }
// 	    });
// 	});
// });
</script>
@endsection

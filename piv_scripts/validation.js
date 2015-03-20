jQuery(document).ready(function(){
	var fullpath=window.location.href;
	var split_path=fullpath.split("/");
	if(split_path[4]=='' || split_path[4].match(/doing/g)){
		var site_url='http://'+window.location.hostname;
	}else{
		var site_url='http://'+window.location.hostname+"/"+split_path[3];
	}
	
	jQuery("#verify_data").on('submit',function(e) {
		


		if(jQuery("#identity_type").val()==''){
			jQuery("#identity_type").focus();
			jQuery(".err_identity_type").text("Please Select Photo Id Type");
			jQuery("#identity_type").css("border","1px solid red");
			return false;
		}
		else{
			jQuery(".err_identity_type").text("");
			jQuery("#identity_type").css("border","1px solid #dfdfdf");	
		}
		if(jQuery("#identity_type").val()=='driver_license'){
			if(jQuery("#region").val()==''){
				jQuery("#region").focus();
				jQuery(".err_region").text("Please Select Region");
				jQuery("#region").css("border","1px solid red");
				return false;
			}
			else{
				jQuery(".err_region").text("");
				jQuery("#region").css("border","1px solid #dfdfdf");	
			}
		}	

		e.preventDefault();



		

		jQuery(".loader_image").css("display","block");
		jQuery("body").css("opacity",'0.5');

		jQuery.ajax({
			url:site_url+'/wp-admin/admin-ajax.php?action=photo_id_verify',
			type:'post',
			//data:postdata,/
			data: new FormData(this), 
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false, 
			success:function(result_piv){
				jQuery(".loader_image").css("display","none");
				jQuery("body").css("opacity",'1');

				jQuery(".ivs-form1").slideUp("slow");
				jQuery(".result").slideDown("slow");
				jQuery(".result").html(result_piv);
			}
		})

	});
	jQuery("#identity_type").change(function(){

		if(jQuery(this).val()=='driver_license'){
			jQuery("#region_block").slideDown("slow");
		}else{
			jQuery("#region_block").slideUp("slow");
		}
	});


	jQuery("#photo").change(function() {
		//jQuery(".loader_image").css("display","block");
		//jQuery("body").css("opacity",'0.5');

		var file = this.files[0];
		if(file.size>2097152){
			alert("Image size is more than 2MB Please choose another image");
			return false;
		}
		var imagefile = file.type;
		var match= ["image/jpeg","image/png","image/jpg"];
		if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
			jQuery('#previewing').attr('src','noimage.png');
			jQuery("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
			return false;
		}else{
			var reader = new FileReader();
			reader.onload = imageIsLoaded;
			reader.readAsDataURL(this.files[0]);

		}
	});

	function imageIsLoaded(e) {
		jQuery("#file").css("color","green");
		jQuery('#image_preview').css("display", "block");
		jQuery('#previewing').attr('src', e.target.result);
		jQuery('#previewing').attr('width', '250px');
		jQuery('#previewing').attr('height', '230px');
	};

});
<img src="<?php echo plugins_url('/images/loader.gif' , __FILE__)?>" style="display:none" class="loader_image">

<?php
if(count($configuration)>0){
?>
<div class="main-form-plg photoid">
	<div class="ivs-form1">
	
		<div class="ivs-message alert-static"></div>
		<div id="preview">
			<img id="previewing" src="<?php echo plugins_url("noimage.png",__FILE__)?>" />
		</div>
		<form id="verify_data" action="" method="post" enctype="multipart/form-data">
				
			<div class="ivs-inner-form">
				<div class="ivs-field">
					<label for="country">Photo ID Document Type</label>
					<div class="ivs-input">
						<select id="identity_type" name="identity_type"  class="valid">
							<option value="">Select Photo Id Document Type</option>	
							<option value="driver_license">Driving Licence</option>
							<option value="passport">Passport</option>
							
						</select>
					
						<span class="err_identity_type err"></span>
					</div>
						
					<div class="clear"></div>
				</div>
				<div class="ivs-field" id="region_block" style="display:none">
					<label for="country">Region</label>
					<div class="ivs-input">
						<select id="region" name="region"  class="valid">
							<option value="">Select Region</option>	
							<option value="USA">USA</option>
							<option value="CANADA">CANADA</option>
							<option value="AUSTRALIA">AUSTRALIA</option>
							<option value="SOUTH AMERICA">SOUTH AMERICA</option>
							<option value="ASIA">ASIA</option>
							<option value="AFRICA">AFRICA</option>
								
						</select>
					
						<span class="err_region err"></span>
					</div>
						
					<div class="clear"></div>
				</div>

				<div class="ivs-field">
					<label for="driving_license">Local file / URL  </label>
					<div class="ivs-input">	
						 <input type="text" name="photoid_url" id="photoid_url" >
						
					</div>		
					<div class="clear"></div>
				</div>

				<div class="ivs-field">
					
					<div class="ivs-input">	
						 <div id="share-bottom">
	                        <button type="button" class="photo-button" onclick="document.getElementById('photo').click();"><p> Browse </p></button>
	                        <div id="upload-wrap">
	                            <input type="file" name="photo" id="photo"/>
	                        </div>
	                        
	                    </div>
						<span class="err err_photoid_url"></span>
						<span style="font-size:11px;">(* Image Size Should be less than 2MB Size)</span>
					</div>		
					<div class="clear"></div>
				</div>

			
				<div class="ivs-field ivs-btn">	
					<button type="submit" id="verify_piv" >VERIFY</button>
						<!-- <input type="submit"  class="" value="VERIFY" id="verify_piv" /> -->
					<div class="clear"></div>
				</div>	

			</div>
		</form>
		<div class="clear"></div>
<!-- 		<div id="preview">
			<img id="previewing" src="<?php echo plugins_url("noimage.png",__FILE__)?>" />
		</div> -->
</div>
<div id="message"></div>
	</div>
<?php
}else{

	echo "API Credentials Not Yet Configured";
}
?>


<div class="result" style="float:left">
</div>

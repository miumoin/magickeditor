<?php
	if(isset($_REQUEST['magick_image'])){

		preview_magick_image();
	}
	elseif(isset($_REQUEST['magick_background'])) {
		
		magick_transparent_image();
	}
	elseif(isset($_REQUEST['load_image'])) {
		
		magick_load_image();
	}
	elseif(isset($_REQUEST['process'])) {
		
		if($_REQUEST['process']=='upload_background_image') process_upload_background_image();
		elseif($_REQUEST['process']=='upload_magick_image') process_upload_magick_image();
		elseif($_REQUEST['process']=='upload_magick_font') process_upload_magick_font();
		die();
	}

	function initiate_magickeditor($json) {
		$data = json_decode($json);
?>
	
	<div class="row">
		<div class="col-md-8">
			
			<div class="panel panel-primary">
				<div class="panel-heading"><h4>Editor</h4></div>
				
				<div class="panel-body">
					<div id="magicalPart" style="position: relative;">
						<img src="<?php echo MAGICKBASE; ?>/?magick_background" id="magickArea" width="600" height="800"/>
						
						<div id="magickElements" style="position: absolute; top: 0px; left: 0px;"></div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">			
			
			<div class="panel panel-primary">
				<div class="panel-heading"><h4>Add New</h4></div>
				
				<div class="panel-body">
					<a href="#" onclick="magick_new_layer('image'); return false;" class="btn btn-info">Image layer</a>
					<a href="#" onclick="magick_new_layer('text'); return false;" class="btn btn-success">Text layer</a>
					
					<br>
					<a href="#" onclick="javascript:document.getElementById('uploadBackgroundImage').click(); return false;"><strong>Upload Background</strong></a>
													
					<input id="uploadBackgroundImage" type="file" accept="image/*" name="background_image" 
						onchange="javascript:upload_background_image(this, 
						'<?php echo MAGICKBASE; ?>?process=upload_background_image', 
						'<?php echo MAGICKBASE; ?>');"
						style="display:none"/>
					<span id="backgroundImageUploadStatus"></span>
													
				</div>
			</div>
			
			<div id="magick_layer_edit_image" class="panel panel-primary" style="display:none">	
				<div class="panel-heading">
					<h4>Image Layer</h4>
				</div>
				
				<div class="panel-body">
					
					<div class="form-group">
						
						<label><a href="#" onclick="javascript:document.getElementById('uploadMagickImage').click(); return false;" class="btn btn-info"><strong>Upload Image</strong></a></label>
														
						<input id="uploadMagickImage" type="file" accept="image/*" name="magick_image" 
							onchange="javascript:upload_magick_image(this, 
							'<?php echo MAGICKBASE; ?>?process=upload_magick_image', 
							'<?php echo MAGICKBASE; ?>');"
							style="display:none"/>
						<span id="magickImageUploadStatus"></span><br>
						
						<label>Rotation</label>
						<div id="image_rotation_slider"></div>
						
						<label>Bending</label>
						<div id="image_bending_slider"></div>

						<br>
						<label><input type="checkbox" id="image_bending_anticlock" onchange="javascript: if(this.checked==true) { magick_update_element('b_anticlock', 1, 'image') } else { magick_update_element('b_anticlock', 0, 'image') }"> Anti-clockwise bending</label>
						<br>
						<label><input type="checkbox" id="image_bending_manual_radius" onchange="javascript: if(this.checked==true) { magick_update_element('b_type', 'manual', 'image'); document.getElementById('image_manual_radius').style.display='block'; } else { magick_update_element('b_type', 'auto', 'image'); document.getElementById('image_manual_radius').style.display='none'; }"> Manual Radius</label>

						<div id="image_manual_radius" style="display:none">
							<label>Top Radius (pixel)</label>
							<input type="text" id="magick_image_top_radius" class="form-control" onchange="magick_update_element('b_top_rad', this.value, 'text')">

							<label>Bottom Radius (pixel)</label>
							<input type="text" id="magick_image_bottom_radius" class="form-control" onchange="magick_update_element('b_bottom_rad', this.value, 'text')">
						</div>

					</div>
					
				</div>
			</div>
			
			<div id="magick_layer_edit_text"class="panel panel-primary" style="display:none">	
				<div class="panel-heading">
					<h4>Text Layer</h4>
				</div>
				
				<div class="panel-body">
					
					<div class="form-group">
						<label>Text</label>
						<input type="text" name="text" class="form-control" id="magick_text" onchange="magick_update_element( 'text', this.value, 'text')"><br>
						
						<label><a href="#" onclick="javascript:document.getElementById('uploadMagickFont').click(); return false;" class="btn btn-info"><strong>Upload Font</strong></a></label>
														
						<input id="uploadMagickFont" type="file" name="magick_font" 
							onchange="javascript:upload_magick_font(this, 
							'<?php echo MAGICKBASE; ?>?process=upload_magick_font', 
							'<?php echo MAGICKBASE; ?>');"
							style="display:none"/>
						<span id="magickFontUploadStatus"></span><br>
						
						<label>Stroke Color</label>
						<input type="text" name="stroke" class="form-control colorpicker" id="magick_text_stroke" onchange="magick_update_element( 'stroke', this.value, 'text')"><br>
						
						<label>Font Color</label>
						<input type="text" name="color" class="form-control colorpicker" id="magick_text_color" onchange="magick_update_element( 'color', this.value, 'text')"><br>
						
						<h3>Rotate</h3>
						<label>Degree of Rotation</label>
						<div id="text_rotation_slider"></div>
						
						<h3>Bend</h3>
						<label>Degree of Bending</label>
						<div id="text_bending_slider"></div>

						<br>
						<label><input type="checkbox" id="text_bending_anticlock" onchange="javascript: if(this.checked==true) { magick_update_element('b_anticlock', 1, 'text') } else { magick_update_element('b_anticlock', 0, 'text') }"> Anti-clockwise bending</label>
						<br>
						<label><input type="checkbox" id="text_bending_manual_radius" onchange="javascript: if(this.checked==true) { magick_update_element('b_type', 'manual', 'text'); document.getElementById('text_manual_radius').style.display='block'; } else { magick_update_element('b_type', 'auto', 'text'); document.getElementById('text_manual_radius').style.display='none'; }"> Manual Radius</label>

						<div id="text_manual_radius" style="display:none">
							<label>Top Radius (pixel)</label>
							<input type="text" id="magick_text_top_radius" class="form-control" onchange="magick_update_element('b_top_rad', this.value, 'text')">

							<label>Bottom Radius (pixel)</label>
							<input type="text" id="magick_text_bottom_radius" class="form-control" onchange="magick_update_element('b_bottom_rad', this.value, 'text')">
						</div>
						
					</div>
					
				</div>
			</div>
			
			<div class="panel panel-primary">	
				<div class="panel-heading">
					<h4>Added Layers</h4>
				</div>
			
				<div class="panel-body">
					<table id="magickList"></table>
				</div>
			</div>
		</div>
	</div>
	
	

	<textarea id="magickData" name="magickData" style="display:none"><?php echo $json; ?></textarea>
	<div id="magickNow" style="display:none"></div>
	<script> magickShow(); </script>
	
<?php
	}

	function preview_magick_image() {

		$layer = json_decode(urldecode($_REQUEST['magick_image']));

		if(($layer->type=='image') && (file_exists($layer->file))) {

			//load the image into variable			
			$im = load_magick_image($layer->file);
		}
		elseif($layer->type=='text') {

			//load the font and create text image into a variable
			$im = load_magick_text( $layer->text, $layer->font, 40, $layer->color, $layer->stroke, 0);			
		}
		
		$im->setImageMatte( TRUE );

		
		if( $layer->b != 0 ) {		

			if((isset($layer->b_anticlock)) && ($layer->b_anticlock == 1)) {

				$im->rotateImage(new ImagickPixel('none'), 180);		
				$layer->r = $layer->r+180;
			}
			
			if((isset($layer->b_type)) && ($layer->b_type == 'manual')) {

				$bend_degrees = array($layer->b, 0, $layer->b_top_rad, $layer->b_bottom_rad);
			}
			else $bend_degrees = array($layer->b);

			$im->distortImage(Imagick::DISTORTION_ARC, $bend_degrees, TRUE);
		}

		
		if( $layer->r != 0 ) {			
			
			$im->rotateImage(new ImagickPixel('none'), $layer->r);
		}

		//display the image whether it is text or image
		header("Content-Type: image/jpeg");		
		echo $im;
	}

	function load_magick_image($file) {

		$im = new Imagick();		
		$im->readImage($file);	
		return $im;
	}

	function load_magick_text( $text, $font, $size, $color, $stroke, $shade ) {

		/* Create a new canvas object and a white image */
		$im = new Imagick();
		
		/* Create imagickdraw object */
		$draw = new ImagickDraw();

		/* Set font size and font */
		$draw->setFontSize($size);
		//
		if(is_file($font)) $draw->setFont($font);
		if(trim($stroke) != "") $draw->setStrokeColor( new ImagickPixel($stroke) );
		if(trim($color)!="") $draw->setFillColor( new ImagickPixel($color) );
		
		$textmetric = $im->queryFontMetrics($draw, $text);
		$baseline = $textmetric['boundingBox']['y2'];		

		/* Annotate some text */
		$draw->annotation(0, $baseline, $text);
		
		$textwidth = $textmetric['textWidth'] + 2 * $textmetric['boundingBox']['x1'];
		$textheight = $textmetric['textHeight'] + $textmetric['descender'];
		
		$im->newImage($textwidth, $textheight, (new ImagickPixel('transparent')));
		
		/* Draw the ImagickDraw on to the canvas */		
		$im->drawImage($draw);


		/* Set the format to PNG */
		$im->setImageFormat('png');

		return $im;
	}
	
	function distort_image( $file, $degree ) {

		$textOnly = new Imagick($file);
		$textOnly->setImageMatte( TRUE );
		$textOnly->distortImage(Imagick::DISTORTION_ARC, array($degree), FALSE);
		return $textOnly;
	}

	function rotate_image( $file, $degree ) {
		
		$imagick = new Imagick($file);
		$imagick->rotateImage(new ImagickPixel('none'), $degree);
		$imagick->writeImage($file);
		$imagick->clear();
		$imagick->destroy();
	}
	
	function magick_transparent_image() {
		
		$im = new Imagick();
		$im->newImage(100, 100, (new ImagickPixel('transparent')));
		/* Set the format to PNG */
		$im->setImageFormat('png');
		
		header("Content-Type: image/png");		
		echo $im;
	}
	
	function magick_load_image() {
		
		if( !file_exists( $_REQUEST['load_image'] ) ) $_REQUEST['load_image'] = MAGICKDIRECTORY.'tshirt-default.jpg';	
		$im = new Imagick($_REQUEST['load_image']);		
		header("Content-Type: image/png");
		echo $im;
	}
	
	function process_upload_background_image() {		
		
		$_FILES['image']=$_FILES['photos'];
		$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		$path=upload_the_image(200 * 1024, "_background", $valid_exts);
		echo $path;
	}
	
	function process_upload_magick_image() {		
		
		$_FILES['image']=$_FILES['photos'];
		$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		$path=upload_the_image(200 * 1024, "_image", $valid_exts);
		echo $path;
	}
	
	function process_upload_magick_font() {
		
		$_FILES['image']=$_FILES['photos'];
		//var_dump($_FILES['image']);
		$valid_exts = array('ttf'); // valid extensions
		$path=upload_the_image(200 * 1024, "_font", $valid_exts);
		echo $path;
	}
	
	function upload_the_image($max_size, $prefix, $valid_exts) {		
		
		$path = MAGICKDIRECTORY; // upload directory
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			if( ! empty($_FILES['image']) ) {
				// get uploaded file extension
				$ext = strtolower(pathinfo($_FILES['image']['name'][0], PATHINFO_EXTENSION));
				// looking for format and size validity
				if (in_array($ext, $valid_exts) AND $_FILES['image']['size'][0] < $max_size*50) {
					$path = $path . uniqid(). $prefix.rand(0,100).'.' .$ext;
					
					
					// move uploaded file from temp to uploads directory
					if (move_uploaded_file($_FILES['image']['tmp_name'][0], $path)) {   
						return $path;
					} else echo $_FILES['image']['tmp_name'][0];
				} else {
					echo 'Invalid file!';
				}
			} else {
				echo 'File not uploaded!';
			}
		} else {
			echo 'Bad request!';
		}
	}
?>

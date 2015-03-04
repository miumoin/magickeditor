<?php
	if(isset($_REQUEST['magick_image'])){

		preview_magick_image();
	}
	elseif(isset($_REQUEST['magick_background'])) {
		
		magical_transparent_image();
	}
	elseif(isset($_REQUEST['process'])) {
		
		if($_REQUEST['process']=='upload_background_image') process_upload_background_image();
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
						<img src="<?php echo MAGICKBASE; ?>/?magick_background" id="magickArea" width="600"/>
						
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
					
					<a href="#" onclick="javascript:document.getElementById('uploadBackgroundImage').click(); return false;"><strong>Upload Background</strong></a>
													
					<input id="uploadBackgroundImage" type="file" accept="image/*" name="background_image" 
						onchange="javascript:upload_background_image(this, 'magickBackground', 
						'<?php echo MAGICKBASE; ?>?process=upload_background_image&dir_to_store=<?php echo MAGICKDIRECTORY; ?>&', 
						'<?php echo MAGICKBASE; ?>');"
					style="display:none"/>
													
				</div>
			</div>
			
			<div id="magick_layer_edit_image" class="panel panel-primary">	
				<div class="panel-heading">
					<h4>Image Layer</h4>
				</div>
				
				<div class="panel-body">
					
					<div class="form-group">
						<label>Upload Image</label>
						<input type="file" name="image" id="magick_image_file"><br>
						
						<label>Rotation</label>
						<div id="image_rotation_slider"></div>
						
						<label>Bending</label>
						<div id="image_bending_slider"></div>
					</div>
					
				</div>
			</div>
			
			<div id="magick_layer_edit_text"class="panel panel-primary">	
				<div class="panel-heading">
					<h4>Text Layer</h4>
				</div>
				
				<div class="panel-body">
					
					<div class="form-group">
						<label>Text</label>
						<input type="text" name="text" class="form-control" id="magick_text"><br>
						
						<label>Font</label>
						<input type="file" name="font" id="magick_text_font"><br>
						
						<label>Stroke Color</label>
						<input type="text" name="stroke" class="form-control" id="magick_text_stroke"><br>
						
						<label>Font Color</label>
						<input type="text" name="color" class="form-control" id="magick_text_color"><br>
						
						<label>Rotation</label>
						<div id="text_rotation_slider"></div>
						
						<label>Bending</label>
						<div id="text_bending_slider"></div>
						
						
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
	
	

	<div id="magickData"><?php echo $json; ?></div>
	<div id="magickNow"></div>
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
			$im = load_magick_text( $layer->text, $layer->font, 40, $layer->color, $layer->r, $layer->b, $layer->t, 0);
			
		}
		
		if( $layer->b != 0 ) {
			
			$im->setImageMatte( TRUE );
			$im->distortImage(Imagick::DISTORTION_ARC, array($layer->b), FALSE);
		}
		
		if( $layer->r != 0 ) {
			
			$im->rotateImage(new ImagickPixel('none'), $layer->r);
		}		

		//display the image whether it is text or image
		header("Content-Type: image/png");		
		echo $im;
	}

	function load_magick_image($file) {

		$im = new Imagick($file);
		return $im;
	}

	function load_magick_text( $text, $font, $size, $color, $rotation, $bending, $tilting, $shade ) {

		/* Create a new canvas object and a white image */
		$im = new Imagick();
		
		/* Create imagickdraw object */
		$draw = new ImagickDraw();

		/* Set font size and font */
		$draw->setFontSize($size);
		$draw->setFont($font);
		$draw->setFillColor( new ImagickPixel($color) );
		
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
	
	function magical_transparent_image() {
		
		$im = new Imagick();
		$im->newImage(100, 100, (new ImagickPixel('transparent')));
		/* Set the format to PNG */
		$im->setImageFormat('png');
		
		header("Content-Type: image/png");		
		echo $im;
	}
	
	function process_upload_background_image() {
		
		echo "Hello"; die();
		$_FILES['image']=$_FILES['photos'];
		$valid_exts = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		$path=upload_the_image(200 * 1024, "_background", $valid_exts);
		echo $path;
	}
	
	function upload_the_image($max_size, $prefix, $valid_exts) {		
		
		//$max_size = 200 * 1024; // max file size
		$path = $_REQUEST['dir_to_store']."/"; // upload directory

		//temp

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
					}
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

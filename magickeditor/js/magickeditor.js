function magickShow() {

	var json = json_decode(document.getElementById('magickData').value);
	var layers = json.layers;
	var preview = '<img src="'+magickBase+'?load_image='+json.background.image+'" id="magickBackground" style="position: absolute; left: 0px; top: 0px; width: 600px; z-index: 0">';
	var list = "";
	var i = layers.length;
	while( i > 0 ) {

		i--;
		list += '<tr><td>';
		list += magick_layer_element(i, layers[i].type, layers.length);			
		list += '</td></tr>';
		
		preview += '<img src="'+magickBase+'?magick_image='+encodeURIComponent(json_encode(layers[i]))+'" id="magickElement'+i+'" style="position: absolute; left: '+layers[i].x+'px; top: '+layers[i].y+'px; width: '+layers[i].w+'px; height: '+layers[i].h+'px; z-index: '+(i+1)+';">';
	}

	document.getElementById('magickElements').innerHTML = preview;
	document.getElementById('magickList').innerHTML = list;
	
	//initiating color picker
	$(".colorpicker").spectrum({
		preferredFormat: "rgb"
	});
	
	//initiating sliders
	$( "#text_rotation_slider, #text_bending_slider, #image_rotation_slider, #image_bending_slider" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 360,
		value: 0,
		change: refreshMagickElement
	});
}

function magick_layer_element(i, type, total) {

	
	var html = '<div class="row">';

			html += '<div class="col-sm-6">';
				html += '<a href="#" onclick="javascript:startMagick(\''+i+'\'); return false">';
					html += '<button type="button" class="btn btn-default btn-sm" >';					
						if(type == 'image') {

							html += '<span class="glyphicon glyphicon-picture"  style="color:black" aria-hidden="true"></span>';
							html += '<span> image</span>';
						}
						else if(type == 'text') {

							html += '<span class="glyphicon glyphicon-text-size"  style="color:black" aria-hidden="true"></span>';
							html += '<span> text</span>';
						}
					
					html += '</button>';
				html += '</a>';
			html += '</div>';

			html += '<div class="col-sm-2">';
				if(i<(total-1)) {
					html += '<a href="#" onclick="magick_rearrange(\''+i+'\', \'up\'); return false;">';
						html += '<button type="button" class="btn btn-default btn-sm" >	';	
					  		html += '<span class="glyphicon glyphicon-menu-up" aria-hidden="true" style="color:black"></span>';
					  	html += '</button>';
					html += '</a>';
				}
			html += '</div>';

			html += '<div class="col-sm-2">';
				if(i>0) {
					html += '<a href="#" onclick="magick_rearrange(\''+i+'\', \'down\'); return false;">';
					  	html += '<button type="button" class="btn btn-default btn-sm" >	';	
					  		html += '<span class="glyphicon glyphicon-menu-down" aria-hidden="true" style="color:black"></span>';
					  	html += '</button>';
					html += '</a>';
				}
			html += '</div>';
			
			html += '<div class="col-sm-2">';
				html += '<a href="#" onclick="magick_rearrange(\''+i+'\', \'remove\'); return false;">';
				  	html += '<button type="button" class="btn btn-default btn-sm" >	';	
				  		html += '<span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:black"></span>';
					html += '</button>';
				html += '</a>';
			html += '</div>';
					
	html += '</div>';
	return html;
}

function magick_rearrange(id, option){
	
	var json = json_decode(document.getElementById('magickData').value);
	var layers = json.layers;
	var i = 0;
	var j = 0;
	var new_layers = [];

	//Hide both edit panel
	document.getElementById('magick_layer_edit_image').style.display = 'none';
	document.getElementById('magick_layer_edit_text').style.display = 'none';

	//destroy jcrop
	if ($('#magickArea').data('Jcrop')) {
	   $('#magickArea').data('Jcrop').destroy();
	}

	if(option == 'remove') {

		while(i<layers.length) {

			if(id != i) {

				new_layers[j] = layers[i];
				j++;
			}
			i++;
		}
	}
	else if(option == 'up') {

		while(i<layers.length) {

			if(id == i) {

				new_layers[j] = layers[i+1];
				new_layers[j+1] = layers[i];
				i++;
				j++;				
			}
			else new_layers[j] = layers[i];
			j++;
			i++;
		}
	}
	else if(option == 'down') {

		while(i<layers.length) {

			if((id-1) == i) {

				new_layers[j] = layers[i+1];
				new_layers[j+1] = layers[i];
				i++;
				j++;				
			}
			else new_layers[j] = layers[i];
			j++;
			i++;
		}
	}
	
	json.layers = new_layers;
	document.getElementById('magickData').value = json_encode(json);
	magickShow();
}

function refreshMagickElement() {
	
	var id = document.getElementById('magickNow').innerHTML;
	var json = json_decode(document.getElementById('magickData').value);
	
	if(json.layers[id].type == 'image') {
		
		json.layers[id].r = $( "#image_rotation_slider" ).slider( 'value' );
		json.layers[id].b = $( "#image_bending_slider" ).slider( 'value' );
	}
	else if(json.layers[id].type == 'text') {
		
		json.layers[id].r = $( "#text_rotation_slider" ).slider( 'value' );
		json.layers[id].b = $( "#text_bending_slider" ).slider( 'value' );
	}
	document.getElementById('magickData').value = json_encode(json);
	
	document.getElementById('magickElement'+id).src = magickBase+'?magick_image='+encodeURIComponent(json_encode(json.layers[id]));
}

function startMagick( id ) {

	document.getElementById('magickNow').innerHTML = id;

	var json = json_decode(document.getElementById('magickData').value);
	var layers = json.layers;
	
	//hiding input forms
	document.getElementById('magick_layer_edit_image').style.display = 'none';
	document.getElementById('magick_layer_edit_text').style.display = 'none';
	
	//Generating the input form
	if(layers[id].type == 'text') {
		
		//Refresh Text Layer Input form
		document.getElementById('magick_text').value = layers[id].text;
		document.getElementById('magick_text_stroke').value = layers[id].stroke;
		document.getElementById('magick_text_color').value = layers[id].color;
		$("#magick_text_stroke").spectrum({color: layers[id].stroke, preferredFormat: "rgb"});
		$("#magick_text_color").spectrum({color: layers[id].color, preferredFormat: "rgb"});
		$( "#text_rotation_slider" ).slider( "value", layers[id].r );
		$( "#text_bending_slider" ).slider( "value", layers[id].b );

		//checking checkbox if anticlock bending enabled
		if(layers[id].b_anticlock == 1) document.getElementById('text_bending_anticlock').checked = true;
		else document.getElementById('text_bending_anticlock').checked = false;

		//Manual Radius Inputs
		document.getElementById('magick_text_top_radius').value = layers[id].b_top_rad;
		document.getElementById('magick_text_bottom_radius').value = layers[id].b_bottom_rad;

		//Checking checkbox of bending type if manual radius enabled
		if(layers[id].b_type == 'manual') {

			document.getElementById('text_bending_manual_radius').checked = true;
			document.getElementById('text_manual_radius').style.display = 'block';
		}
		else { 

			document.getElementById('text_bending_manual_radius').checked = false;
			document.getElementById('text_manual_radius').style.display = 'none';
		}

		
		//Hide Image Layer Input form
		//Display Text Layer Input form	
		$( '#magick_layer_edit_text').toggle();	
	}
	else if(layers[id].type == 'image') {
		
		//Rerfress Image Layer Input form
		
		$( "#image_rotation_slider" ).slider( "value", layers[id].r );
		$( "#image_bending_slider" ).slider( "value", layers[id].b );


		//checking checkbox if anticlock bending enabled
		if(layers[id].b_anticlock == 1) document.getElementById('image_bending_anticlock').checked = true;
		else document.getElementById('image_bending_anticlock').checked = false;

		//Manual Radius Inputs
		document.getElementById('magick_image_top_radius').value = layers[id].b_top_rad;
		document.getElementById('magick_image_bottom_radius').value = layers[id].b_bottom_rad;

		//Checking checkbox of bending type if manual radius enabled
		if(layers[id].b_type == 'manual') {

			document.getElementById('image_bending_manual_radius').checked = true;
			document.getElementById('image_manual_radius').style.display = 'block';
		}
		else { 

			document.getElementById('image_bending_manual_radius').checked = false;
			document.getElementById('image_manual_radius').style.display = 'none';
		}

		
		//Hide Text Layer Input form
		//Display Image Layer Input form
		$('#magick_layer_edit_image').toggle();
	}

	//Add cropper
	$('#magickArea').Jcrop({
			onChange:    magick_coords,
			bgColor:     'transparent',
			bgOpacity:   1,
			setSelect:   [ layers[id].x, layers[id].y, layers[id].x+layers[id].w, layers[id].y+layers[id].h ]
		});

	
}

function magick_coords( c ) {

	var id = document.getElementById('magickNow').innerHTML;
	document.getElementById('magickElement'+id).style.left = c.x+'px';
	document.getElementById('magickElement'+id).style.top = c.y+'px';
	document.getElementById('magickElement'+id).style.width = c.w+'px';
	document.getElementById('magickElement'+id).style.height = c.h+'px';
	
	var json = json_decode(document.getElementById('magickData').value);
	json.layers[id].x = c.x;
	json.layers[id].y = c.y;
	json.layers[id].w = c.w;
	json.layers[id].h = c.h;
	document.getElementById('magickData').value = json_encode(json);
}

//Creating New Layer
function magick_new_layer(type) {
	
	var json = json_decode(document.getElementById('magickData').value);
	var layers = json.layers;
	var id = layers.length;
	
	
	json.layers[id] = {};
	
	json.layers[id].type = type;
	json.layers[id].x = 0;
	json.layers[id].y = 0;
	json.layers[id].w = 0;
	json.layers[id].h = 0;
	json.layers[id].r = 0;
	json.layers[id].b = 0;
	json.layers[id].t = 0;
	json.layers[id].b_anticlock = 0;
	json.layers[id].b_type = 'auto';
	json.layers[id].b_top_rad = 50;
	json.layers[id].b_bottom_rad = 20;
	
	if(type == 'text') {
		
		json.layers[id].text = '';
		json.layers[id].font = '';
		json.layers[id].color = '';
		json.layers[id].stroke = '';
	}
	else if(type == 'image') {
		
		json.layers[id].file = '';
	}
	
	document.getElementById('magickNow').innerHTML = id;
	document.getElementById('magickData').value = json_encode(json);
	magickShow();
	startMagick(id);
}

//Upload the background
function upload_background_image(file, uploadUrl, base)
{
	document.getElementById("backgroundImageUploadStatus").innerHTML = "uploading...";
	magick_upload(file, uploadUrl, base, 'background_image_uploaded');	
}

function background_image_uploaded( response, base ) {
	var json = json_decode(document.getElementById('magickData').value);
	json.background.image = response;
	document.getElementById('magickData').value = json_encode(json);
	document.getElementById('magickBackground').src = magickBase+'?load_image='+response;
	document.getElementById("backgroundImageUploadStatus").innerHTML = "";
}

//Upload a image layer
function upload_magick_image( file, uploadUrl, base ) {
	
	document.getElementById("magickImageUploadStatus").innerHTML = "uploading...";
	magick_upload(file, uploadUrl, base, 'magick_image_uploaded');	
}

function magick_image_uploaded( response ) {
	
	magick_update_element( 'file', response, 'image' );
	document.getElementById("magickImageUploadStatus").innerHTML = "";
}

function magick_update_element( field, value, type ) {
	
	var id = document.getElementById('magickNow').innerHTML;
	var json = json_decode(document.getElementById('magickData').value);
	if(json.layers[id].type == type) {
		
		json.layers[id][field] = value;
		document.getElementById('magickData').value = json_encode(json);
		document.getElementById('magickElement'+id).src = magickBase+'?magick_image='+encodeURIComponent(json_encode(json.layers[id]));	
	}
}

//Upload Text Font
function upload_magick_font( file, uploadUrl, base ) {
	
	document.getElementById("magickFontUploadStatus").innerHTML = "uploading...";
	magick_upload(file, uploadUrl, base, 'magick_font_uploaded');	
}

function magick_font_uploaded( response ) {
	
	magick_update_element( 'font', response, 'text' );
	document.getElementById("magickFontUploadStatus").innerHTML = "";
}

//Useful Functions
function magick_upload(image, uploadUrl, base, callback)
{
	// Get the selected files from the input.
	var files = image.files;
	
	// Create a new FormData object.
	var formData = new FormData();
	
	// Loop through each of the selected files.
	for (var i = 0; i < files.length; i++) {
	  var file = files[i];

	  /*// Check the file type.
	  if (!file.type.match('image.*')) {
		continue;
	  }*/

	  // Add the file to the request.
	  formData.append('photos[]', file, file.name);
	}	
	
	
	// Set up the request.
	var xhr = new XMLHttpRequest();
	
	// Open the connection.
	xhr.open('POST', uploadUrl, true);
	
	// Set up a handler for when the request finishes.
	xhr.onload = function () {
	  if (xhr.status === 200) {
		
		if(callback != '' ) {			
			window[callback](xhr.responseText, base);
		}

	  } else {
		alert('An error occurred!');
	  }
	};
	
	// Send the Data.
	xhr.send(formData);
	
	return false;
}

/*---------------- PHP.JS Starts ------------------------------*/
function json_encode(mixed_val) {
  //       discuss at: http://phpjs.org/functions/json_encode/
  //      original by: Public Domain (http://www.json.org/json2.js)
  // reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      improved by: Michael White
  //         input by: felix
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //        example 1: json_encode('Kevin');
  //        returns 1: '"Kevin"'

  /*
    http://www.JSON.org/json2.js
    2008-11-19
    Public Domain.
    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
    See http://www.JSON.org/js.html
  */
  var retVal, json = this.window.JSON;
  try {
    if (typeof json === 'object' && typeof json.stringify === 'function') {
      retVal = json.stringify(mixed_val); // Errors will not be caught here if our own equivalent to resource
      //  (an instance of PHPJS_Resource) is used
      if (retVal === undefined) {
        throw new SyntaxError('json_encode');
      }
      return retVal;
    }

    var value = mixed_val;

    var quote = function(string) {
      var escapable =
        /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
      var meta = { // table of character substitutions
        '\b': '\\b',
        '\t': '\\t',
        '\n': '\\n',
        '\f': '\\f',
        '\r': '\\r',
        '"': '\\"',
        '\\': '\\\\'
      };

      escapable.lastIndex = 0;
      return escapable.test(string) ? '"' + string.replace(escapable, function(a) {
        var c = meta[a];
        return typeof c === 'string' ? c : '\\u' + ('0000' + a.charCodeAt(0)
          .toString(16))
          .slice(-4);
      }) + '"' : '"' + string + '"';
    };

    var str = function(key, holder) {
      var gap = '';
      var indent = '    ';
      var i = 0; // The loop counter.
      var k = ''; // The member key.
      var v = ''; // The member value.
      var length = 0;
      var mind = gap;
      var partial = [];
      var value = holder[key];

      // If the value has a toJSON method, call it to obtain a replacement value.
      if (value && typeof value === 'object' && typeof value.toJSON === 'function') {
        value = value.toJSON(key);
      }

      // What happens next depends on the value's type.
      switch (typeof value) {
        case 'string':
          return quote(value);

        case 'number':
          // JSON numbers must be finite. Encode non-finite numbers as null.
          return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':
          // If the value is a boolean or null, convert it to a string. Note:
          // typeof null does not produce 'null'. The case is included here in
          // the remote chance that this gets fixed someday.
          return String(value);

        case 'object':
          // If the type is 'object', we might be dealing with an object or an array or
          // null.
          // Due to a specification blunder in ECMAScript, typeof null is 'object',
          // so watch out for that case.
          if (!value) {
            return 'null';
          }
          if ((this.PHPJS_Resource && value instanceof this.PHPJS_Resource) || (window.PHPJS_Resource &&
            value instanceof window.PHPJS_Resource)) {
            throw new SyntaxError('json_encode');
          }

          // Make an array to hold the partial results of stringifying this object value.
          gap += indent;
          partial = [];

          // Is the value an array?
          if (Object.prototype.toString.apply(value) === '[object Array]') {
            // The value is an array. Stringify every element. Use null as a placeholder
            // for non-JSON values.
            length = value.length;
            for (i = 0; i < length; i += 1) {
              partial[i] = str(i, value) || 'null';
            }

            // Join all of the elements together, separated with commas, and wrap them in
            // brackets.
            v = partial.length === 0 ? '[]' : gap ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind +
              ']' : '[' + partial.join(',') + ']';
            gap = mind;
            return v;
          }

          // Iterate through all of the keys in the object.
          for (k in value) {
            if (Object.hasOwnProperty.call(value, k)) {
              v = str(k, value);
              if (v) {
                partial.push(quote(k) + (gap ? ': ' : ':') + v);
              }
            }
          }

          // Join all of the member texts together, separated with commas,
          // and wrap them in braces.
          v = partial.length === 0 ? '{}' : gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' :
            '{' + partial.join(',') + '}';
          gap = mind;
          return v;
        case 'undefined':
          // Fall-through
        case 'function':
          // Fall-through
        default:
          throw new SyntaxError('json_encode');
      }
    };

    // Make a fake root object containing our value under the key of ''.
    // Return the result of stringifying the value.
    return str('', {
      '': value
    });

  } catch (err) { // Todo: ensure error handling above throws a SyntaxError in all cases where it could
    // (i.e., when the JSON global is not available and there is an error)
    if (!(err instanceof SyntaxError)) {
      throw new Error('Unexpected error type in json_encode()');
    }
    this.php_js = this.php_js || {};
    this.php_js.last_error_json = 4; // usable by json_last_error()
    return null;
  }
}

function json_decode(str_json) {
  //       discuss at: http://phpjs.org/functions/json_decode/
  //      original by: Public Domain (http://www.json.org/json2.js)
  // reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      improved by: T.J. Leahy
  //      improved by: Michael White
  //        example 1: json_decode('[ 1 ]');
  //        returns 1: [1]

  /*
    http://www.JSON.org/json2.js
    2008-11-19
    Public Domain.
    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
    See http://www.JSON.org/js.html
  */

  var json = this.window.JSON;
  if (typeof json === 'object' && typeof json.parse === 'function') {
    try {
      return json.parse(str_json);
    } catch (err) {
      if (!(err instanceof SyntaxError)) {
        throw new Error('Unexpected error type in json_decode()');
      }
      this.php_js = this.php_js || {};
      this.php_js.last_error_json = 4; // usable by json_last_error()
      return null;
    }
  }

  var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
  var j;
  var text = str_json;

  // Parsing happens in four stages. In the first stage, we replace certain
  // Unicode characters with escape sequences. JavaScript handles many characters
  // incorrectly, either silently deleting them, or treating them as line endings.
  cx.lastIndex = 0;
  if (cx.test(text)) {
    text = text.replace(cx, function(a) {
      return '\\u' + ('0000' + a.charCodeAt(0)
        .toString(16))
        .slice(-4);
    });
  }

  // In the second stage, we run the text against regular expressions that look
  // for non-JSON patterns. We are especially concerned with '()' and 'new'
  // because they can cause invocation, and '=' because it can cause mutation.
  // But just to be safe, we want to reject all unexpected forms.
  // We split the second stage into 4 regexp operations in order to work around
  // crippling inefficiencies in IE's and Safari's regexp engines. First we
  // replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
  // replace all simple value tokens with ']' characters. Third, we delete all
  // open brackets that follow a colon or comma or that begin the text. Finally,
  // we look to see that the remaining characters are only whitespace or ']' or
  // ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.
  if ((/^[\],:{}\s]*$/)
    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
      .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
      .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

    // In the third stage we use the eval function to compile the text into a
    // JavaScript structure. The '{' operator is subject to a syntactic ambiguity
    // in JavaScript: it can begin a block or an object literal. We wrap the text
    // in parens to eliminate the ambiguity.
    j = eval('(' + text + ')');

    return j;
  }

  this.php_js = this.php_js || {};
  this.php_js.last_error_json = 4; // usable by json_last_error()
  return null;
}

/*---------------------- PHP.JS Function Ends -----------------------------*/

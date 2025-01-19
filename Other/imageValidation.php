<form>
	<input type="file" id="image" onchange="validateImage(event)">
</form>

<div id="result"></div>
<script>
var x=1;
function validateImage(event){
	document.getElementById('result').innerHTML='';
	if(x==2){
		document.getElementById('output').remove();
		x=1;
	}
	var image=document.getElementById('image');
	var filename=image.value;
	if(filename!=''){
		var extdot=filename.lastIndexOf(".")+1;
		var ext=filename.substr(extdot,filename.lenght).toLowerCase();
		if(ext=="jpg" || ext=="png"){
			x=2;
			var output=document.createElement('img');
			output.id='output';
			output.src=URL.createObjectURL(event.target.files[0]);
			output.style.height = '100px';
			image.after(output);
		}else{
			document.getElementById('result').innerHTML='Please select only jpg and png file';
		}
	}
}
</script>
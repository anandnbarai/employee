 //? Image validation with javascript
    // var x = 'notUpload';

    // var validateImage = function validateImage(event) {

    //     var updateButton = document.getElementById('updateEmp');
    //     var submitButton = document.getElementById('addEmp');

    //     var isUpdatePage = window.location.href.indexOf('empForm.php?edit=') > -1;
    //     var isAddPage = window.location.href.indexOf('empForm.php?insert') > -1;

    //     document.getElementById('check-image').innerHTML = '';
    //     if (!isUpdatePage) {
    //         if (x == 'uploaded') {

    //             document.getElementById('output').remove();

    //             x = 'notUpload';

    //         }
    //     }

    //     var image = document.getElementById('image');

    //     //this will store image in temp path
    //     var filename = image.value;

    //     console.log('Selected file name :', filename);

    //     if (filename != '') {

    //         //below variabl gives us the position of . in filename
    //         var extDotPos = filename.lastIndexOf(".") + 1;
    //         console.log('Position of DOT in image name :', extDotPos);

    //         //this will give image file extension and convert in into lower case
    //         var ext = filename.substr(extDotPos, filename.length).toLowerCase();
    //         console.log('Image file extension :', ext);

    //         if (ext == "jpg" || ext == "png" || ext == "jpeg") {
    //             x = 'uploaded';



    //             // display output image after id = image
    //             if (!isUpdatePage) {
    //                 //create dynamic image tag to display valid image uploaded
    //                 var output = document.createElement('img');

    //                 //below is to provide id to dynamic created img tag
    //                 output.id = 'output';

    //                 //URL.createObjectURL(): This is a built-in JavaScript function provided by the URL object. 
    //                 //It is used to create a unique and temporary URL for a given object.
    //                 output.src = URL.createObjectURL(event.target.files[0]);

    //                 // Set the height of the image to 100 pixels
    //                 output.style.height = '100px';

    //                 // Set the margin-bottom of the image to 15 pixels
    //                 output.style.marginBottom = '15px';

    //                 image.before(output);
    //             }

    //             if (isAddPage) {
    //                 submitButton.disabled = false;
    //             }

    //             if (isUpdatePage) {
    //                 updateButton.disabled = false;
    //             }

    //         } else {

    //             //output will be display in tag having id check-image
    //             document.getElementById('check-image').innerHTML = 'Please Select only jpg, jpeg and png File';

    //             if (isAddPage) {
    //                 submitButton.disabled = true;
    //             }

    //             if (isUpdatePage) {
    //                 updateButton.disabled = true;
    //             }
    //         }
    //     }
    // }
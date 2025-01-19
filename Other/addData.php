<?php
// database connection file
include 'connect.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Data</title>
    <!-- Dropdown -->
    <script src="js/jquery_3.3.1.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- form validation -->
    <script src="js/validation.js"></script>
    <style>
        .error {
            font-weight: bold;
            color: red;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        a {
            text-decoration: none !important;
            color: white;
        }
    </style>

</head>

<body>
    <div class="container-fluid mt-3">
        <button type="submit" name="view" class="btn btn-dark"><a href="index.php">View Data</a></button>
    </div>
    <!-- get date from user -->
    <div class="container-fluid col-md-6">
        <h3 class="text-center mt-2 ">Add Employee Data</h3>
        <form method="post" id="form" enctype="multipart/form-data">
            <div class="form-outline mb-3">
                <label class="form-label">Username :</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name" onkeypress="return onlyAlpha(event)" onInput="checkName()">
                <span id="check-name"></span>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email" onInput="checkEmail()">
                <span id="check-email"></span>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Phone :</label>
                <input type="number" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control" onInput="checkPhone()">
                <span id="check-phone"></span>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Gender :</label>
                <br>
                <input type="radio" name="gender" id="male" value="male" required> Male
                <input type="radio" name="gender" id="female" value="female"> Female
                <input type="radio" name="gender" id="transgender" value="transgender"> Transgender
                <input type="radio" name="gender" id="other" value="other"> Other
                <!-- To display error -->
                <label for="gender" class="error"></label>

            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Country :</label>
                <select name="country" id="country" class="form-control">
                    <option value="">Select Country</option>
                    <?php

                    $sql = $emp->mf_query("select id,name from countries");

                    while ($fetch = $emp->mf_fetch_array($sql)) {
                    ?>
                        <option value="<?php echo $fetch['id']; ?>">
                            <?php echo $fetch['name']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">State :</label>
                <select name="state" id="state" class="form-control">
                    <option value="">Select State</option>
                </select>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">City :</label>
                <select name="city" id="city" class="form-control">
                    <option value="">Select City</option>
                </select>
            </div>

            <div class="form-outline mb-3">
                <label class="form-label">Image :</label>
                <input type="file" name="image" id="image" placeholder="Enter Your Image" class="form-control" onchange="validateImage(event)">
                <span id="check-image" style='color:red'></span>
            </div>

            <div id="dynamicFami">
                <div class="form-outline mb-3" id="mem_1">
                    <label class="form-label">Member 1 :</label>
                    <input type="text" name="vMember_1" id="vMember_1" class="form-control" placeholder="Enter Your Family Member">
                    <i type="button" class="bi bi-trash delete_member" data-id="1" value="Delete"></i>
                </div>
                <input type="hidden" id="iTotalMemer" name="iTotalMemer" value="1" />
            </div>

            <div class="form-outline mb-3" id="dynamicData">
                <input type="button" id="addFMember" name="addFMember" value="Add More Family Members" class="btn btn-dark p-2">
            </div>

            <div class="form-outline mb-3">
                <input type="submit" id="addEmp" name="addEmp" value="Add Data" class="btn btn-dark p-2">
            </div>
        </form>
    </div>

</body>

</html>

<script src="js/ajax.js"></script>
<script>
    //? allow only alphabates in name
    function onlyAlpha(event) {
        var char = event.which;
        if (char > 31 && char != 32 && (char < 65 || char > 90) && (char < 97 || char > 122)) {
            return false;
        }
    }

    //? Check email exist or not while adding 
    function checkEmail() {
        $.ajax({
            url: "checkAvailability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#check-email").html(data);
            },
            error: function() {}
        });
    }

    //? Check name exist or not while adding 
    function checkName() {
        var name = $('#name').val();

        //only check after two alphabets added
        if (name.length >= 2) {
            $.ajax({
                url: "checkAvailability.php",
                data: 'name=' + name, 
                type: "POST",
                success: function(data) {
                    $("#check-name").html(data);
                },
                error: function() {}
            });
        }
    }

    //? Check phone exist or not while adding 
    function checkPhone() {
        var phone = $('#phone').val();

        if (phone.length >= 10) {
            $.ajax({
                url: "checkAvailability.php",
                data: 'phone=' + phone,
                type: "POST",
                success: function(data) {
                    $("#check-phone").html(data);
                },
                error: function() {}
            });
        }
    }


    //? Image validation with javascript
    var x = 'notUpload';
    function validateImage(event) {

        var submitButton = document.getElementById('addEmp');

        document.getElementById('check-image').innerHTML = '';

        if (x == 'uploaded') {

            document.getElementById('output').remove();
            x = 'notUpload';
            
        }

        var image = document.getElementById('image');

        //this will store image in temp path
        var filename = image.value;
        console.log('Selected file name :', filename);

        if (filename != '') {

            //below variabl gives us the position of . in filename
            var extDotPos = filename.lastIndexOf(".") + 1;
            console.log('Position of DOT in image name :', extDotPos);

            //this will give image file extension and convert in into lower case
            var ext = filename.substr(extDotPos, filename.length).toLowerCase();
            console.log('Image file extension :', ext);

            if (ext == "jpg" || ext == "png" || ext == "jpeg") {
                x = 'uploaded';

                //create dynamic image tag to display valid image uploaded
                var output = document.createElement('img');

                //below is to provide id to dynamic created img tag
                output.id = 'output';

                //URL.createObjectURL(): This is a built-in JavaScript function provided by the URL object. 
                //It is used to create a unique and temporary URL for a given object.

                output.src = URL.createObjectURL(event.target.files[0]);

                // Set the height of the image to 100 pixels
                output.style.height = '100px';

                // Set the margin-bottom of the image to 15 pixels
                output.style.marginBottom = '15px';

                // display output image after id = image
                image.before(output);
                submitButton.disabled = false;

            } else {

                //output will be display in tag having id check-image
                document.getElementById('check-image').innerHTML = 'Please Select only jpg, jpeg and png File';
                submitButton.disabled = true;
            }
        }
    }

    //? form vallidation
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                name: {
                    minlength: 2,
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    rangelength: [10, 12],
                    number: true
                },
                gender: {
                    required: true,
                },
                country: {
                    required: true
                },
                state: {
                    required: true
                },
                city: {
                    required: true
                },
                image: {
                    required: true
                },
                vMember_1: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Please enter Name.',
                },
                email: {
                    required: 'Please enter Email Address.',
                    email: 'Please enter a valid Email Address.',
                },
                phone: {
                    required: 'Please enter Contact.',
                    rangelength: 'Phone should be 10 digit number.'
                },
                gender: 'Please select any one Option.',
                country: 'Please select Country.',
                state: 'Please select State.',
                city: 'Please select City.',
                image: 'Please upload image.',
                vMember_1: 'Please add atleast one family member'
            },
            submitHandler: function(form) {
                //form.submit();
            }
        });
    });
</script>
<?php
include 'connect.php';
if (isset($_REQUEST["ed"])) {
    $id = $_REQUEST["ed"];
    $sql = $mfp->mf_query("SELECT * FROM data where id='$id'");
    $row = $mfp->mf_fetch_array($sql);
    extract($row);

    $mem = $mfp->mf_query("SELECT Id,vName FROM family WHERE iEmpId = '$id'");
    if ($mfp->mf_num_rows($mem) > 0) {
        while ($fetchMem = $mfp->mf_fetch_array($mem)) {
            $memberNames[$fetchMem['Id']] = $fetchMem["vName"];
        }
    } else {
        $msg = "<h5>No members found for '$fname'</h5>";
    }

    $iTotalMember = $mfp->mf_num_rows($mem);
}

if (isset($_REQUEST["update"])) {
    extract($_REQUEST);
    $tmp_name = $_FILES['file']['tmp_name'];
    $aUpd = array();

    if ($tmp_name != '') {
        $folder = "uploads/";
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $path = $folder . $file_name;
        $target_file = $folder . basename($file_name);
        $img_ex = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = random_int(100000, 999999) . "." . $img_ex;
        $img_ex_lc = strtolower($img_ex);
        $allowed_exs = array("jpg", "jpeg", "png", "pdf");

        // if ($file_size > 1000000) {
        //     echo "Sorry, your file is too large.";

        // }
        if (in_array($img_ex_lc, $allowed_exs)) {
        } else {
            $error = "You can't upload files of this type";
        }

        // $sql = $mfp->mf_query("SELECT * FROM data WHERE id='$id'");
        // if ($row = $mfp->mf_fetch_array($sql)) {
        //     $delimg = $row['file'];
        // }
        // unlink($folder . $delimg);
        $dbPath = $mfp->uploadFile($tmp_name, $file_name, 'uploads/');
        $aUpd['file'] = $dbPath;
    }

    $aUpd['fname'] = $fname;
    $aUpd['lname'] = $lname;
    $aUpd['dDate'] = $date;
    $aUpd['country'] = $country;
    $aUpd['state'] = $state;
    $aUpd['city'] = $city;
    $aUpd['vsal'] = $sal;
    $aUpd['email'] = $email;
    $aUpd['gender'] = $gender;

    $mfp->mf_dbupdate("data", $aUpd, " WHERE id = '$id'");
    if ($mfp) {
        for ($i = 1; $i <= $_POST['iTotalMemer']; $i++) {

            $iMemberId = intval($_POST['iMemberId_' . $i]);

            if ($iMemberId > 0) {
                $data = array();
                $data['vName'] = $_POST['vMember_' . $i];
                $iMemberId = intval($_POST['iMemberId_' . $i]);
                // echo $iMemberId;exit;
                // print_pre($data);exit;
                $mfp->mf_dbupdate("family", $data, " WHERE Id = '$iMemberId'");

            } else {

                $data = array();
                $data['vName'] = $_POST['vMember_' . $i];
                $data['iEmpId'] = $id;
                $iMemberId = intval($_POST['iMemberId_' . $i]);
                $mfp->mf_dbinsert("family", $data);
            }

            echo "<script>window.open('display.php','_self')</script>";
            //echo "updated";
        }
        // echo $_SESSION['Last_query'] ;
        // if ($mfp) {
        //     echo "<script>window.open('display.php','_self')</script>";
        // }


    } else {
        echo "error";
    }
}

if (isset($_REQUEST["del"])) {
    $id = $_REQUEST["del"];

    $aUpd = array();
    $aUpd['eStatus'] = 'd';
    $mfp->mf_dbupdate("data", $aUpd, " WHERE id = '$id'");

    echo json_encode(array("status" => 200, "msg" => "Record has been deleted successfully."));

    exit;
}

?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>oops </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- icon link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- popup link -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>
    <script src="js/validation.js"></script>
    <style>
        * {
            font-family: 'Crimson Text', serif;
            font-size: 20px;

        }
    </style>
</head>

<body>
    <div class="container">
        <h3>REGISTRATION FORM</h3>
        <form method="post" class="form-horizontal" name="frmAddEmp" id="frmAddEmp" enctype="multipart/form-data">
            <a href="display.php" class="btn btn-info">View details</a>
            <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <div class="form-group">
                <label for="fname" class="control-label col-sm-2">First Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="fname" name="fname" value="<?= $fname ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="fname" class="control-label col-sm-2">Last Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="lname" name="lname" value="<?= $lname ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="date" class="control-label col-sm-2">Date:</label>
                <div class="col-sm-10">
                    <input type="date" id="date" name="date" value="<?= $dDate ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="country" class="control-label col-sm-2">Country:</label>
                <div class="col-sm-10">
                    <select id="country" name="country">
                        <option value="">--------SELECT--------</option>
                        <?php

                        $sql = $mfp->mf_query("select id,name from countries");
                        while ($row = $mfp->mf_fetch_array($sql)) {
                            $selected = ($row['id'] == $country) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo $selected ?>>
                                <?php echo $row['name']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="state" class="control-label col-sm-2">state:</label>
                <div class="col-sm-10">
                    <select id="state" name="state">
                        <option value="">--------SELECT--------</option>
                        <?php

                        $sql = $mfp->mf_query("select * from states where country_id = '$country' ");
                        while ($row = $mfp->mf_fetch_array($sql)) {
                            $selected = ($row['id'] == $state) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo $selected ?>>
                                <?php echo $row['StateName']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="city" class="control-label col-sm-2">city:</label>
                <div class="col-sm-10">
                    <select id="city" name="city">
                        <option value="">--------SELECT--------</option>

                        <?php

                        $sql = $mfp->mf_query("select * from cities where state_id='$state' ");
                        while ($row = $mfp->mf_fetch_array($sql)) {
                            $selected = ($row['id'] == $city) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $row['id']; ?>" <?php echo $selected ?>>
                                <?php echo $row['name']; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="sal" class="control-label col-sm-2">Salary:</label>
                <div class="col-sm-10">
                    <input type="number" id="sal" name="sal" value="<?= $vsal ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-2">Email:</label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" value="<?= $email ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="gender" class="control-label col-sm-2">Gender:</label>
                <div class="col-sm-10">
                    <input type="radio" name="gender" value="male" <?php if ($gender == "male") {
                        echo "checked";
                    } ?>/>  Male
                    <br>
                    <input type="radio" name="gender" value="female" <?php if ($gender == "female") {
                        echo "checked";
                    } ?> />
                    Female <br />
                </div>
            </div>
            <div class="form-group">
                <label for="file" class="control-label col-sm-2">Upload profile:</label>
                <div class="col-sm-10">
                    <?php
                    if (isset($_REQUEST["ed"])) {
                        ?>
                        <img src="uploads/<?= $file ?>" height="130"> <br>&nbsp;&nbsp;
                        <?php
                    } ?>

                    <input type="file" id="file" name="file">

                </div>
            </div>
            <div class="form-group" id="dynamicData">
                <label for="fam" class="control-label col-sm-2">Family Details:</label>
                <input type="button" id="addFMember" name="addFMember" value="Add Family Members"
                    class="btn btn-primary">
            </div>
            <?php
            if ($iTotalMember > 0) {
                ?>
                <div id="dynamicFami">

                    <?php
                        $i = 1;
                        foreach ($memberNames as $key => $memberName) {
                    ?>
                        <div class="form-group" id="mem_<?=$i?>">
                            <label class="control-label col-sm-2">Member <?=$i?>:</label>
                            <input type="text" name="vMember_<?=$i?>" id="vMember_<?=$i?>" class="" value="<?=$memberName?>" required>
                            <i type="button" class="bi bi-trash delete_member p-2" data-id="<?=$i?>" value="Delete"></i>
                            <input type="hidden" name="iMemberId_<?=$i?>" id="iMemberId_<?=$i?>" value="<?=$key?>" />
                        </div>

                    <?php $i++; }  ?>
                    <input type="hidden" id="iTotalMemer" name="iTotalMemer" value="<?= $iTotalMember ?>" />
                </div>
                <?php
            } else {
                ?>
                <div id="dynamicFami">
                    <?php
                    echo $msg;
                    ?>
                    <input type="hidden" id="iTotalMemer" name="iTotalMemer" value="<?= $iTotalMember ?>" />
                </div>
                <?php
            }
            ?>


            <div id="dCity"></div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?php
                    if (isset($_REQUEST["ed"])) {
                        ?>
                        <input type="submit" value="UPDATE" name="update" class="btn btn-primary">
                        <?php
                    } else {
                        ?>
                        <button type="submit" id="submitbtn" name="insert" class="btn btn-primary">SUBMIT</button>
                        <?php
                    }

                    ?>
                </div>
            </div>
        </form>
    </div>
</body>
<script>
    $(document).ready(function () {
        $("#submitbtn").click(function () {

            $("form[name='frmAddEmp']").validate({
                rules: {
                    fname: "required",
                    lname: "required",
                    date: "required",
                    country: "required",
                    state: "required",
                    city: "required",
                    sal: "required",
                    file: "required",
                    gender: "required",
                    email: {
                        required: true,
                        // Specify that email should be validated
                        // by the built-in "email" rule
                        email: true
                    },

                },
                messages: {
                    fname: 'Please enter First Name.',
                    lname: 'Please enter Last Name.',
                    date: 'Please select Date',
                    country: 'Please select Country.',
                    state: 'Please select State.',
                    city: 'Please select City.',
                    sal: 'Please enter your salary',
                    file: 'Please choose your file',
                    gender: 'Please select your gender',
                },
                email: {
                    required: 'Please enter Email Address.',
                    email: 'Please enter a valid Email Address.',
                },

                submitHandler: function (form) {
                    // form.submit();
                }
            });
        });


        $("#submitbtn").click(function () {
            var formData = new FormData($('#frmAddEmp')[0]);
            formData.append('action', 'insert_emp');
            if($("#frmAddEmp").valid()){
                $.ajax({
                    type: "POST",
                    url: 'emAction.php',
                    data: formData,
                    async: false,
                    success: function (data) {
                        alert("Submitted Successfully");
                    },
                    error: function (data) {
                        alert("Fill the details");
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });

    });
    // add family members
    $(document).on('click', '#addFMember', function () {
        var iTotalMemer = $('#iTotalMemer').val();
        iTotalMemer = (iTotalMemer != '') ? parseInt(iTotalMemer) : 0;
        iTotalMemer++;
        $('#iTotalMemer').val(iTotalMemer);

        $('#dynamicFami').append(`
            <div class="form-group" id="mem_` + iTotalMemer + `">
                <label class="control-label col-sm-2">Member ` + iTotalMemer + ` :</label>
                <input type="text" name="vMember_` + iTotalMemer + `" id="vMember_` + iTotalMemer + `"  placeholder="Enter Your Family Member" required>
                <i class="bi bi-trash delete_member p-2" data-id="` + iTotalMemer + `" value="Delete"></i>
            </div>
        `)
    });
    // delete family members
    $(document).on('click', '.delete_member', function () {
        var id = $(this).attr("data-id");
        var db_id = $('#iMemberId_'+id).val();
        console.log(db_id);
        if(db_id > 0){
            // ajax call and delete record in database
        }
        
        var numFields = $('#dynamicFami input:not(#iTotalMemer)').length;

        if (numFields > 1) {
            $('#mem_' + id).remove();
        } else {
            alert('You cannot delete the last Input Field.');
        }
    });

    //fetch data of state in update
    $(document).on('change', '#country', function () {
        $('#state').html('<option value="">Select State</option>');
        var cId = $(this).val();
        $.ajax({
            url: 'fetch.php',
            type: "POST",
            data: {
                c_id: cId
            },
            success: function (data) {
                $('#state').append(data);
            }
        });
    });
    //fetch data of city in update
    $(document).on('change', '#state', function () {
        $('#city').html('<option value="">Select City</option>');
        var sId = $(this).val();
        $.ajax({
            url: 'fetch1.php',
            type: "POST",
            data: {
                s_id: sId
            },
            dataType:'json',
            success: function (data) {
                $('#city').append(data.html);

                var html = '<table>';
                $.each(data.aData, function (index, city) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${city}</td>
                        </tr>
                    `;
                });
                html += '</table>';
                $('#dCity').html(html);
            }
        });
    });

   
    
</script>

</html>
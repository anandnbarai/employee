<?php
include 'connect.php';
//fetch data 
$sql = $mfp->mf_query("SELECT * FROM data");
$row = $mfp->mf_fetch_array($sql);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display</title>
    <!-- jquery datatable link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- export pdf jquery -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- icon link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />




    <style>
        * {
            font-family: 'Crimson Text', serif;
            font-size: 20px;

        }
    </style>
</head>

<body>

    <div align="center">
        <h4>
            <a href="index.php">Register Now</a>
        </h4>
    </div>
    <div class="row col-md-1">
        <div class="form-outline">
            <form action="printall.php">
                <input type="submit" id="printAll" value="PDF" name="printAll" class="btn btn-info">
            </form>
        </div>
    </div>
    <div class="row col-md-1">
        <div class="form-outline">
            <form action="export.php">
                <input type="submit" id="printAll" value="Excel" name="export" class="btn btn-info">
            </form>
        </div>
    </div>
    <div class="row col-md-2">
        <div class="form-outline">
            <select name="filter_country" id="filter_country" class="form-control">
                <option value="">Search Country</option>
                <?php
                $country_sql = $mfp->mf_query("SELECT id,name FROM countries");
                while ($cou = $mfp->mf_fetch_array($country_sql)) {
                    ?>
                    <option value="<?php echo $cou['id']; ?>">
                        <?php echo $cou['name']; ?>
                    </option>&nbsp;
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row col-md-2">
        <div class="form-outline">
            <select name="filter_state" id="filter_state" class="form-control">
                <option value="">Search State</option>
                <?php
                $state_sql = $mfp->mf_query("SELECT id,StateName FROM states");

                while ($state = $mfp->mf_fetch_array($state_sql)) {
                    ?>
                    <option value="<?php echo $state['id']; ?>">
                        <?php echo $state['StateName']; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row col-md-2">
        <div class="form-outline">
            <input type="submit" id="btnSearch" value="Search" class="btn btn-info">
        </div>
    </div>

    <div class="row col-md-1">
        <div class="form-outline">
            <form method="post" action="import.php" enctype="multipart/form-data">
                <input type="file" name="file" required><br>
                <input type="submit" value="IMPORT" name="import" class="btn btn-info">
            </form>
        </div>
    </div>


    <div class="container-fluid col-md-8" align="center">
        <form method="post" id="frm">
            <table id="tblData" class="table table-striped table-bordered">
                <h3>DETAILS</h3>
                <thead>
                    <tr>
                        <th colspan="14">
                            <input type="button" class="btn btn-danger" id="delete_selected" name="delete[]"
                                value="Delete All Records">
                        </th>
                    </tr>
                    <tr>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>Sr. No.</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Salary</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Profile </th>
                        <th>Family Details</th>
                        <th>Action</th>

                    </tr>
                </thead>
            </table>
        </form>
    </div>
    <!-- popup modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Family Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                        <?php
                        $sql = $mfp->mf_query("SELECT iEmpid,vName FROM family");
                        $result = $mfp->mf_fetch_array($sql);
                        //print_pre($result);

                        if ($result->num_rows > 0) {
                            while ($datas = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $datas["id"] . "</td>";
                                echo "<td>" . $datas["name"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No data found</td></tr>";
                        }
                        ?>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    //fetch datatable
    var t = "";
    $(document).ready(function () {
        t = $('#tblData').DataTable({
            "processing": true,
            "serverSide": true,
            "language": {
                "infoFiltered": "",
                "processing": "<img src='http://192.168.1.155/finix/images/loader.svg'>"
            },
            "ajax": "./get_userdata.php",
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0, 12]
            },],
            "order": [
                [1, 'DESC']
            ],
            //export to other format
            dom: 'lBfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
        });


        // All events goes here....
    });
    // delete datatable
    $(document).on('click', '.delete_mem', function () {
        var id = $(this).attr("data-id");

        if (confirm("Are you sure you want to delete this Member?")) {

            $.ajax({
                type: "POST",
                url: "index.php",
                data: ({
                    'del': id
                }),

                success: function (data) {
                    if (data.status == 200) {
                        alert(data.msg);
                    }
                    t.draw();
                }
            });
        } else {
            return false;
        }

    });
    $(document).on('click', '#btnSearch', function () {
        // jQuery expression that searches the first column of a table for a value that matches the value of the #filter_country input field.
        t.column(0).search($('#filter_country').val());
        t.column(1).search($('#filter_state').val());
        // t.column(2).search($('#filter_city').val());

        t.draw();
    });

    //multidelete checkbox
    $(document).on('click', '#select_all', function () {
        if ($("input:checkbox").prop("checked")) {
            $("input:checkbox[name='delete[]']").prop("checked", true);
        } else {
            $("input:checkbox[name='delete[]']").prop("checked", false);
        }
    });

    $(document).on('click', '.check_box', function () {
        var total_check_boxes = $("input:checkbox[name='delete[]']").length;
        var total_checked_boxes = $("input:checkbox[name='delete[]']:checked").length;
        // console.log(total_check_boxes,total_checked_boxes);

        // If all checked manually then check select_all checkbox
        if (total_check_boxes === total_checked_boxes) {
            $("#select_all").prop("checked", true);
        }
        else {
            $("#select_all").prop("checked", false);
        }
    });

    // delete checkboxes

    $(document).on('click', '#delete_selected', function () {
        var selectedIds = [];
        // Get the selected checkboxes
        $("input[name='delete[]']:checked").each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert("Please select at least one record to delete.");
            return;
        }
        // console.log(selectedIds);

        // Send an Ajax request to delete.php
        $.ajax({
            type: "POST",
            url: "emAction.php",
            data: {
                'action': 'delete_multiple_emp',
                'ids': selectedIds
            },
            dataType: 'json',
            success: function (data) {
                // // Handle the response from delete.php (e.g., show a success message)
                // alert(response);
                // // You can also refresh the page or update the table here
                alert(data.msg);
                t.draw();
            },
        });
    });

    $(document).on('click', '.foram', function () {
        // alert("hii");
        var id = $(this).attr('data-id');
        //console.log(id);
        //exampleModal
        $('#exampleModal').modal('show');
    });
</script>
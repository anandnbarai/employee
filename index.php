<?php

include 'connect.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Data</title>

    <!-- Datatable -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Accordian -->
    <!-- <script src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"></script> -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <!-- PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <!-- Export file to excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <!-- print button -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Datatable CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">

    <!-- Datatable BS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script> -->
  
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    
    <!-- BS Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        a {
            text-decoration: none !important;
            color: white;
        }

        #btn-show-all-doc {
            float: right;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-3">
        <button type="submit" name="add" id="addData" class="btn btn-dark"><a href="empForm.php?insert">Add Data</a></button>
        <h2 class="text-center">Employee Data</h2>
    </div>
    <div class="container d-flex mt-3">
        <div class="col-md-2">

        </div>
        <div class="row col-md-4 p-2">
            <div class="form-outline">
                <select name="filter_country" id="filter_country" class="form-control">
                    <option value="">Search by Country</option>
                    <?php

                    $country_sql = $emp->mf_query("SELECT id,name FROM countries");
                    while ($fetchCountry = $emp->mf_fetch_array($country_sql)) {
                    ?>
                        <option value="<?php echo $fetchCountry['id']; ?>">
                            <?php echo $fetchCountry['name']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row col-md-4 p-2">
            <div class="form-outline">
                <select name="filter_state" id="filter_state" class="form-control">
                    <option value="">Search by State</option>
                    <?php

                    $state_sql = $emp->mf_query("SELECT id,StateName FROM states");

                    while ($fetchState = $emp->mf_fetch_array($state_sql)) {
                    ?>
                        <option value="<?php echo $fetchState['id']; ?>">
                            <?php echo $fetchState['StateName']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row col-md-2 p-2">
            <div class="form-outline">
                <input type="submit" id="btnSearch" value="Search" name="add" class="btn btn-dark p-1">
            </div>
        </div>
    </div>

    </div>
    <div class="container-fluid col-md-8 mt-3 p-3">
        <form action="allAction.php" method="POST" enctype="multipart/form-data">
            <div class="row p-2">
                <div class="col-md-10">
                    <input type="file" name="excel" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="uploadExcel" class="btn btn-dark">Upload Excel File</button>
                </div>

            </div>
        </form>
    </div>
    
    <!-- <input type="checkbox"> -->
    <div class="container-fluid xyz col-md-8">
        <table id="emptbl" class="display" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th colspan="3">
                        <button type="button" id="delete_selected" name="delete[]" value="Delete">Delete</button>
                    </th>
                    <th colspan="3">
                        <form action="allAction.php" method="post">
                            <button type="submit" name="exportPDF" value="Export Data to PDF">Export Data to PDF</button>
                        </form>
                    </th>
                    <th colspan="3">
                        <form action="allAction.php" method="post">
                            <button type="submit" name="exportExcel" value="Export Data to Excel">Export Data to Excel</button>
                        </form>
                    </th>
                    <th colspan="4">
                        <button id="btn-show-all-doc">Expand / Collapse</button>
                    </th>
                </tr>
                <tr class="text-center">
                    <th><i class="bi bi-plus-square"></i></th>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Image</th>
                    <th>Family Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="viewMem" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Family Member Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="editMember" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    //? format, fetch datatable
    var t = "";
    $(document).ready(function() {
        t = $('#emptbl').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": "fetchEmpData.php",

            //below data name defined is key
            columns: [{
                    "className": 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    render: function() {
                        return '<i class="bi bi-plus-square"></i>'
                    },
                },
                {
                    data: 'checkbox'
                },
                {
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'gender'
                },
                {
                    data: 'country'
                },
                {
                    data: 'state'
                },
                {
                    data: 'city'
                },
                {
                    data: 'image'
                },
                {
                    data: 'family'
                },
                {
                    data: 'action'
                }
            ],

            //DESC : last added data will show first

            "order": [
                [0, 'DESC']
            ],

            //aoColumnDefs is used to define the columns in the DataTables
            //aoColumnDefs is an array of objects that define the columns in a jQuery DataTable 
            //The aoColumnDefs option can be used to customize the columns in the DataTable in a number of ways
            //data sort by column

            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0, 1, 4, 5, 10, 11, 12]
            }],

            //export data
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                },
                'copy', 'csv', 'excel', 'print'
            ],
        });
        // .on('preXhr.dt', function(e, settings, xhr) {
        //     if (settings.jqXHR)
        //         settings.jqXHR.abort();
        //     $(".dataTables_processing").show();
        // });

        //to expand and collapse child row
        t.on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let row = t.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                $(this).find('i').removeClass('bi bi-dash-square').addClass('bi bi-plus-square');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                $(this).find('i').removeClass('bi bi-plus-square').addClass('bi bi-dash-square');
            }
        });

    });

    $('#page_number').change(function() {
        var page_number = $(this).val();
        $('#emptbl').DataTable().page(page_number - 1).draw();
    });


    //? to return 
    function format(d) {

        var div = $('<div/>').addClass('loading').text('Loading...');

        $.ajax({
            url: 'allAction.php',
            type: "POST",
            data: {
                'action': 'childRow',
                'empId': d.id
            },
            dataType: 'json',
            // async:false,
            success: function(response) {

                var memberNames = response.aData;

                var listHtml = `<table>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Member Name</th>
                                </tr>`;

                if (memberNames == null) {
                    listHtml += `<tr><td colspan="2">Data not found.</td></tr>`;
                    console.log('aa');
                } else {
                    $.each(memberNames, function(index, memberName) {
                        listHtml += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${memberName}</td>
                            </tr>
                        `;
                    });
                }

                listHtml += `</table>`;

                div.html(listHtml).removeClass('loading');
            }
        });
        return div;
    }

    //? to fetch family member details and display in Index page
    $(document).on('click', '.memberInfo', function() {
        var empId = $(this).data('id');
        // console.log(empId);
        // exit;
        $.ajax({
            type: 'POST',
            url: 'allAction.php',
            data: {
                'action': 'fetchFamily',
                empId: empId
            },
            success: function(response) {
                $('.modal-body').html(response);
                $('#viewMem').modal('show');
            }
        });
    });


    //? delete action Index page
    $(document).on('click', '.delete', function() {
        var id = $(this).attr("deleteid");
        if (confirm("Are you sure?")) {
            $.ajax({
                type: "POST",
                url: "allAction.php",
                data: {
                    'action': 'deleteData',
                    'delete': id
                },
                dataType: 'json',
                success: function(data) {
                    alert(data.msg);
                    t.draw();
                }
            });
        } else {
            return false;
        }
    });

    //? country,state filter
    $(document).on('click', '#btnSearch', function() {

        // jQuery expression that searches the first column of a table for a value that matches the value of the #filter_country input field.

        t.column(0).search($('#filter_country').val());
        t.column(1).search($('#filter_state').val());
        // t.column(2).search($('#filter_city').val());

        t.draw();
    });

    //? select all checkbox when click on id=select_all checkbox (header row checkbox)
    $(document).on('click', '#select_all', function() {

        //To get the property of an element, you can use the .prop() method with the name of the property as the argument.

        //if any input:checkbox is checked 
        if ($("input:checkbox").prop("checked")) {
            $("input:checkbox[name='delete[]']").prop("checked", true);
            console.log('checked all');
        }

        //else condition is used when use after selecting all checkbox again select id=select_all checkbox
        //then all checked checkbox again unchecked
        else {
            $("input:checkbox[name='delete[]']").prop("checked", false);
            console.log('unchecked all');
        }
    });


    //? Delete for checkboxes
    $(document).on('click', '#delete_selected', function() {
        var ids = [];

        // Get the selected checkboxes
        $("input[name='delete[]']:checked").each(function() {
            ids.push($(this).val());
        });

        // console.log(ids);
        // console.log(ids.length);
        if (ids.length === 0) {
            alert("Please Select at least One Record to delete.");
            return;
        }

        // Send an Ajax request to delete.php
        if (confirm("Are you sure?")) {
            $.ajax({
                type: "POST",
                url: "allAction.php",
                data: {
                    'action': 'deleteUsingCheckbox',
                    'ids': ids
                },
                dataType: 'json',
                success: function(data) {
                    // // Handle the response from delete.php (e.g., show a success message)
                    // alert(response);
                    // // You can also refresh the page or update the table here
                    alert(data.msg);
                    t.draw();
                },
            });
        }
    });

    //? Collapse Expand All Button
    $('#btn-show-all-doc').on('click', expandCollapseAll);

    function expandCollapseAll() {

        t.rows('.parent').nodes().to$().find('td:first-child').trigger('click').length
        t.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click')

    }
</script>
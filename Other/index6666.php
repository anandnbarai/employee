<?php

include 'connect.php';

require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Data</title>

    <!-- Datatable -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>

    <!-- Accordian -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
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
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand p-2" href="#">Employee Data</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link p-2" href="addData.php">Add Data</a>
                </li>
            </ul>
        </div>
    </nav> -->

    <div class="container-fluid mt-3">
        <button type="submit" name="view" class="btn btn-dark"><a href="addData.php">Add Data</a></button>
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
        <form action="importExcel.php" method="POST" enctype="multipart/form-data">
            <div class="row p-2">
                <div class="col-md-10">
                    <input type="file" name="excel" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="upload_excel" class="btn btn-dark">Upload Excel File</button>
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
                        <form action="allDataInPdf.php">
                            <button type="submit" id="printAll" value="Export Data to PDF" name="printAll">Export Data to PDF</button>
                        </form>
                    </th>
                    <th colspan="3">
                        <form action="allDataInExcel.php">
                            <button type="submit" id="print" value="Export Data to Excel" name="print">Export Data to Excel</button>
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
        })

        // .on('preXhr.dt', function(e, settings, xhr) {
        //     if (settings.jqXHR)
        //         settings.jqXHR.abort();
        //     $(".dataTables_processing").show();
        // });


        t.on('click', 'td.dt-control', function(e) {
            let tr = e.target.closest('tr');
            let row = t.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            } else {
                // Open this row
                row.child(format(row.data())).show();
            }
        });

    });


    function format(d) {
        var div = $('<div/>').addClass('loading').text('Loading...');
        $.ajax({
            url: 'childRow.php',
            type: "POST",
            data: {
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

                if(memberNames == null){
                    listHtml += `<tr><td colspan="2">Data not found.</td></tr>`;
                    console.log('aa');
                }else{
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

    //? child table format and fetch data as key from fetchEmpData, below function is use when use click on + button and expand the data
    // function childRow(d) {
    //     //console.log(d);

    //     // `d` is the original data object for the row
    //     return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
    //         '<tr>' +
    //         '<td>Name :</td>' +
    //         '<td>' + d.name + '</td>' +
    //         '</tr>' +
    //         '<tr>' +
    //         '<td>Email : </td>' +
    //         '<td>' + d.email + '</td>' +
    //         '</tr>' +
    //         '<tr>' +
    //         '<td>Phone : </td>' +
    //         '<td>' + d.phone + '</td>' +
    //         '</tr>' +
    //         '<tr>' +
    //         '<td>Gender :</td>' +
    //         '<td>' + d.gender + '</td>' +
    //         '</tr>' +
    //         '<tr>' +
    //         '<td>Image :</td>' +
    //         '<td>' + d.image + '</td>' +
    //         '</tr>' +
    //         '</table>';
    // }

    //fetch family member data in child row when click on i button 
    // $(document).on('click', 'td.details-control', function() {
    //     /* var empId = d.id;
    //     $.ajax({
    //         url: 'childRow.php',
    //         type: "POST",
    //         data: {
    //             empId: empId
    //         },
    //         success: function(response) {
    //             // Define the childRow function here
    //             function childRow(d) {
    //                 // `d` represents the data returned from the server (response)
    //                 // You can use it to construct the HTML for the child row

    //                 // For simplicity, we insert the entire response as HTML in the child row
    //                 return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
    //                             '<tr>' +
    //                                 '<td>Employee & Family Details :</td>' +
    //                                 '<td>' + response + '</td>' +
    //                             '</tr>' +
    //                         '</table>';
    //             }

    //             // Insert the child row with the defined function
    //             // The response from the server is passed as a parameter to childRow
    //             row.child(childRow(response)).show();

    //             // Change the icon to indicate that the child row is now open
    //             $(this).find('i').removeClass('bi bi-plus-square').addClass('bi bi-dash-square');
    //         }
    //     }); */


    //     // Find the closest row (tr) to the clicked cell
    //     /* var tr = $(this).closest('tr');

    //     //to fetch data from specific datatable row
    //     var d = t.row(this).data();

    //     //provide specific row id
    //     var empId = d.id;

    //     // Get the DataTable row object based on the clicked row
    //     var row = t.row(tr); // Assuming you have defined t as your DataTable variable

    //     if (row.child.isShown()) {
    //         // This block executes when the child row is already open

    //         // Hide the child row
    //         row.child.hide();
    //         // Change the icon to indicate that the child row is now closed

    //         $(this).find('i').removeClass('bi bi-dash-square').addClass('bi bi-plus-square');
    //     } else {

    //         // This block executes when the child row is not open
    //         // Get the employee ID from a data attribute of the clicked cell

    //         $.ajax({
    //             url: 'childRow.php',
    //             type: "POST",
    //             data: {
    //                 empId: empId
    //             },
    //             success: function(response) {
    //                 // Define the childRow function here
    //                 function childRow(d) {
    //                     // `d` represents the data returned from the server (response)
    //                     // You can use it to construct the HTML for the child row

    //                     // For simplicity, we insert the entire response as HTML in the child row
    //                     return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
    //                                 '<tr>' +
    //                                     '<td>Employee & Family Details :</td>' +
    //                                     '<td>' + response + '</td>' +
    //                                 '</tr>' +
    //                           '</table>';
    //                 }

    //                 // Insert the child row with the defined function
    //                 // The response from the server is passed as a parameter to childRow
    //                 row.child(childRow(response)).show();

    //                 // Change the icon to indicate that the child row is now open
    //                 $(this).find('i').removeClass('bi bi-plus-square').addClass('bi bi-dash-square');
    //             }
    //         });
    //     } */
    // });

    //? code for expand/collapse button working, show, hide childe row
    // $('#emptbl tbody').on('click', 'td.details-control', function() {
    //     var tr = $(this).closest('tr');
    //     var empId = $(this).data('id');
    //     // t is datatable variable, created when datatable defined
    //     var row = t.row(tr);

    //     $.ajax({
    //         url: 'fetchFam.php',
    //         type: "POST",
    //         data: {
    //             empId: empId
    //         },
    //         success: function(response) {

    //             function childRow(response) {
    //                 // `d` can be used to access data in the parent row
    //                 // Construct the HTML for the child row using `d` or `response`
    //                 return 'Hi';
    //             }
    //             row.child(childRow(response)).show();
    //             $(this).find('i').removeClass('bi bi-plus-square').addClass('bi bi-dash-square');
    //         }

    //     });

    //     if (row.child.isShown()) {
    //         // This row is already open - close it
    //         row.child.hide();
    //         //code will change icon - to + when child row closed
    //         $(this).find('i').removeClass('bi bi-dash-square').addClass('bi bi-plus-square');
    //     } else {

    //         // childRows cuntion created above
    //         row.child(childRow(row.data())).show();
    //         //code will change icon + to - when child row open
    //         $(this).find('i').removeClass('bi bi-plus-square').addClass('bi bi-dash-square');
    //     }
    //     //if(child row shown){close child row} else{open child row}
    // });

    // $(document).on('click', 'td.details-control', function() {
    //     var empId = $(this).data('id');
    //     var row = $(this).closest('tr');

    //     $.ajax({
    //         url: 'fetchFam.php',
    //         type: "POST",
    //         data: {
    //             empId: empId
    //         },
    //         success: function(response) {
    //             var data = JSON.parse(response);
    //             var childRowHtml = childRow(data);
    //         }
    //     });
    // });

    // Handle click on "Expand All" button
    // $('#btn-show-all-children').on('click', function() {
    //     // Enumerate all rows
    //     t.rows().every(function() {
    //         // If row has details collapsed
    //         if (!this.child.isShown()) {
    //             // Open this row
    //             this.child(format(this.data())).show();
    //             $(this.node()).addClass('shown');
    //         }
    //     });
    // });

    // // Handle click on "Collapse All" button
    // $('#btn-hide-all-children').on('click', function() {
    //     // Enumerate all rows
    //     t.rows().every(function() {
    //         // If row has details expanded
    //         if (this.child.isShown()) {
    //             // Collapse row details
    //             this.child.hide();
    //             $(this.node()).removeClass('shown');
    //         }
    //     });
    // });

    //? Collapse Expand All Button
    $('#btn-show-all-doc').on('click', expandCollapseAll);

    function expandCollapseAll() {

        t.rows('.parent').nodes().to$().find('td:first-child').trigger('click').length
        t.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click')

    }

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

    //To count total checkox and cheked checkbox
    // $(document).on('click', '.check_box', function() {
    //     var total_check_boxes = $("input:checkbox[name='delete[]']").length;
    //     var total_checked_boxes = $("input:checkbox[name='delete[]']:checked").length;
    //     console.log(total_check_boxes,total_checked_boxes);

    //     // If all checked manually then check select_all checkbox
    //     if (total_check_boxes === total_checked_boxes) {
    //         $("#select_all").prop("checked", true);
    //     } else {
    //         $("#select_all").prop("checked", false);
    //     }
    // });

    //? Delete for checkboxes
    $(document).on('click', '#delete_selected', function() {
        var ids = [];

        // Get the selected checkboxes
        $("input[name='delete[]']:checked").each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            alert("Please select at least one record to delete.");
            return;
        }
        // console.log(selectedIds);

        // Send an Ajax request to delete.php
        if (confirm("Are you sure?")) {
            $.ajax({
                type: "POST",
                url: "deleteAll.php",
                data: {
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

    //? delete action
    $(document).on('click', '.delete', function() {
        var id = $(this).attr("deleteid");
        if (confirm("Are you sure?")) {
            $.ajax({
                type: "POST",
                url: "deleteData.php",
                data: {
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

    // The table.column().search() method in DataTables is used to search for data in a specific column of a table
    //? country filter
    $(document).on('click', '#btnSearch', function() {

        // jQuery expression that searches the first column of a table for a value that matches the value of the #filter_country input field.

        t.column(0).search($('#filter_country').val());
        t.column(1).search($('#filter_state').val());
        // t.column(2).search($('#filter_city').val());

        t.draw();
    });

    //? to fetch family member details
    $(document).on('click', '.memberInfo', function() {
        var empId = $(this).data('id');
        // console.log(empId);
        // exit;
        $.ajax({
            type: 'POST',
            url: 'fetchFam.php',
            data: {
                empId: empId
            },
            success: function(response) {
                $('.modal-body').html(response);
                $('#viewMem').modal('show');
            }
        });
    });
</script>
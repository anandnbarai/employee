 $(document).on('click', '.delete_member', function() {

        //fetch id of newly added family member dynamic input field
        var id = $(this).attr("data-id");

        //fetch id of already inserted family member
        var db_id = $('#iMemberId_' + id).val();
        // console.log(db_id);

        if($('.delete_member').length < 2){
            alert("You can't delete last input field.");
            return false;
        }

        if (db_id > 0) {
            $.ajax({
                type: 'POST',
                url: "allAction.php",
                data: {
                    'action': 'deleteFamily',
                    db_id: db_id
                },
                dataType: 'json',
                success: function(data) {
                    alert(data.msg);
                    //reload same page after data update, otherwise empty data will added.
                    // window.location.reload();
                    // $("#dynamicFami").load(location.href + "#dynamicFami");
                    $('#mem_'+id).remove();
                    // $('#iTotalMemer').remove();
                }
            });
        }else{
            $('#mem_'+id).remove();
        }
    });
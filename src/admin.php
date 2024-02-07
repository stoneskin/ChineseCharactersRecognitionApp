<?php require "_adminSessionHeader.php" ?> 
<?php require_once '_incFunctions.php' // htmlpurifier-4.15.0-lite/library/HTMLPurifier.auto.php is included ?>
<?php
   if (isset($_GET['includeNonActive']) && $_GET['includeNonActive']==1) {
        $includeNonActive = true;                  
    } else {     
        $includeNonActive = false;               
   }
?>
<script>
$(function() {
        // Create New Row
        $('#add_event').click(function() {
            if ($('tr[data-id=""]').length > 0) {
                $('tr[data-id=""]').find('[name="eventName"]').focus()
                return false;
            }
            var tr = $('<tr>')
            $('input[name="id"]').val('')
            tr.addClass('py-1 px-2');
            tr.attr('data-id', '');
            tr.append('<td contenteditable name="eventName"></td>')
            tr.append('<td contenteditable name="accessKey"></td>')
            tr.append('<td contenteditable name="activeDate"></td>')
            tr.append('<td contenteditable name="expiredDate"></td>')
            tr.append('<td class="text-center"><button class="btn btn-sm btn-primary btn-flat rounded-0 px-2 py-0">Save</button><button class="btn btn-sm btn-dark btn-flat rounded-0 px-2 py-0" onclick="cancel_button($(this))" type="button">Cancel</button></td>')
            $('#form-tbl').append(tr)
            tr.find('[name="eventName"]').focus()
        })

        // Edit Row
        $('.edit_data').click(function() {
            var id = $(this).closest('tr').attr('data-id')
            $('input[name="id"]').val(id)
            var count_column = $(this).closest('tr').find('td').length
            $(this).closest('tr').find('td').each(function() {
                if ($(this).index() != (count_column - 1))
                    $(this).attr('contenteditable', true)
            })
            $(this).closest('tr').find('[name="eventName"]').focus()
            $(this).closest('tr').find('.editable').show('fast')
            $(this).closest('tr').find('.noneditable').hide('fast')
        })


        $('#form-data').submit(function(e) {
            e.preventDefault();
            var id = $('input[name="id"]').val();
           
            var data = {};
            // check fields promise
            var check_fields = new Promise(function(resolve, reject) {
                    data['id'] = id;
                    $('td[contenteditable]').each(function() {
                        data[$(this).attr('name')] = $(this).text()
                        if (data[$(this).attr('name')] == '') {
                            alert("All fields are required.");
                            resolve(false);
                            return false;
                        }
                    })

                    resolve(true);
                })
            check_fields.then(function(resp) {
                if (!resp)
                    return false;

                $.ajax({
                    url: "adminAPI.php",
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(res) {
                        alert (res);
                        if (res.includes('successfully'))
                        {
                            location.reload();
                        }

                    },
                    error: function (err) {
                        alert('An error occured while saving the data!');
                        console.log(err)
                    },

                })
            })
        })
    })


// removing table row when cancel button triggered clicked
window.cancel_button = function(_this) {
    if (_this.closest('tr').attr('data-id') == '') {
        _this.closest('tr').remove()
    } else {
        $('input[name="id"]').val('')
        _this.closest('tr').find('td').each(function() {
            $(this).removeAttr('contenteditable')
        })
        _this.closest('tr').find('.editable').hide('fast')
        _this.closest('tr').find('.noneditable').show('fast')
    }
}

function switchEventList(includeNonActive)
{
    alert(includeNonActive);
    if (includeNonActive == 1)
    {
        window.location.assign('admin.php?includeNonActive=1');
    }
    else
    {
        window.location.assign('admin.php');
    }
}

function chkIncludeNonActive_Click(checkbox) {
    if(checkbox.checked){
        window.location.assign('admin.php?includeNonActive=1');
    }
    else{
        window.location.assign('admin.php');
    }
}

</script>

<div class="container">
    <div class="row">
        <div class="frame-main col-md-3 col-sm-3">
            <div class="form-title">Admin Account Info</div>
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="label">UserName:</div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="label">
                        <?php echo $_SESSION["loginUser"] ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="label">
                        <a href="editAccount.php">Edit Account</a>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <h2>Event List</h2>
    
    <div class="row">

        <div class="col-12">
            <!-- Table Form start -->
            <form action="" id="form-data">
                <?php 
                    if ($includeNonActive) {
                        echo '<input type="checkbox" id="chkIncludeNonActive" name="chkIncludeNonActive" checked onclick="chkIncludeNonActive_Click(this)">';                 
                    } else {                
                        echo '<input type="checkbox" id="chkIncludeNonActive" name="chkIncludeNonActive" onclick="chkIncludeNonActive_Click(this)">';  
                    }
                ?>
                <label for="chkIncludeNonActiveEvent">Include non-active events</label><br>
                <input type="hidden" name="id" value="">
                <table id="form-tbl">
                    <colgroup>
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center p-1">Event Name</th>
                            <th class="text-center p-1">Access Key</th>
                            <th class="text-center p-1">Active Date</th>
                            <th class="text-center p-1">Expire Date</th>
                            <th class="text-center p-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if ($includeNonActive) {
                        $query = $conn->query("SELECT * FROM `event` ORDER by ExpiredDate DESC limit 50");                      
                    } else {                
                        $query = $conn->query("SELECT * FROM `event` WHERE ExpiredDate> CURRENT_DATE() and ActiveDate<=CURRENT_DATE() ORDER by ExpiredDate DESC");                               
                    }
                  
                    while($row = $query->fetch_assoc()):
                    ?>
                    <tr data-id='<?php echo $row['ID'] ?>'>
                        <?php
                            $eventName = $row['EventName']; 
                            $eventID = $row['ID'];
                        ?>
                        <td name="eventName"><?php echo "<a href=eventInfo.php?event=".$eventID.">".$eventName."</a>" ?></td>
                        <td name="accessKey"><?php echo $row['AccessKey'] ?></td>
                        <td name="activeDate"><?php echo (new DateTime($row['ActiveDate']))->format('Y-m-d') ?></td>
                        <td name="expiredDate"><?php echo (new DateTime($row['ExpiredDate']))->format('Y-m-d') ?></td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0 py-0 edit_data noneditable" type="button">Edit</button>
                            <button class="btn btn-sm btn-primary btn-flat rounded-0 px-2 py-0 editable">Save</button>
                            <button class="btn btn-sm btn-dark btn-flat rounded-0 px-2 py-0 editable" onclick="cancel_button($(this))" type="button">Cancel</button></td>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
            <!-- Table Form end -->
        </div>
        <div class="w-100 d-flex pposition-relative justify-content-center">
            <button class="btn btn-flat btn-primary" id="add_event" type="button">Add New Event</button>
        </div>
    </div>
</div>
<?php require "_footer.php" ?>
<?php require "_adminSessionHeader.php" ?> 
<?php require_once '_incFunctions.php' ?>
<?php
// Get current page and search parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = sanitizeHTML(isset($_GET['search']) ? trim($_GET['search']) : '');
$userType =sanitizeHTML(isset($_GET['userType']) ? $_GET['userType'] : '');
$itemsPerPage = 20;
$offset = ($page - 1) * $itemsPerPage;

// Build query based on filters
$whereClause = [];
$params = [];
$types = "";

if (!empty($search)) {
    $whereClause[] = "Email LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

if (!empty($userType)) {
    $whereClause[] = "UserType = ?";
    $params[] = $userType;
    $types .= "s";
}

$where = !empty($whereClause) ? "WHERE " . implode(" AND ", $whereClause) : "";

// Get total count for pagination
$countSql = "SELECT COUNT(*) as total FROM user $where";
if (!empty($params)) {
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'];
} else {
    $total = $conn->query($countSql)->fetch_assoc()['total'];
}

$totalPages = ceil($total / $itemsPerPage);

// Get filtered users
$sql = "SELECT * FROM user $where ORDER BY Email LIMIT ? OFFSET ?";
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $params[] = $itemsPerPage;
    $params[] = $offset;
    $types .= "ii";
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $itemsPerPage, $offset);
    $stmt->execute();
    $query = $stmt->get_result();
}
?>

<script>
function limitTextLength(event, maxLength) {
    if (event.target.textContent.length >= maxLength && !isSpecialKey(event)) {
        event.preventDefault();
    }
}

function isSpecialKey(event) {
    return event.ctrlKey && (event.key === 'a' || event.key === 'x') || isNavigationKey(event.key);
}

function isNavigationKey(key) {
    return key.includes('Arrow') || key === 'Backspace' || key === 'Delete' || key === 'Home' || key === 'End';
}

function showMessage(message, isSuccess = true) {
    $('#modalMessage')
        .text(message)
        .removeClass('text-danger text-success')
        .addClass(isSuccess ? 'text-success' : 'text-danger');
    $('#messageModal').modal('show');
    
    if (isSuccess) {
        $('#messageModal').on('hidden.bs.modal', function () {
            location.reload();
        });
    }
    return false;
}

$(function() {
    // Create New Row
    $('#add_user').click(function() {
        if ($('tr[data-id=""]').length > 0) {
            $('tr[data-id=""]').find('[name="email"]').focus()
            return false;
        }
        var tr = $('<tr>')
        $('input[name="id"]').val('')
        tr.addClass('py-1 px-2');
        tr.attr('data-id', '');
        tr.append('<td contenteditable name="email" onkeydown="limitTextLength(event, 100)"></td>')
        tr.append('<td contenteditable name="password" onkeydown="limitTextLength(event, 100)"></td>')
        tr.append('<td><select name="userType" class="form-control"><option value="parent">Parent</option><option value="student">Student</option></select></td>')
        tr.append('<td><select name="gradeId" class="form-control grade-select"></select></td>')
        tr.append('<td name="isAdmin" class="text-center"><input type="checkbox" class="isAdmin-checkbox"></td>')
        tr.append('<td class="text-center"><button class="btn btn-sm btn-primary btn-flat rounded-0 px-2 py-0">Save</button><button class="btn btn-sm btn-dark btn-flat rounded-0 px-2 py-0" onclick="cancel_button($(this))" type="button">Cancel</button></td>')
        $('#form-tbl').append(tr)
        loadGrades(tr.find('.grade-select'))
        tr.find('[name="email"]').focus()
    })

    // Load grades for select
    function loadGrades(selectElement) {
        const currentValue = selectElement.val();
        $.ajax({
            url: 'api/getGrades.php',
            method: 'GET',
            dataType: 'json',
            success: function(grades) {
                selectElement.empty();
                selectElement.append('<option value="0">N/A</option>');
                grades.forEach(grade => {
                    selectElement.append(`<option value="${grade.GradeID}" 
                        ${currentValue == grade.GradeID ? 'selected' : ''}>
                        ${grade.GradeName}
                    </option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading grades:', error);
                showMessage('Error loading grade levels', false);
            }
        });
    }

    // Edit Row
    $('.edit_data').click(function() {
        var tr = $(this).closest('tr');
        var id = tr.attr('data-id');
        $('input[name="id"]').val(id);
        
        // Convert email and password cells to input fields
        var emailCell = tr.find('[name="email"]');
        var passwordCell = tr.find('[name="password"]');
        
        var emailValue = emailCell.find('a').length ? emailCell.find('a').text() : emailCell.text();
        var passwordValue = passwordCell.text();
        
        emailCell.html(`<input type="text" class="form-control" value="${emailValue}" maxlength="100">`);
        passwordCell.html(`<input type="text" class="form-control" value="${passwordValue}" maxlength="100">`);
        
        // Enable other fields
        tr.find('select').prop('disabled', false);
        tr.find('.isAdmin-checkbox').prop('disabled', false);
        
        // Load grades for student type
        if (tr.find('[name="userType"]').val() === 'student') {
            loadGrades(tr.find('.grade-select'));
        }
        
        tr.find('.editable').show('fast');
        tr.find('.noneditable').hide('fast');
    });

    // Handle user type change
    $(document).on('change', 'select[name="userType"]', function() {
        var tr = $(this).closest('tr');
        var gradeSelect = tr.find('.grade-select');
        
        if ($(this).val() === 'student') {
            loadGrades(gradeSelect);
            gradeSelect.prop('disabled', false);
        } else {
            gradeSelect.html('<option value="0">N/A</option>');
            gradeSelect.prop('disabled', true);
        }
    });

    // Form submission
    $('#form-data').submit(function(e) {
        e.preventDefault();
        var id = $('input[name="id"]').val();
        var tr = $('tr[data-id="' + (id || '') + '"]');
        var data = {
            id: id,
            email: tr.find('[name="email"] input').val() || tr.find('[name="email"]').text(),
            password: tr.find('[name="password"] input').val() || tr.find('[name="password"]').text(),
            userType: tr.find('[name="userType"]').val(),
            gradeId: tr.find('[name="gradeId"]').val(),
            isAdmin: tr.find('.isAdmin-checkbox').is(':checked') ? 1 : 0
        };


        if (!data.password || data.password.length < 4) {
            showMessage("Password must be at least 4 characters long.", false);
            return false;
        }

        if (data.userType === 'student' && data.gradeId === '0') {
            showMessage("Please select a grade for student accounts.", false);
            return false;
        }
        var delay=1;
        // Enhanced validation
        if (!data.email || !validateEmail(data.email)) {
            showMessage("Warning, User not have a valid email address....", false);
           // return false;
           delay=3000;
           
        }

        setTimeout( ()=>{
        $.ajax({
            url: "api/userAdminAPI.php",
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                showMessage(res.message, res.success);
            },
            error: function(err) {
                showMessage('An error occurred while saving the data!', false);
                console.log(err);
            }
        })},delay);
    });
});

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Cancel button handler
window.cancel_button = function(_this) {
    var tr = _this.closest('tr');
    if (tr.attr('data-id') == '') {
        tr.remove()
    } else {
        $('input[name="id"]').val('');
        
        // Restore original email and password display
        var emailCell = tr.find('[name="email"]');
        var passwordCell = tr.find('[name="password"]');
        var emailInput = emailCell.find('input');
        var passwordInput = passwordCell.find('input');
        
        if (emailInput.length) {
            emailCell.text(emailInput.val());
        }
        if (passwordInput.length) {
            passwordCell.text(passwordInput.val());
        }
        
        // Disable other fields
        tr.find('select').prop('disabled', true);
        tr.find('.isAdmin-checkbox').prop('disabled', true);
        tr.find('.editable').hide('fast');
        tr.find('.noneditable').show('fast');
    }
}
</script>

<style>
#messageModal .modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

#messageModal .modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

#messageModal .modal-body {
    padding: 20px;
}

#modalMessage {
    margin: 0;
    font-size: 16px;
    padding: 10px;
}

.grade-select {
    min-width: 100px;
}

.pagination {
    justify-content: center;
    margin-top: 20px;
}

#filterForm {
    margin-bottom: 20px;
}

.form-inline > * {
    margin-right: 10px;
}

td[contenteditable="true"] {
    background-color: #fff;
    padding: 5px;
    border: 1px solid #ddd;
}

td[contenteditable="true"]:focus {
    outline: 2px solid #007bff;
    border: 1px solid #007bff;
}

td[name="email"] input,
td[name="password"] input {
    width: 100%;
    padding: 2px 5px;
    border: 1px solid #ddd;
    border-radius: 3px;
}

td[name="email"] input:focus,
td[name="password"] input:focus {
    outline: 2px solid #007bff;
    border-color: #007bff;
}

#messageModal .text-success {
    color: #28a745 !important;
    font-weight: 500;
}

#messageModal .text-danger {
    color: #dc3545 !important;
    font-weight: 500;
}
</style>

<div class="container">
    <h2>User Management</h2>
    <p class="alert alert-info"><b>Note to admin</b>:<br>
    - Student accounts must have a grade level selected<br>
    - Parent accounts can manage multiple students<br>
    - Admin users can access admin pages</p>

    <div class="row mb-3">
        <div class="col-md-8">
            <form id="filterForm" class="form-inline">
                <input type="text" class="form-control mr-2" name="search" placeholder="Search email..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <select class="form-control mr-2" name="userType">
                    <option value="">All Types</option>
                    <option value="parent" <?php echo $userType === 'parent' ? 'selected' : ''; ?>>Parent</option>
                    <option value="student" <?php echo $userType === 'student' ? 'selected' : ''; ?>>Student</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="useradmin.php" class="btn btn-secondary ml-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="" id="form-data">
                <input type="hidden" name="id" value="">
                <table id="form-tbl">
                    <colgroup>
                        <col width="25%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center p-1">Email</th>
                            <th class="text-center p-1">Password</th>
                            <th class="text-center p-1">User Type</th>
                            <th class="text-center p-1">Grade</th>
                            <th class="text-center p-1">Admin</th>
                            <th class="text-center p-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    while($row = $query->fetch_assoc()):
                        $gradeQuery = $conn->query("SELECT GradeName FROM grade WHERE GradeID = " . $row['GradeID']);
                        $gradeName = ($gradeQuery && $row['GradeID'] > 0) ? $gradeQuery->fetch_assoc()['GradeName'] : 'N/A';
                    ?>
                    <tr data-id='<?php echo $row['ID'] ?>'>
                        <td name="email" onkeydown="limitTextLength(event, 100)">
                            <?php if ($row['Email']): ?>
                                <a href="userDetail.php?id=<?php echo $row['ID']; ?>"><?php echo $row['Email']; ?></a>
                            <?php endif; ?>
                        </td>
                        <td name="password" onkeydown="limitTextLength(event, 100)"><?php echo $row['Password'] ?></td>
                        <td>
                            <select name="userType" class="form-control" disabled>
                                <option value="parent" <?php echo $row['UserType'] == 'parent' ? 'selected' : '' ?>>Parent</option>
                                <option value="student" <?php echo $row['UserType'] == 'student' ? 'selected' : '' ?>>Student</option>
                            </select>
                        </td>
                        <td>
                            <select name="gradeId" class="form-control grade-select" disabled>
                                <option value="<?php echo $row['GradeID'] ?>"><?php echo $gradeName ?></option>
                            </select>
                        </td>
                        <td name="isAdmin" class="text-center">
                            <input type="checkbox" class="isAdmin-checkbox" <?php echo $row['IsAdmin'] ? 'checked' : ''; ?> 
                                   disabled>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0 py-0 edit_data noneditable" type="button">Edit</button>
                            <button class="btn btn-sm btn-primary btn-flat rounded-0 px-2 py-0 editable">Save</button>
                            <button class="btn btn-sm btn-dark btn-flat rounded-0 px-2 py-0 editable" onclick="cancel_button($(this))" type="button">Cancel</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="w-100 d-flex position-relative justify-content-center mt-3">
            <button class="btn btn-flat btn-primary" id="add_user" type="button">Add New User</button>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page-1); ?>&search=<?php echo urlencode($search); ?>&userType=<?php echo urlencode($userType); ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&userType=<?php echo urlencode($userType); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page+1); ?>&search=<?php echo urlencode($search); ?>&userType=<?php echo urlencode($userType); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<?php require "_footer.php" ?> 
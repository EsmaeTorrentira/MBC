<?php
session_start();
include 'db.php';

// Restrict access
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Initialize
$showEditForm = false;
$editRecord = null;

// Search term
$search = $_GET['search'] ?? '';

// Sorting
$orderBy = "ORDER BY year_enrolled DESC, lastname ASC";
$sortOption = $_GET['sort'] ?? '';
switch ($sortOption) {
    case "year_asc":
        $orderBy = "ORDER BY year_enrolled ASC, lastname ASC";
        break;
    case "year_desc":
        $orderBy = "ORDER BY year_enrolled DESC, lastname ASC";
        break;
    case "lastname_asc":
        $orderBy = "ORDER BY lastname ASC";
        break;
    case "lastname_desc":
        $orderBy = "ORDER BY lastname DESC";
        break;
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch student records
$sql = "SELECT * FROM students 
        WHERE LOWER(lastname) LIKE LOWER(:search) 
           OR LOWER(firstname) LIKE LOWER(:search)
        $orderBy
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total records for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM students 
                            WHERE LOWER(lastname) LIKE LOWER(:search) 
                               OR LOWER(firstname) LIKE LOWER(:search)");
$countStmt->execute([':search' => "%$search%"]);
$totalRecords = $countStmt->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Handle delete
if (isset($_POST['delete_id'])) {
    $deleteStmt = $pdo->prepare("DELETE FROM students WHERE student_id = :id");
    $deleteStmt->execute(['id' => $_POST['delete_id']]);
    $_SESSION['success'] = "Record deleted successfully!";
    header("Location: read.php");
    exit();
}

// Handle edit modal
if (isset($_POST['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = :id");
    $stmt->execute(['id' => $_POST['edit_id']]);
    $editRecord = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($editRecord) $showEditForm = true;
    else $_SESSION['error'] = "Record not found!";
}

// Handle update
if (isset($_POST['update'])) {
    $updateStmt = $pdo->prepare("UPDATE students SET 
        lastname = :lastname,
        firstname = :firstname,
        year_enrolled = :year_enrolled,
        year_graduated = :year_graduated,
        status = :status,
        school_name = :school_name
        WHERE student_id = :id");
    $updateStmt->execute([
        'id' => $_POST['id'],
        'lastname' => $_POST['lastname'],
        'firstname' => $_POST['firstname'],
        'year_enrolled' => $_POST['year_enrolled'],
        'year_graduated' => $_POST['year_graduated'],
        'status' => $_POST['status'],
        'school_name' => $_POST['school_name']
    ]);
    $_SESSION['success'] = "Record updated successfully!";
    header("Location: read.php");
    exit();
}

// Messages
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

// Dynamic numbering start
$number = $offset + 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Records</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
body { font-family:'Segoe UI',sans-serif; background:#f8f9fa; }
.app-container { max-width:1400px; margin:20px auto; }
.header { background:linear-gradient(135deg,#2c3e50,#3498db); color:white; padding:20px; border-radius:8px; text-align:center; margin-bottom:20px; }
.data-table { background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 15px rgba(0,0,0,0.08); }
.table thead { background:linear-gradient(135deg,#3498db,#2c3e50); color:white; }
.table th { cursor:pointer; }
.action-btn { padding:5px 10px; border-radius:4px; font-size:0.85rem; }
.btn-edit { background:#f39c12; color:white; border:none; }
.btn-delete { background:#e74c3c; color:white; border:none; }
.btn-primary-custom { background:#3498db; color:white; border:none; }
.table-hover tbody tr:hover { background-color: rgba(52,152,219,0.05); }
</style>
</head>
<body>
<div class="app-container">

<div class="header">
    <h1><i class="bi bi-people-fill"></i> Student Records</h1>
    <p>Manage your student information</p>
</div>

<?php if($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i> <?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Buttons & Search -->
<div class="d-flex justify-content-between mb-3">
    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-house-door"></i> Home</a>
        <a href="create.php" class="btn btn-primary-custom"><i class="bi bi-plus-lg"></i> Add New</a>
        <a href="read.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
    </div>
    <form method="GET" class="d-flex gap-2">
        <input type="text" class="form-control" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
        <select class="form-select" name="sort" onchange="this.form.submit()">
            <option value="">Sort by</option>
            <option value="year_asc" <?= $sortOption=='year_asc'?'selected':'' ?>>Year Enrolled ↑</option>
            <option value="year_desc" <?= $sortOption=='year_desc'?'selected':'' ?>>Year Enrolled ↓</option>
            <option value="lastname_asc" <?= $sortOption=='lastname_asc'?'selected':'' ?>>Lastname A-Z</option>
            <option value="lastname_desc" <?= $sortOption=='lastname_desc'?'selected':'' ?>>Lastname Z-A</option>
        </select>
    </form>
</div>

<!-- Data Table -->
<div class="data-table table-responsive">
<table class="table table-hover table-bordered">
<thead>
<tr>
<th>#</th>
<th>Lastname</th>
<th>Firstname</th>
<th>Year Enrolled</th>
<th>Year Graduated</th>
<th>Status</th>
<th>School Name</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if($records && count($records)>0): ?>
<?php foreach($records as $record): ?>
<tr>
<td><?= $number++ ?></td>
<td><?= htmlspecialchars($record['lastname']) ?></td>
<td><?= htmlspecialchars($record['firstname']) ?></td>
<td><?= htmlspecialchars($record['year_enrolled']) ?></td>
<td><?= htmlspecialchars($record['year_graduated']) ?></td>
<td><?= htmlspecialchars($record['status']) ?></td>
<td><?= htmlspecialchars($record['school_name']) ?></td>
<td>
    <form method="POST" style="display:inline;">
        <input type="hidden" name="edit_id" value="<?= $record['student_id'] ?>">
        <button class="btn btn-edit action-btn"><i class="bi bi-pencil-square"></i></button>
    </form>
    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this record?');">
        <input type="hidden" name="delete_id" value="<?= $record['student_id'] ?>">
        <button class="btn btn-delete action-btn"><i class="bi bi-trash"></i></button>
    </form>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="8" class="text-center py-4">No records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

<!-- Pagination -->
<?php if($totalPages>1): ?>
<nav class="mt-3"><ul class="pagination justify-content-center">
<?php for($i=1;$i<=$totalPages;$i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
<a class="page-link" href="?search=<?= urlencode($search) ?>&sort=<?= $sortOption ?>&page=<?= $i ?>"><?= $i ?></a>
</li>
<?php endfor; ?>
</ul></nav>
<?php endif; ?>

</div>

<!-- Edit Modal -->
<?php if($showEditForm && $editRecord): ?>
<div class="modal fade show" style="display:block; background: rgba(0,0,0,0.5);">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST">
<div class="modal-header">
<h5 class="modal-title">Edit Student #<?= $editRecord['student_id'] ?></h5>
<a href="read.php" class="btn-close"></a>
</div>
<div class="modal-body">
<input type="hidden" name="id" value="<?= $editRecord['student_id'] ?>">
<label>Lastname</label>
<input type="text" class="form-control mb-2" name="lastname" value="<?= htmlspecialchars($editRecord['lastname']) ?>" required>
<label>Firstname</label>
<input type="text" class="form-control mb-2" name="firstname" value="<?= htmlspecialchars($editRecord['firstname']) ?>" required>
<label>Year Enrolled</label>
<input type="number" class="form-control mb-2" name="year_enrolled" value="<?= $editRecord['year_enrolled'] ?>">
<label>Year Graduated</label>
<input type="number" class="form-control mb-2" name="year_graduated" value="<?= $editRecord['year_graduated'] ?>">
<label>Status</label>
<input type="text" class="form-control mb-2" name="status" value="<?= $editRecord['status'] ?>">
<label>School Name</label>
<input type="text" class="form-control mb-2" name="school_name" value="<?= htmlspecialchars($editRecord['school_name']) ?>">
</div>
<div class="modal-footer">
<a href="read.php" class="btn btn-secondary">Cancel</a>
<button type="submit" name="update" class="btn btn-primary-custom">Save Changes</button>
</div>
</form>
</div>
</div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

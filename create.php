<?php
require_once "db.php"; // ✅ makes sure $pdo is available

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = $_POST['lastname'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $year_enrolled = $_POST['year_enrolled'] ?? '';
    $year_graduated = $_POST['year_graduated'] ?? '';
    $status = $_POST['status'] ?? '';
    $school_name = $_POST['school_name'] ?? '';

    try {
        $sql = "INSERT INTO students (lastname, firstname, year_enrolled, year_graduated, status, school_name, date_registered) 
                VALUES (:lastname, :firstname, :year_enrolled, :year_graduated, :status, :school_name, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':lastname' => $lastname,
            ':firstname' => $firstname,
            ':year_enrolled' => $year_enrolled,
            ':year_graduated' => $year_graduated,
            ':status' => $status,
            ':school_name' => $school_name
        ]);

        $message = "✅ Student successfully added!";
    } catch (PDOException $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            text-align: center;
            padding: 30px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
        }
        input, select, button {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #2ecc71;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #27ae60;
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            text-decoration: none;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            margin: 5px;
        }
        .nav a:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">🏠 Home</a>
        <a href="create.php">➕ Add Student</a>
        <a href="read.php">📋 View Students</a>
    </div>

    <div class="container">
        <h2>Add Student Information</h2>
        <form method="POST">
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="year_enrolled" placeholder="Year Enrolled" required>
            <input type="text" name="year_graduated" placeholder="Year Graduated or Status (e.g. Undergrad)" required>
            <input type="text" name="status" placeholder="Status (Graduate/Undergrad/etc.)" required>
            <input type="text" name="school_name" placeholder="School Name" required>
            <button type="submit">Save Student</button>
        </form>
        <div class="message"><?php echo $message; ?></div>
    </div>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'];
$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village of Hope - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
    :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        body {
      
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            color: #fff;
          
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #1c1c1c, #333);
            color: #fff;
            position: fixed;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.4);
        }

        .sidebar img.logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #fff;
        }

        .sidebar h4 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            width: 90%;
            padding: 12px;
            text-decoration: none;
            font-size: 16px;
            color: white;
            text-align: center;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #505050;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #707070;
            transform: translateX(5px);
        }

        .main-content {
          
            margin-left: 250px;
            padding: 30px;
            flex-grow: 1;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            height: 100vh;
            overflow: auto;
            backdrop-filter: brightness(0.85);
            background-color: white;
        }

        .main-content h2 {
            font-size: 50px;
            color: black;
            font-family: times new roman;
        }
        
        
        .main-content p {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            color: #f1f1f1;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
           background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            transition: 0.3s ease;
            color: #eee;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.6);
        }

        .card i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #17a2b8;
        }

        .logout {
            margin-top: auto;
            width: 90%;
            padding: 12px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-align: center;
            transition: 0.3s;
        }

        .logout:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar a {
                font-size: 14px;
                padding: 10px;
            }

            .sidebar h4, .sidebar img.logo {
                display: none;
            }

            .main-content {
                margin-left: 60px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="en.jfif" alt="Endeavor Logo" class="logo">
        <h4>Endeavor Institute of International Leadership</h4>

       
            <a href="create.php">Register Student</a>
            <a href="read.php">View Records</a>
            
         
        

        <a href="logout.php" class="logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, <strong><?= htmlspecialchars($username) ?>!</strong></h2>
        

        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-user-friends"></i>
                <h4>Mission</h4>
                <p>Our mission is to create a community where orphaned, abandoned, and abused children find 
                Faith, Hope, and Healing in New Families</p>
            </div>
            <div class="card">
                <i class="fas fa-home"></i>
                <h4>Vision</h4>
                <p>We envision a community where generations of once-at-risk children have found Faith
                , Family, Hope and Healing</p>
            </div>
            <div class="card">
                <i class="fas fa-hand-holding-usd"></i>
                <h4>Goal</h4>
                <p>To help children find a Family that will nurture not just it's Physical, but also Spiritual well-being.</p>
            </div>
        </div>
    </div>

</body>
</html>

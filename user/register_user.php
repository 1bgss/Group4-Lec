<?php
include '../db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'user'; 

    $stmt = $koneksi->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if (!$stmt->execute([$name, $email, $password, $role])) {
        echo "Error: " . implode(", ", $stmt->errorInfo());
        exit();
    }

    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            filter: blur(8px);
        }

        .slideshow {
            position: absolute;
            width: 100%;
            height: 100%;
            animation: fade 20s infinite;
        }

        @keyframes fade {
            0% { opacity: 1; }
            10% { opacity: 1; }
            20% { opacity: 0; }
            30% { opacity: 0; }
            40% { opacity: 1; }
            50% { opacity: 1; }
            60% { opacity: 0; }
            70% { opacity: 0; }
            80% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        .image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 1s ease-in-out;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            z-index: 1; /* Make sure the form is above the background */
        }
        

h1 {
    font-size: 2.5rem; 
    color: #333; 
    margin-bottom: 20px; 
}

.form-group label {
    font-weight: bold; 
    color: #555; 
}

.form-control {
    border: 2px solid #007bff; 
    border-radius: 5px; 
    padding: 10px; 
    transition: border-color 0.3s; 
}

.form-control:focus {
    border-color: #0056b3; 
    box-shadow: 0 0 5px rgba(0, 86, 179, 0.5); 
}

.btn-primary {
    background-color: #007bff; 
    border: none; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 1.2rem; 
    transition: background-color 0.3s, transform 0.3s; 
}

.btn-primary:hover {
    background-color: #0056b3; 
    transform: translateY(-2px); 
}

    </style>
</head>
<body>
    <div class="background">
        <div class="slideshow">
            <img src="https://i.pinimg.com/736x/03/3c/7d/033c7d7ab0d35fe4572e67ac0a81513d.jpg" class="image" style="opacity: 1;">
            <img src="https://i.pinimg.com/736x/fa/94/50/fa9450af8ac853a09581220b21290b09.jpg" class="image" style="opacity: 0;">
            <img src="https://i.pinimg.com/736x/88/35/c2/8835c2b9c6578f20328dbe55b5cd5088.jpg" class="image" style="opacity: 0;">
        </div>
    </div>
    <div class="container mt-5">
        <h1 class="text-center">Register as User</h1>
        <form action="register_user.php" method="POST" class="form-group">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <script>
        const images = document.querySelectorAll('.image');
        let current = 0;

        function showNextImage() {
            images[current].style.opacity = 0;
            current = (current + 1) % images.length;
            images[current].style.opacity = 1;
        }

        setInterval(showNextImage, 5000); 
    </script>
</body>
</html>

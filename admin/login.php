<?php
session_start();

// In login.php, modify the successful login block:
//    if (password_verify($password, $user['password_hash'])) {
 //       $_SESSION['admin_logged_in'] = true;
 //       $_SESSION['admin_username'] = $user['username'];
       // $_SESSION['admin_user_id'] = $user['id']; // THIS IS THE CRUCIAL LINE
 //       header('Location: blog.php');
  //      exit;
  //  }

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: blog.php');
    exit;
}


// In your login script (where admin logs in), make sure to set:
//$_SESSION['admin_user_id'] = $user['id']; // Where $user comes from your database

require "../config/config.php";

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $db->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $result = $db->query("SELECT * FROM admin_users WHERE username = '$username'");
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            header('Location: blog.php');
            exit;
        }
    }
    
    $error = 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Retroviral Solution</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <img src="../img/logo-1.png" class="w-20 h-20 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
                <p class="text-gray-600">Access the blog management panel</p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php $db->close(); ?>
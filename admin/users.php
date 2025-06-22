<?php
require_once 'auth_check.php';
require '../config/config.php';

// Add this check at the top
//if (!isset($_SESSION['admin_user_id'])) {
//    die('Unauthorized access - user ID not set');
//}

// Improved session check
if (!isset($_SESSION['admin_logged_in']) ) {  //|| !isset($_SESSION['admin_user_id'])
    header('Location: login.php');
    exit;
}


// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        // Prevent deleting yourself
        if ($id !== $_SESSION['admin_logged_in']) {
            $db->query("DELETE FROM admin_users WHERE id = $id");
            $_SESSION['message'] = 'User deleted successfully';
        }
    } elseif (isset($_POST['add_user'])) {
        $username = $db->real_escape_string($_POST['username']);
        $email = $db->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $db->query("INSERT INTO admin_users (username, email, password_hash) VALUES ('$username', '$email', '$password')");
        $_SESSION['message'] = 'User added successfully';
    }
    
    header('Location: users.php');
    exit;
}

// Get all users
$users = $db->query("SELECT * FROM admin_users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Manage Admin Users</h1>
                    <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" 
                            class="bg-primary hover:bg-blue-400 text-dark px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i> Add User
                    </button>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
    <div class="text-sm font-medium text-gray-900">
        <?php echo htmlspecialchars($user['username']); ?>
        <?php if (isset($_SESSION['admin_logged_in']) && $user['id'] === $_SESSION['admin_logged_in']): ?>
            <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">You</span>
        <?php endif; ?>
    </div>
</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($user['id'] !== $_SESSION['admin_logged_in']): ?>
                                            <form action="users.php" method="POST" class="inline">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" name="delete" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Add New Admin User</h3>
                <button onclick="document.getElementById('addUserModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required minlength="8"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" name="add_user" class="bg-primary hover:bg-blue-400 border border-blue-600 text-dark px-6 py-2 rounded-lg">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php $db->close(); ?>
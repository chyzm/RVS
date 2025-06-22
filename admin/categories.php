<?php
require_once 'auth_check.php';
require "../config/config.php";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $db->query("DELETE FROM blog_categories WHERE id = $id");
        $_SESSION['message'] = 'Category deleted successfully';
    } else {
        $name = $db->real_escape_string($_POST['name']);
        $slug = $db->real_escape_string(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)));
        
        if (isset($_POST['id'])) {
            // Update existing category
            $id = intval($_POST['id']);
            $db->query("UPDATE blog_categories SET name = '$name', slug = '$slug' WHERE id = $id");
            $_SESSION['message'] = 'Category updated successfully';
        } else {
            // Create new category
            $db->query("INSERT INTO blog_categories (name, slug) VALUES ('$name', '$slug')");
            $_SESSION['message'] = 'Category created successfully';
        }
    }
    
    header('Location: categories.php');
    exit;
}

// Get all categories
$categories = $db->query("SELECT c.*, COUNT(p.id) as post_count 
                         FROM blog_categories c
                         LEFT JOIN blog_posts p ON c.id = p.category_id
                         GROUP BY c.id
                         ORDER BY c.name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | Admin Panel</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Manage Categories</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                        <a href="category_edit.php" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i> New Category
                        </a>
                        <a href="blog.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-newspaper mr-2"></i> Blog Posts
                        </a>
                    </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($category = $categories->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($category['slug']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $category['post_count']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="category_edit.php?id=<?php echo $category['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-4">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="categories.php" method="POST" class="inline">
                                            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                            <button type="submit" name="delete" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this category?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $db->close(); ?>
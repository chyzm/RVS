<?php
require_once 'auth_check.php';
require "../config/config.php";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        // Delete from junction table first
        $db->query("DELETE FROM blog_post_categories WHERE post_id = $id");
        // Then delete the post
        $db->query("DELETE FROM blog_posts WHERE id = $id");
        $_SESSION['message'] = 'Post deleted successfully';
    } else {
        $title = $db->real_escape_string($_POST['title']);
        $slug = $db->real_escape_string(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)));
        $content = $db->real_escape_string($_POST['content']);
        $excerpt = $db->real_escape_string($_POST['excerpt']);
        $categories = isset($_POST['categories']) ? $_POST['categories'] : [];
        
        // Handle file upload
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../img/';
            $file_name = uniqid() . '_' . basename($_FILES['featured_image']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file)) {
                $featured_image = $file_name;
            }
        }
        
        if (isset($_POST['id'])) {
            // Update existing post
            $id = intval($_POST['id']);
            $image_sql = $featured_image ? ", featured_image = '$featured_image'" : '';
            
            $query = "UPDATE blog_posts SET title = '$title', slug = '$slug', content = '$content', excerpt = '$excerpt' $image_sql WHERE id = $id";
            $db->query($query);
            
            // Update categories - delete existing associations first
            $db->query("DELETE FROM blog_post_categories WHERE post_id = $id");
            
            // Insert new category associations
            foreach ($categories as $category_id) {
                $category_id = intval($category_id);
                $db->query("INSERT INTO blog_post_categories (post_id, category_id) VALUES ($id, $category_id)");
            }
            
            $_SESSION['message'] = 'Post updated successfully';
        } else {
            // Create new post
            $query = "INSERT INTO blog_posts (title, slug, content, excerpt, featured_image) VALUES ('$title', '$slug', '$content', '$excerpt', '$featured_image')";
            $db->query($query);
            $post_id = $db->insert_id;
            
            // Insert category associations
            foreach ($categories as $category_id) {
                $category_id = intval($category_id);
                $db->query("INSERT INTO blog_post_categories (post_id, category_id) VALUES ($post_id, $category_id)");
            }
            
            $_SESSION['message'] = 'Post created successfully';
        }
    }
    
    header('Location: blog.php');
    exit;
}

// Pagination setup
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total count of posts
$total_query = "SELECT COUNT(*) as total FROM blog_posts";
$total_result = $db->query($total_query);
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $per_page);

// Get paginated posts with category information
$query = "SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as category_name
          FROM blog_posts p
          LEFT JOIN blog_post_categories pc ON p.id = pc.post_id
          LEFT JOIN blog_categories c ON pc.category_id = c.id
          GROUP BY p.id
          ORDER BY p.created_at DESC 
          LIMIT $offset, $per_page";
$posts = $db->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog | Admin Panel</title>
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
                    <h1 class="text-2xl font-bold text-gray-800">Manage Blog Posts</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                        <a href="blog_edit.php" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i> New Post
                        </a>
                        <a href="categories.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-tags mr-2"></i> Categories
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($post = $posts->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <?php if ($post['featured_image']): ?>
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="../img/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="">
                                                </div>
                                            <?php endif; ?>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($post['title']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $post['category_name'] ? htmlspecialchars($post['category_name']) : 'Uncategorized'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="blog_edit.php?id=<?php echo $post['id']; ?>" class="text-blue-600 hover:text-blue-900 mr-4">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="blog.php" method="POST" class="inline">
                                            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" name="delete" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this post?');">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to <span class="font-medium"><?php echo min($offset + $per_page, $total_posts); ?></span> of <span class="font-medium"><?php echo $total_posts; ?></span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=<?php echo $page - 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++): ?>
                                        <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'bg-primary border-primary text-white' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'; ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <a href="?page=<?php echo $page + 1; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $db->close(); ?>
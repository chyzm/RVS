<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require "../config/config.php";

$post = null;
$selected_categories = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $db->query("SELECT * FROM blog_posts WHERE id = $id");
    $post = $result->fetch_assoc();
    
    // Get selected categories for this post
    if ($post) {
        $cat_result = $db->query("SELECT category_id FROM blog_post_categories WHERE post_id = $id");
        while ($cat = $cat_result->fetch_assoc()) {
            $selected_categories[] = $cat['category_id'];
        }
    }
}

// Get all categories
$categories = $db->query("SELECT * FROM blog_categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? 'Edit' : 'Create'; ?> Blog Post | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/rhkzuwd5m8r22l1l4ky66yzcqzwhex28z6gjbyv055gyi9lt/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE
    tinymce.init({
        selector: '#content',
        height: 400,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });

    // Handle form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Force TinyMCE to save content
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                tinymce.get('content').save();
            }
            
            // Validate required fields
            const title = document.getElementById('title');
            if (title && !title.value.trim()) {
                e.preventDefault();
                alert('Title is required');
                title.focus();
                return false;
            }
            
            return true;
        });
    }
});
</script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-2xl font-bold text-gray-800 mb-8"><?php echo $post ? 'Edit' : 'Create New'; ?> Blog Post</h1>
                
                <form method="POST" action="blog.php" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
                    <?php if (isset($post) && $post): ?>
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                    <?php endif; ?>
                                
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="title" name="title" required 
                               value="<?php echo $post ? htmlspecialchars($post['title']) : ''; ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    
                    <div class="mb-6">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt (Short Description)</label>
                        <textarea id="excerpt" name="excerpt" rows="3" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo $post ? htmlspecialchars($post['excerpt']) : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <textarea id="content" name="content" rows="10" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?php echo $post ? htmlspecialchars($post['content']) : ''; ?></textarea>
                    </div>
                    

                    <!-- Category -->
                    <div class="mb-6">
                        <label for="categories" class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                        <select id="categories" name="categories[]" multiple size="6" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <?php 
                            // Reset the categories query
                            $categories_result = $db->query("SELECT * FROM blog_categories ORDER BY name");
                            
                            if ($categories_result && $categories_result->num_rows > 0) {
                                while ($category = $categories_result->fetch_assoc()): 
                                    $is_selected = in_array($category['id'], $selected_categories);
                                    ?>
                                    <option value="<?php echo $category['id']; ?>" 
                                            <?php echo $is_selected ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endwhile;
                            } else {
                                echo '<option value="" disabled>No categories available</option>';
                            }
                            ?>
                            
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Hold Ctrl (Cmd on Mac) to select multiple categories
                            <?php if (!empty($selected_categories)): ?>
                                | Selected: <?php echo implode(', ', $selected_categories); ?>
                            <?php endif; ?>
                        </p>
                        <?php if ($categories_result && $categories_result->num_rows == 0): ?>
                            <p class="text-sm text-orange-600 mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                No categories available. <a href="categories.php" class="text-primary hover:underline">Create some categories first</a>.
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php
                    // Debug section (add ?debug=1 to URL to see debug info)
                    if (isset($_GET['debug']) && $_GET['debug'] == '1') {
                        echo "<div class='mb-6 bg-gray-50 p-4 rounded-lg border'>";
                        echo "<h3 class='font-medium text-gray-700 mb-2'>Debug Information:</h3>";
                        
                        if ($post) {
                            echo "<p><strong>Post Data:</strong></p>";
                            echo "<pre class='text-xs bg-gray-100 p-2 rounded'>" . print_r($post, true) . "</pre>";
                        } else {
                            echo "<p>No post data (creating new post)</p>";
                        }
                        
                        echo "<p class='mt-2'><strong>Selected Categories:</strong> " . implode(', ', $selected_categories) . "</p>";
                        
                        echo "<p class='mt-2'><strong>Available Categories:</strong></p>";
                        $debug_categories = $db->query("SELECT * FROM blog_categories ORDER BY name");
                        if ($debug_categories) {
                            echo "<ul class='text-sm'>";
                            while ($cat = $debug_categories->fetch_assoc()) {
                                echo "<li>ID: " . $cat['id'] . " - Name: " . htmlspecialchars($cat['name']) . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No categories found or query failed</p>";
                        }
                        
                        echo "</div>";
                    }
                    ?>
                    
                    <div class="mb-6">
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                        <?php if ($post && $post['featured_image']): ?>
                            <div class="mb-2">
                                <img src="../img/<?php echo htmlspecialchars($post['featured_image']); ?>" class="h-32 w-auto rounded-lg">
                                <label class="inline-flex items-center mt-2">
                                    <input type="checkbox" name="remove_featured_image" class="rounded text-primary">
                                    <span class="ml-2 text-sm text-gray-600">Remove current image</span>
                                </label>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="featured_image" name="featured_image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, GIF, WebP</p>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="blog.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            <?php echo $post ? 'Update Post' : 'Create Post'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php $db->close(); ?>
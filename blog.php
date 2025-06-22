<?php
require "config/config.php";

// Get current post if slug is provided
$current_post = null;
$related_posts = [];
if (isset($_GET['slug'])) {
    $slug = $db->real_escape_string($_GET['slug']);
    $query = "SELECT * FROM blog_posts WHERE slug = '$slug'";
    $result = $db->query($query);
    $current_post = $result->fetch_assoc();
    
    // If post exists, get related posts
    if ($current_post) {
        $post_id = $current_post['id'];
        // Get related posts from the same categories
        $query = "SELECT DISTINCT p.* 
                  FROM blog_posts p
                  JOIN blog_post_categories pc ON p.id = pc.post_id
                  WHERE p.id != $post_id 
                  AND pc.category_id IN (
                      SELECT category_id FROM blog_post_categories WHERE post_id = $post_id
                  )
                  ORDER BY p.created_at DESC 
                  LIMIT 3";
        $result = $db->query($query);
        while ($row = $result->fetch_assoc()) {
            $related_posts[] = $row;
        }
        
        // If no related posts from same categories, get latest posts
        if (empty($related_posts)) {
            $query = "SELECT * FROM blog_posts WHERE id != $post_id ORDER BY created_at DESC LIMIT 3";
            $result = $db->query($query);
            while ($row = $result->fetch_assoc()) {
                $related_posts[] = $row;
            }
        }
    }
} else {
    // Pagination setup
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $per_page = 6;
    $offset = ($page - 1) * $per_page;

    // CATEGORY FILTERING SETUP
    $category_condition = '';
    $category_params = '';
    $selected_category = null;
    
    if (isset($_GET['category'])) {
        $category_slug = $db->real_escape_string($_GET['category']);
        $cat_query = $db->query("SELECT id, name FROM blog_categories WHERE slug = '$category_slug'");
        if ($cat_query->num_rows > 0) {
            $selected_category = $cat_query->fetch_assoc();
            $category_id = $selected_category['id'];
            $category_condition = "AND p.id IN (
                SELECT post_id FROM blog_post_categories WHERE category_id = $category_id
            )";
            $category_params = '&category=' . urlencode($category_slug);
        }
    }

    // TOTAL POSTS COUNT QUERY (with category filter)
    $total_query = "SELECT COUNT(DISTINCT p.id) as total FROM blog_posts p WHERE 1=1 $category_condition";
    $total_result = $db->query($total_query);
    $total_posts = $total_result->fetch_assoc()['total'];
    $total_pages = ceil($total_posts / $per_page);

    // MAIN POSTS QUERY (with category filter)
    $query = "SELECT DISTINCT p.* FROM blog_posts p WHERE 1=1 $category_condition 
              ORDER BY p.created_at DESC LIMIT $offset, $per_page";
    $result = $db->query($query);
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }


}

    // CATEGORIES WITH COUNT QUERY (for sidebar)
   
    $categories_query = $db->query("
    SELECT c.id, c.name, c.slug, COUNT(pc.post_id) as post_count 
    FROM blog_categories c
    LEFT JOIN blog_post_categories pc ON c.id = pc.category_id
    LEFT JOIN blog_posts p ON pc.post_id = p.id
    GROUP BY c.id, c.name, c.slug
    ORDER BY c.name
    ");
    
    if (!$categories_query) {
    die("Categories query failed: " . $db->error);
    }
    
    $categories = [];
    while ($category = $categories_query->fetch_assoc()) {
    $categories[] = $category;
    }
    echo "<!-- Categories Data: ";
    print_r($categories);
    echo " -->";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
    <title><?php echo isset($current_post) ? htmlspecialchars($current_post['title']) . ' | ' : ''; ?>Blog | Retroviral Solution</title>
    <style>
        .blog-card {
            transition: all 0.3s ease;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
        .post-content p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        .post-content ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .post-content ol {
            list-style-type: decimal;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .post-content li {
            margin-bottom: 0.5rem;
        }
        .pagination a {
            transition: all 0.3s ease;
        }
        .pagination a:hover:not(.active) {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Blog Header -->
    <section class="relative bg-gradient-to-r from-primary to-secondary text-white py-20 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="absolute top-20 left-20 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse animation-delay-1000"></div>
                <div class="absolute bottom-10 right-20 w-40 h-40 bg-white bg-opacity-5 rounded-full animate-pulse animation-delay-500"></div>
            </div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight animate-fade-in-up">
                <?php 
                if (isset($current_post)) {
                    echo htmlspecialchars($current_post['title']);
                } elseif (isset($selected_category)) {
                    echo 'Category: ' . htmlspecialchars($selected_category['name']);
                } else {
                    echo 'Our Blog';
                }
                ?>
            </h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto animate-fade-in-up animation-delay-200">
                <?php 
                if (isset($current_post)) {
                    echo 'Posted on ' . date('F j, Y', strtotime($current_post['created_at']));
                } elseif (isset($selected_category)) {
                    echo 'Posts in ' . htmlspecialchars($selected_category['name']);
                } else {
                    echo 'Insights and updates on HIV treatment and healthcare logistics';
                }
                ?>
            </p>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <?php if (isset($current_post)): ?>
                <!-- Single Post View -->
                <div class="flex flex-col lg:flex-row gap-12">
                    <article class="lg:w-2/3">
                        <?php if ($current_post['featured_image']): ?>
                            <img src="img/<?php echo htmlspecialchars($current_post['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($current_post['title']); ?>"
                                 class="w-full h-auto rounded-xl shadow-lg mb-8">
                        <?php endif; ?>
                        
                        <div class="post-content prose max-w-none">
                            <?php echo $current_post['content']; ?>
                        </div>
                        
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <a href="<?php echo APPURL; ?>/blog.php" class="inline-flex items-center text-primary hover:text-secondary transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Back to all posts
                            </a>
                        </div>
                    </article>
                    
                    <!-- Sidebar with Related Posts -->
                    <aside class="lg:w-1/3">
                        <?php if (!empty($related_posts)): ?>
                            <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                                <h3 class="text-xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                                    Related Posts
                                </h3>
                                <div class="space-y-6">
                                    <?php foreach ($related_posts as $post): ?>
                                        <div class="flex gap-4">
                                            <?php if ($post['featured_image']): ?>
                                                <img src="img/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($post['title']); ?>"
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            <?php endif; ?>
                                            <div>
                                                <h4 class="font-semibold text-gray-800 hover:text-primary transition-colors">
                                                    <a href="<?php echo APPURL; ?>/blog.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                                        <?php echo htmlspecialchars($post['title']); ?>
                                                    </a>
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                         <?php endif; ?>
                        
                          <!-- Categories Widget -->
                          <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                            <h3 class="text-xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                                Categories
                            </h3>
                            <?php if (!empty($categories)): ?>
                                <ul class="space-y-2">
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <a href="<?php echo APPURL; ?>/blog.php?category=<?php echo htmlspecialchars($category['slug']); ?>" 
                                               class="flex justify-between items-center text-gray-700 hover:text-primary transition-colors py-2 px-3 rounded hover:bg-gray-100 <?php echo (isset($_GET['category']) && $_GET['category'] == $category['slug']) ? 'bg-primary text-white' : ''; ?>">
                                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                                                <span class="<?php echo (isset($_GET['category']) && $_GET['category'] == $category['slug']) ? 'bg-white text-primary' : 'bg-gray-200 text-gray-600'; ?> text-xs px-2 py-1 rounded-full">
                                                    <?php echo (int)$category['post_count']; ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500">No categories yet.</p>
                            <?php endif; ?>
                        </div>
                    </aside>
                </div>
            <?php else: ?>
                <!-- Blog Listing -->
                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Main Content Area -->
                    <div class="lg:w-2/3">
                        <?php if (isset($selected_category)): ?>
                            <div class="mb-8">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-bold text-gray-800">
                                        Posts in "<?php echo htmlspecialchars($selected_category['name']); ?>"
                                    </h2>
                                    <a href="<?php echo APPURL; ?>/blog.php" 
                                       class="text-primary hover:text-secondary transition-colors">
                                        <i class="fas fa-arrow-left mr-2"></i> View all posts
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($posts)): ?>
                            <div class="grid md:grid-cols-2 gap-8">
                                <?php foreach ($posts as $post): ?>
                                    <article class="blog-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 animate-fade-in-up">
                                        <a href="<?php echo APPURL; ?>/blog.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                            <?php if ($post['featured_image']): ?>
                                                <img src="img/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($post['title']); ?>"
                                                     class="w-full h-48 object-cover">
                                            <?php endif; ?>
                                            <div class="p-6">
                                                <h3 class="text-xl font-bold text-gray-800 mb-3 hover:text-primary transition-colors">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </h3>
                                                <p class="text-gray-600 mb-4">
                                                    <?php echo htmlspecialchars($post['excerpt']); ?>
                                                </p>
                                                <div class="flex justify-between items-center text-sm text-gray-500">
                                                    <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                                                    <span class="text-primary font-medium hover:text-secondary transition-colors">
                                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Pagination Controls -->
                            <?php if ($total_pages > 1): ?>
                                <div class="flex justify-center mt-12">
                                    <nav class="flex items-center gap-2 pagination">
                                        <?php if ($page > 1): ?>
                                            <a href="?page=<?php echo $page - 1; ?><?php echo $category_params; ?>" 
                                               class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                                &laquo; Previous
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        $start = max(1, $page - 2);
                                        $end = min($total_pages, $page + 2);
                                        for ($i = $start; $i <= $end; $i++): 
                                        ?>
                                            <a href="?page=<?php echo $i; ?><?php echo $category_params; ?>" 
                                               class="px-4 py-2 border <?php echo $i == $page ? 'bg-primary text-white border-primary' : 'border-gray-300 hover:bg-gray-100'; ?> rounded">
                                                <?php echo $i; ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <?php if ($page < $total_pages): ?>
                                            <a href="?page=<?php echo $page + 1; ?><?php echo $category_params; ?>" 
                                               class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                                Next &raquo;
                                            </a>
                                        <?php endif; ?>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-12">
                                <i class="fas fa-blog text-6xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg mb-4">
                                    <?php echo isset($selected_category) ? 
                                        'No posts found in "' . htmlspecialchars($selected_category['name']) . '" category.' : 
                                        'No blog posts yet. Check back soon!'; ?>
                                </p>
                                <?php if (isset($selected_category)): ?>
                                    <a href="<?php echo APPURL; ?>/blog.php" 
                                       class="text-primary hover:text-secondary transition-colors">
                                        <i class="fas fa-arrow-left mr-2"></i> View all posts
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="lg:w-1/3">
                        <!-- Categories Widget -->
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                            <h3 class="text-xl font-bold text-primary mb-6 pb-2 border-b border-gray-200">
                                Categories
                            </h3>
                            <?php if (!empty($categories)): ?>
                                <ul class="space-y-2">
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <a href="<?php echo APPURL; ?>/blog.php?category=<?php echo htmlspecialchars($category['slug']); ?>" 
                                               class="flex justify-between items-center text-gray-700 hover:text-primary transition-colors py-2 px-3 rounded hover:bg-gray-100 <?php echo (isset($_GET['category']) && $_GET['category'] == $category['slug']) ? 'bg-primary text-white' : ''; ?>">
                                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                                                <span class="<?php echo (isset($_GET['category']) && $_GET['category'] == $category['slug']) ? 'bg-white text-primary' : 'bg-gray-200 text-gray-600'; ?> text-xs px-2 py-1 rounded-full">
                                                    <?php echo (int)$category['post_count']; ?>
                                                </span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500">No categories yet.</p>
                            <?php endif; ?>
                            
                            <?php if (isset($_GET['category'])): ?>
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <a href="<?php echo APPURL; ?>/blog.php" 
                                       class="inline-flex items-center text-primary hover:text-secondary transition-colors">
                                        <i class="fas fa-arrow-left mr-2"></i> View all posts
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </aside>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 p-3 bg-primary text-white rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-secondary">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

<script>
console.log("Categories Data:", <?php echo json_encode($categories); ?>);
</script>
</body>
</html>
<?php $db->close(); ?>
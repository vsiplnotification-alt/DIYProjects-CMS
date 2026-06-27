<?php
// Add Project Page
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    // Validate inputs
    if (empty($title) || empty($description) || empty($content) || empty($image_url)) {
        $error = "All fields are required!";
    } else {
        $query = "INSERT INTO projects (title, description, content, image_url, created_at) 
                  VALUES ('$title', '$description', '$content', '$image_url', NOW())";
        
        if (mysqli_query($conn, $query)) {
            $success = "Project added successfully! Redirecting...";
            // Log the success
            error_log("Project added: " . $title);
            // Redirect after 2 seconds
            echo "<meta http-equiv='refresh' content='2;url=dashboard.php'>";
        } else {
            $error = "Database Error: " . mysqli_error($conn);
            // Log the error
            error_log("Failed to add project: " . mysqli_error($conn));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - DIY Projects Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.tiny.cloud/1/3s8ds16bkex7ubijt7ft5cgmmqhyx9yrem5622ytzvxiqlzb/tinymce/7/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'lists link image',
            toolbar: 'formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image'
        });
    </script>
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .admin-form {
            margin-top: 1.5rem;
        }

        .debug-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        .debug-info ul {
            margin: 0.5rem 0 0 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Admin Navigation -->
    <nav class="admin-navbar">
        <div class="container">
            <div class="logo">DIYProjects Admin</div>
            <ul class="nav-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="projects.php">Projects</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="admin-section">
        <div class="container">
            <div class="form-container">
                <h1>Add New Project</h1>
                
                <?php if($error): ?>
                    <div class="alert alert-error">
                        <strong>❌ Error:</strong> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if($success): ?>
                    <div class="alert alert-success">
                        <strong>✅ Success:</strong> <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="admin-form" id="projectForm">
                    <div class="form-group">
                        <label for="title">Project Title *</label>
                        <input type="text" id="title" name="title" required placeholder="Enter project title">
                    </div>

                    <div class="form-group">
                        <label for="description">Short Description *</label>
                        <textarea id="description" name="description" rows="3" required placeholder="Enter short description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL *</label>
                        <input type="url" id="image_url" name="image_url" required placeholder="https://example.com/image.jpg">
                        <small>Provide full URL of the image (starting with http:// or https://)</small>
                    </div>

                    <div class="form-group">
                        <label for="content">Tutorial Content *</label>
                        <textarea id="content" name="content" required placeholder="Enter tutorial content here..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Add Project</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

                <div class="debug-info">
                    <strong>📝 Instructions:</strong>
                    <ul>
                        <li>Fill in all required fields (*)</li>
                        <li>Image URL must start with http:// or https://</li>
                        <li>Use the rich text editor for content formatting</li>
                        <li>Click "Add Project" to save</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const image_url = document.getElementById('image_url').value.trim();
            const content = tinymce.get('content') ? tinymce.get('content').getContent() : document.getElementById('content').value.trim();
            
            console.log('Form validation check:');
            console.log('Title:', title ? '✓ Filled' : '✗ Empty');
            console.log('Description:', description ? '✓ Filled' : '✗ Empty');
            console.log('Image URL:', image_url ? '✓ Filled' : '✗ Empty');
            console.log('Content:', content ? '✓ Filled' : '✗ Empty');
            
            if (!title || !description || !image_url || !content) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
            
            console.log('✓ All fields valid - Submitting form...');
        });
    </script>
</body>
</html>

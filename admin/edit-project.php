<?php
// Edit Project Page
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$project_id = intval($_GET['id']);
$query = "SELECT * FROM projects WHERE id = $project_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: dashboard.php');
    exit();
}

$project = mysqli_fetch_assoc($result);
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);

    $query = "UPDATE projects SET title='$title', description='$description', 
              content='$content', image_url='$image_url' WHERE id=$project_id";
    
    if (mysqli_query($conn, $query)) {
        $success = "Project updated successfully!";
        // Refresh project data
        $result = mysqli_query($conn, "SELECT * FROM projects WHERE id = $project_id");
        $project = mysqli_fetch_assoc($result);
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - DIY Projects Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'lists link image',
            toolbar: 'formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image'
        });
    </script>
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
                <h1>Edit Project</h1>
                
                <?php if($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="title">Project Title *</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Short Description *</label>
                        <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL *</label>
                        <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($project['image_url']); ?>" required>
                        <small>Provide full URL of the image</small>
                    </div>

                    <div class="form-group">
                        <label for="content">Tutorial Content *</label>
                        <textarea id="content" name="content" required><?php echo $project['content']; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Project</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
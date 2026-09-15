<?php
require_once 'db.php';

$message = '';

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $stmt = $pdo->prepare("UPDATE profile SET name = ?, title = ?, email = ?, phone = ?, location = ?, about = ? WHERE id = 1");
    $stmt->execute([
        $_POST['name'], $_POST['title'], $_POST['email'], 
        $_POST['phone'], $_POST['location'], $_POST['about']
    ]);
    $message = "Profile updated successfully!";
}

// Handle Add Experience
if (isset($_POST['add_experience'])) {
    $stmt = $pdo->prepare("INSERT INTO experience (role, company, period, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['role'], $_POST['company'], $_POST['period'], $_POST['description']]);
    $message = "Experience added!";
}

// Handle Delete Experience
if (isset($_GET['delete_exp'])) {
    $stmt = $pdo->prepare("DELETE FROM experience WHERE id = ?");
    $stmt->execute([$_GET['delete_exp']]);
    header("Location: edit.php");
    exit;
}

// Handle Add Skill
if (isset($_POST['add_skill'])) {
    $stmt = $pdo->prepare("INSERT INTO skills (skill_name) VALUES (?)");
    $stmt->execute([$_POST['skill_name']]);
    $message = "Skill added!";
}

// Handle Delete Skill
if (isset($_GET['delete_skill'])) {
    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$_GET['delete_skill']]);
    header("Location: edit.php");
    exit;
}

$profile = $pdo->query("SELECT * FROM profile LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$experience = $pdo->query("SELECT * FROM experience ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$skills = $pdo->query("SELECT * FROM skills")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Resume System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            padding: 30px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .nav-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #4a90e2;
            font-weight: 600;
        }
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        h2 { margin-bottom: 15px; color: #333; font-size: 20px; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-size: 13px; font-weight: bold; color: #555; margin-bottom: 4px; }
        input, textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea { height: 80px; resize: vertical; }
        button {
            background: #4a90e2;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover { background: #357abd; }
        .alert {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 8px;
        }
        a.delete-btn {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
        }
        .skills-wrap { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
        .skill-tag {
            background: #eef2f7;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="nav-back">← Back to Public Resume</a>

    <?php if ($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Edit Profile Card -->
    <div class="card">
        <h2>Edit Profile Information</h2>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($profile['title']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone']); ?>">
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($profile['location']); ?>">
            </div>
            <div class="form-group">
                <label>About Summary</label>
                <textarea name="about"><?php echo htmlspecialchars($profile['about']); ?></textarea>
            </div>
            <button type="submit" name="update_profile">Save Profile</button>
        </form>
    </div>

    <!-- Manage Experience Card -->
    <div class="card">
        <h2>Manage Work Experience</h2>
        <?php foreach ($experience as $job): ?>
            <div class="list-item">
                <div>
                    <strong><?php echo htmlspecialchars($job['role']); ?></strong> at <?php echo htmlspecialchars($job['company']); ?> 
                    <small style="color:#777;">(<?php echo htmlspecialchars($job['period']); ?>)</small>
                </div>
                <a href="edit.php?delete_exp=<?php echo $job['id']; ?>" class="delete-btn" onclick="return confirm('Delete this job?');">Delete</a>
            </div>
        <?php endforeach; ?>

        <h3 style="margin-top:20px; font-size:15px;">Add New Experience</h3>
        <form method="POST">
            <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" required>
            </div>
            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" required>
            </div>
            <div class="form-group">
                <label>Period (e.g. 2024 - Present)</label>
                <input type="text" name="period" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>
            <button type="submit" name="add_experience">Add Experience</button>
        </form>
    </div>

    <!-- Manage Skills Card -->
    <div class="card">
        <h2>Manage Skills</h2>
        <div class="skills-wrap">
            <?php foreach ($skills as $s): ?>
                <div class="skill-tag">
                    <?php echo htmlspecialchars($s['skill_name']); ?>
                    <a href="edit.php?delete_skill=<?php echo $s['id']; ?>" style="color:#dc3545;text-decoration:none;">×</a>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST" style="display:flex; gap:10px;">
            <input type="text" name="skill_name" placeholder="New skill (e.g., Docker)" required style="flex:1;">
            <button type="submit" name="add_skill">Add Skill</button>
        </form>
    </div>
</div>

</body>
</html>
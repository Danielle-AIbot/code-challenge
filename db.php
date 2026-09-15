<?php
$dbPath = __DIR__ . '/resume.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$pdo->exec("
    CREATE TABLE IF NOT EXISTS profile (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        title TEXT,
        email TEXT,
        phone TEXT,
        location TEXT,
        about TEXT
    );

    CREATE TABLE IF NOT EXISTS experience (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        role TEXT,
        company TEXT,
        period TEXT,
        description TEXT
    );

    CREATE TABLE IF NOT EXISTS education (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        degree TEXT,
        institution TEXT,
        year TEXT
    );

    CREATE TABLE IF NOT EXISTS skills (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        skill_name TEXT
    );
");

// Seed initial data if profile is empty
$stmt = $pdo->query("SELECT COUNT(*) FROM profile");
if ($stmt->fetchColumn() == 0) {
    $pdo->exec("INSERT INTO profile (name, title, email, phone, location, about) VALUES (
        'Jane Doe',
        'Full-Stack Web Developer',
        'jane.doe@example.com',
        '+1 (555) 019-2834',
        'San Francisco, CA',
        'Passionate software engineer with 4+ years of experience building responsive web applications using PHP, JavaScript, and SQL.'
    )");

    $pdo->exec("INSERT INTO experience (role, company, period, description) VALUES 
        ('Senior PHP Developer', 'TechCorp Solutions', '2023 - Present', 'Led backend development for e-commerce platforms, optimizing database queries and integrating payment gateways.'),
        ('Junior Web Developer', 'WebCraft Agency', '2021 - 2023', 'Built and maintained client websites using PHP, HTML, CSS, and JavaScript.')
    ");

    $pdo->exec("INSERT INTO education (degree, institution, year) VALUES 
        ('B.S. in Computer Science', 'University of Technology', '2017 - 2021')
    ");

    $pdo->exec("INSERT INTO skills (skill_name) VALUES ('PHP'), ('MySQL / SQLite'), ('JavaScript (ES6)'), ('HTML5 & CSS3'), ('Git & GitHub'), ('Tailwind CSS')");
}
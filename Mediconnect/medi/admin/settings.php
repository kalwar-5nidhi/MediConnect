<?php
session_start();
include('include/config.php');

// Fetch settings
$settings = [];
$result = $con->query("SELECT * FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$theme = $settings['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings - MediConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 2rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Admin Settings</h2>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Settings updated successfully.</div>
    <?php endif; ?>

    <form action="save_settings.php" method="POST">
        <!-- Site Name -->
        <div class="card mb-4">
            <div class="card-header">General Settings</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name"
                           value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Email -->
        <div class="card mb-4">
            <div class="card-header">Email Settings</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="smtp_host" class="form-label">SMTP Host</label>
                    <input type="text" class="form-control" id="smtp_host" name="smtp_host"
                           value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="smtp_port" class="form-label">SMTP Port</label>
                    <input type="text" class="form-control" id="smtp_port" name="smtp_port"
                           value="<?= htmlspecialchars($settings['smtp_port'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Payment -->
        <div class="card mb-4">
            <div class="card-header">Payment Settings</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="esewa_key" class="form-label">eSewa API Key</label>
                    <input type="text" class="form-control" id="esewa_key" name="esewa_key"
                           value="<?= htmlspecialchars($settings['esewa_key'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Healthcare -->
        <div class="card mb-4">
            <div class="card-header">Healthcare Settings</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="search_radius" class="form-label">Search Radius (KM)</label>
                    <input type="number" class="form-control" id="search_radius" name="search_radius"
                           value="<?= htmlspecialchars($settings['search_radius'] ?? '10') ?>">
                </div>
            </div>
        </div>

        <!-- Theme Mode -->
        <div class="card mb-4">
            <div class="card-header">Appearance</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="theme" class="form-label">Theme</label>
                    <select class="form-select" id="theme" name="theme">
                        <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>Light</option>
                        <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Dark</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

<script>
    // Apply Bootstrap dark/light theme dynamically (for Bootstrap 5.3+)
    document.documentElement.setAttribute('data-bs-theme', '<?= $theme ?>');
</script>
</body>
</html>

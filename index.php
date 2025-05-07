<?php
session_start();
require_once 'config/database.php';

// Log visitor
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT * FROM visitors WHERE visit_date = ?");
$stmt->execute([$today]);
if ($row = $stmt->fetch()) {
    $pdo->prepare("UPDATE visitors SET visit_count = visit_count + 1 WHERE visit_date = ?")->execute([$today]);
} else {
    $pdo->prepare("INSERT INTO visitors (visit_date, visit_count) VALUES (?, 1)")->execute([$today]);
}

// Check if user is logged in
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Sederhana</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
    /* ...existing styles... */
    .dashboard-btn {
        background: none;
        color: inherit;
        border: none;
        outline: none;
        cursor: pointer;
        transition: transform 0.18s cubic-bezier(.4,2,.3,1), box-shadow 0.18s;
        will-change: transform;
        position: relative;
        overflow: hidden;
    }
    .dashboard-btn:hover, .dashboard-btn:focus {
        transform: translateY(-6px) scale(1.04);
        z-index: 2;
    }
    .dashboard-btn.floating {
        transform: translateY(-2px) scale(0.98);
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        background-color: rgba(39, 117, 252, 0.4);
        pointer-events: none;
    }
    @keyframes ripple {
        to {
            transform: scale(2.5);
            opacity: 0;
        }
    }
    .sidebar-anim {
        position: relative;
        overflow: hidden;
        transition: transform 0.18s cubic-bezier(.4,2,.3,1), box-shadow 0.18s;
        will-change: transform;
        z-index: 1;
    }
    .sidebar-anim:hover, .sidebar-anim:focus {
        transform: translateY(-4px) scale(1.04);
        background: rgba(39,117,252,0.08) !important;
    }
    .sidebar-anim.floating {
        transform: translateY(-1px) scale(0.98);
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        background-color: rgba(39, 117, 252, 0.18);
        pointer-events: none;
    }
    @keyframes ripple {
        to {
            transform: scale(2.5);
            opacity: 0;
        }
    }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
            <span class="brand-text font-weight-light">CMS Sederhana</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link sidebar-anim ripple-btn">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="articles.php" class="nav-link sidebar-anim ripple-btn">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Articles</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link sidebar-anim ripple-btn">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                        <p class="text-muted" style="font-size:1.2rem;">Zhainal Firdaus</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <?php
                                $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
                                $articleCount = $stmt->fetchColumn();
                                ?>
                                <h3><?php echo $articleCount; ?></h3>
                                <p>Articles</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <a href="articles.php" class="small-box-footer dashboard-btn ripple-btn">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <?php
                                $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'admin'");
                                $adminCount = $stmt->fetchColumn();
                                ?>
                                <h3><?php echo $adminCount; ?></h3>
                                <p>Total Admin</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <a href="users.php" class="small-box-footer dashboard-btn ripple-btn">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <?php
                                $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username != 'admin'");
                                $userCount = $stmt->fetchColumn();
                                ?>
                                <h3><?php echo $userCount; ?></h3>
                                <p>Total User</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="users.php" class="small-box-footer dashboard-btn ripple-btn">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <?php
                                $stmt = $pdo->query("SELECT COUNT(*) FROM articles WHERE DATE(created_at) = CURDATE()");
                                $todayArticleCount = $stmt->fetchColumn();
                                ?>
                                <h3><?php echo $todayArticleCount; ?></h3>
                                <p>Artikel Hari Ini</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <a href="articles.php" class="small-box-footer dashboard-btn ripple-btn">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <?php
                                $stmt = $pdo->query("SELECT SUM(visit_count) FROM visitors");
                                $totalVisitors = $stmt->fetchColumn();
                                ?>
                                <h3><?php echo $totalVisitors ? $totalVisitors : 0; ?></h3>
                                <p>Total Pengunjung</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <?php
                                $stmt = $pdo->prepare("SELECT visit_count FROM visitors WHERE visit_date = ?");
                                $stmt->execute([$today]);
                                $todayVisitors = $stmt->fetchColumn();
                                ?>
                                <h3><?php echo $todayVisitors ? $todayVisitors : 0; ?></h3>
                                <p>Pengunjung Hari Ini</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabel Artikel Terbaru -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0"><i class="fas fa-clock mr-2"></i>5 Artikel Terbaru</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->query("SELECT title, created_at FROM articles ORDER BY created_at DESC LIMIT 5");
                                        while ($row = $stmt->fetch()) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                            echo '<td>' . date('Y-m-d H:i', strtotime($row['created_at'])) . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; <?php echo date('Y'); ?> CMS Sederhana.</strong>
        All rights reserved.
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
// Ripple effect for button and sidebar
$(document).on('click', '.ripple-btn', function(e) {
    var $btn = $(this);
    var offset = $btn.offset();
    var x = e.pageX - offset.left;
    var y = e.pageY - offset.top;
    var $ripple = $('<span class="ripple"></span>');
    $ripple.css({
        left: x - 20,
        top: y - 20,
        width: 40,
        height: 40
    });
    $btn.append($ripple);
    setTimeout(function() {
        $ripple.remove();
    }, 600);
});
// Floating effect for button and sidebar
$(document).on('mousedown touchstart', '.ripple-btn', function() {
    $(this).addClass('floating');
});
$(document).on('mouseup mouseleave touchend', '.ripple-btn', function() {
    $(this).removeClass('floating');
});
</script>
</body>
</html> 
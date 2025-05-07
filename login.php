<?php
session_start();
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Username atau password salah";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - CMS Sederhana</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body.login-page {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
        }
        .login-box {
            margin-top: 0;
            padding-top: 7rem;
        }
        .login-logo {
            margin-bottom: 2rem;
        }
        .login-logo a {
            color: #fff;
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .login-card-body {
            border-radius: 1.2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            background: rgba(255,255,255,0.95);
        }
        .alert {
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            border-radius: 2rem;
            font-size: 1.1rem;
            font-weight: bold;
            transition: transform 0.18s cubic-bezier(.4,2,.3,1), box-shadow 0.18s;
            box-shadow: 0 2px 8px rgba(31, 38, 135, 0.15);
            will-change: transform;
        }
        .btn-primary:hover, .btn-primary:focus {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 8px 24px 0 rgba(31, 38, 135, 0.18);
        }
        .btn-primary.floating {
            transform: translateY(-2px) scale(0.98);
            box-shadow: 0 4px 12px 0 rgba(31, 38, 135, 0.13);
        }
        .input-group .form-control:focus {
            border-color: #2575fc;
            box-shadow: 0 0 0 2px #6a11cb33;
        }
        .input-group-text {
            background: #f0f4ff;
            color: #2575fc;
            font-size: 1.3rem;
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
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="index.php"><b>CMS</b> Sederhana</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan login untuk memulai sesi Anda</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" id="loginForm">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-4">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 position-relative">
                        <button type="submit" class="btn btn-primary btn-block ripple-btn" style="position:relative;overflow:hidden;">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
// Ripple effect for button
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
// Floating effect for button
$(document).on('mousedown touchstart', '.ripple-btn', function() {
    $(this).addClass('floating');
});
$(document).on('mouseup mouseleave touchend', '.ripple-btn', function() {
    $(this).removeClass('floating');
});
// Input focus effect (optional, already styled in CSS)
$('.form-control').on('focus', function() {
    $(this).closest('.input-group').find('.input-group-text').css('background', '#e3eaff');
});
$('.form-control').on('blur', function() {
    $(this).closest('.input-group').find('.input-group-text').css('background', '#f0f4ff');
});
</script>
</body>
</html> 
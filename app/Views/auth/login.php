<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4">Login</h3>
            <div id="message" class="alert d-none"></div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button id="loginBtn" class="btn btn-primary w-100">Login</button>
            <p class="text-center mt-3">Don't have an account? <a href="/auth/register">Register</a></p>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#loginBtn').click(function() {
                $.ajax({
                    url: '/auth/login',
                    method: 'POST',
                    data: { email: $('#email').val(), password: $('#password').val() },
                    success: function(response) {
                        $('#message').removeClass('d-none alert-danger alert-success')
                            .addClass(response.success ? 'alert-success' : 'alert-danger')
                            .text(response.message);
                        if (response.success) {
                            setTimeout(() => location.href = response.redirect, 1000); // Use the redirect URL from the response
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#message').removeClass('d-none alert-success').addClass('alert-danger').text('Login failed: ' + error);
                        console.log('Error:', xhr.responseText, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>
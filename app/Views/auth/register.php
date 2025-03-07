<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: scale(1.05);
        }
        .spinner-border {
            display: none;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4 text-primary">Register</h3>
            <div id="message" class="alert d-none text-center" role="alert"></div>
            <form id="registerForm" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                    <div class="invalid-feedback">The name field is required.</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Enter your phone number">
                    <div class="invalid-feedback">The phone number field is required.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                    <div class="invalid-feedback">The password field is required.</div>
                </div>
                <button type="submit" id="registerBtn" class="btn btn-primary w-100">
                    Register
                    <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                </button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="/auth/login" class="text-primary fw-bold">Login</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="/assets/js/register.js"></script>
</body>
</html>
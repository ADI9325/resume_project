<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .profile-card {
            max-width: 500px;
            margin: 50px auto;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .btn-back {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-back:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/navbar.html'; ?>

    <div class="container">
        <div class="card profile-card p-4">
            <div class="card-body text-center">
                <h2 class="card-title mb-4 text-primary">My Profile</h2>
                
                <?php if ($user->profile_image): ?>
                    <img src="<?= $baseUrl ?>/uploads/profiles/<?= esc($user->profile_image) ?>" 
                         alt="Profile Image" 
                         class="profile-img mb-3">
                <?php else: ?>
                    <img src="<?= $baseUrl ?>/assets/images/default-profile.png" 
                         alt="Default Profile Image" 
                         class="profile-img mb-3">
                <?php endif; ?>

                <div class="info-item">
                    <strong>Name:</strong> <?= esc($user->name) ?>
                </div>
                <div class="info-item">
                    <strong>Email:</strong> <?= esc($user->email) ?>
                </div>
                <div class="info-item">
                    <strong>Phone:</strong> <?= esc($user->phone ?? 'Not provided') ?>
                </div>
                <div class="info-item">
                    <strong>Role:</strong> <?= $user->role_id == 1 ? 'Admin' : 'User' ?>
                </div>
                <div class="info-item">
                    <strong>Joined:</strong> <?= date('F j, Y', strtotime($user->created_at)) ?>
                </div>

                <a href="/cv/dashboard" class="btn btn-back mt-3">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/navbar.js') ?>"></script>
</body>
</html>
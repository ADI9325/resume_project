<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>All CVs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Email</th>
                    <th>File Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cvs as $cv): ?>
                    <tr>
                        <td><?= $cv->id ?></td>
                        <td><?= esc($cv->user_email) ?></td>
                        <td><?= esc($cv->file_name) ?></td>
                        <td>
                            <a href="<?= $baseUrl ?>/cv/protectedView/<?= $cv->id ?>" 
                               class="btn btn-primary btn-sm" 
                               target="_blank">Show</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
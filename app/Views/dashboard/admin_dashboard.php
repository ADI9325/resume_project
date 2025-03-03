<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<style>
    body {
        user-select: none; /* Disable text selection */
    }
    img, embed, object {
        pointer-events: none; /* Prevent interaction */
    }
</style>
<body>
    <h1>Admin Dashboard</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>File Name</th>
                <th>Uploaded At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cvs)): ?>
                <?php foreach ($cvs as $cv): ?>
                    <tr>
                        <td><?= esc($cv->id) ?></td>
                        <td><?= esc($cv->user_id) ?></td>
                        <td><?= esc($cv->file_name) ?></td>
                        <td><?= esc($cv->uploaded_at) ?></td>
                        <td>
                            <a href="<?= site_url('cv/view/' . $cv->id) ?>" target="_blank">View</a> |
                            <a href="<?= site_url('cv/delete/' . $cv->id) ?>" onclick="return confirm('Are you sure you want to delete this CV?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No CVs uploaded yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script>
    // Disable right-click
    document.addEventListener("contextmenu", function (e) {
        e.preventDefault();
    });

    // Disable Print (Ctrl+P, Ctrl+S, Ctrl+U, Ctrl+Shift+I, F12)
    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey && (e.key === "p" || e.key === "s" || e.key === "u" || e.key === "c" || e.key === "i")) {
            e.preventDefault();
        }
        if (e.key === "F12") {
            e.preventDefault();
        }
    });

    // Prevent screenshots (Hides content when tab is inactive)
    document.addEventListener("visibilitychange", function () {
        if (document.hidden) {
            document.body.style.display = "none";
        } else {
            document.body.style.display = "block";
        }
    });

    // Hide PDF Toolbar
    document.getElementById("pdfViewer").setAttribute("sandbox", "allow-scripts allow-same-origin");
</script>

</body>
</html>

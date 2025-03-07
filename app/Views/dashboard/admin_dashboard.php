<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CV Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .cv-viewer {
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: none;
        }
        .cv-iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
        footer {
            margin-top: auto;
        }
        @media (max-width: 768px) {
            .table-container, .cv-viewer {
                padding: 10px;
            }
            .cv-iframe {
                height: 400px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../admin_components/header.php'; ?>

    <div class="container mt-5 mb-5 flex-grow-1">
        <h2 class="text-center mb-4 text-primary">All CVs</h2>
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User Email</th>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cvs)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No CVs found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cvs as $cv): ?>
                            <tr>
                                <td><?= $cv->id ?></td>
                                <td><?= esc($cv->user_email) ?></td>
                                <td><?= esc($cv->file_name) ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm view-cv-btn" data-cv-id="<?= $cv->id ?>">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div id="cvViewer" class="cv-viewer">
            <h4 class="text-center mb-3">Viewing CV</h4>
            <iframe id="cvIframe" class="cv-iframe" src=""></iframe>
        </div>
    </div>

    <?php include __DIR__ . '/../admin_components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $('.view-cv-btn').on('click', function () {
                const cvId = $(this).data('cv-id');
                const cvUrl = '<?= $baseUrl ?>/cv/protectedView/' + cvId;

                $('#cvIframe').attr('src', cvUrl);
                $('#cvViewer').slideDown();
                
                // Scroll to the viewer
                $('html, body').animate({
                    scrollTop: $('#cvViewer').offset().top
                }, 500);
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CV - CV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        .form-label {
            font-weight: 500;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../components/navbar.html'; ?>

    <div class="container d-flex justify-content-center align-items-center flex-grow-1">
        <div class="card p-4" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4 text-primary">Upload Your CV</h3>
            <div id="message" class="alert d-none text-center" role="alert"></div>
            <form id="uploadForm">
                <div class="mb-3">
                    <label for="cv_file" class="form-label">Select PDF (Max 2MB)</label>
                    <input type="file" class="form-control" id="cv_file" name="cv_file" accept=".pdf" required>
                    <div class="invalid-feedback">Please select a PDF file.</div>
                </div>
                <button type="submit" id="uploadBtn" class="btn btn-primary w-100">Upload</button>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../components/footer.html'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/upload.js') ?>"></script>
</body>
</html>
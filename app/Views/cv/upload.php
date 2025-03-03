<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CV</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
            <h3 class="text-center mb-4">Upload Your CV</h3>
            <div id="message" class="alert d-none"></div>
            <div class="mb-3">
                <label for="cv_file" class="form-label">Select PDF (Max 2MB)</label>
                <input type="file" class="form-control" id="cv_file" name="cv_file" accept=".pdf" required>
            </div>
            <button id="uploadBtn" class="btn btn-primary w-100">Upload</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#uploadBtn').click(function() {
                let formData = new FormData();
                formData.append('cv_file', $('#cv_file')[0].files[0]);

                $.ajax({
                    url: '/cv/upload',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#message').removeClass('d-none alert-danger alert-success')
                            .addClass(response.success ? 'alert-success' : 'alert-danger')
                            .text(response.message);
                        if (response.success) {
                            setTimeout(() => location.href = '/dashboard', 1000);
                        }
                    },
                    error: function() {
                        $('#message').removeClass('d-none alert-success').addClass('alert-danger').text('Upload failed');
                    }
                });
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>My CV Dashboard</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cv-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }
        .cv-page {
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            background-color: #fff;
            position: relative;
        }
        .cv-canvas {
            width: 100%;
            display: block;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 10;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .loading-message {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin: 20px 0;
        }
        /* Disable text selection */
        body {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>
<body>
    <!-- Dynamic content will be injected by JavaScript to obscure source -->
    <div id="dynamic-content"></div>

    <script>
        // Inject content dynamically to make source harder to read
        document.getElementById('dynamic-content').innerHTML = `
            <div class="container mt-5">
                <h2>My CV</h2>
                <?php if ($cv): ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My Uploaded CV</h5>
                            <p class="card-text">File: <?= esc($cv->file_name) ?></p>
                            <div id="cv-container" class="cv-container">
                                <div id="loading" class="loading-message">Loading CV...</div>
                            </div>
                            <div id="error-message" class="error-message" style="display: none;"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/cv/upload" class="btn btn-primary">Upload CV</a>
                <?php endif; ?>
        `;
    </script>

    <!-- Include PDF.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>

    <script>
        // Disable right-click
        document.addEventListener('contextmenu', event => {
            event.preventDefault();
            alert('Right-click is disabled for security reasons.');
        });

        // Disable print
        window.addEventListener('beforeprint', event => {
            event.preventDefault();
            alert('Printing is disabled for security reasons.');
        });

        // Disable copy-paste
        document.addEventListener('copy', event => {
            event.preventDefault();
            alert('Copying is disabled for security reasons.');
        });

        // Disable "View Source" (Ctrl+U or Ctrl+Shift+U)
        document.addEventListener('keydown', function(event) {
            if ((event.ctrlKey || event.metaKey) && (event.key === 'u' || event.key === 'U')) {
                event.preventDefault();
                alert('Viewing source is disabled for security reasons.');
            }
        });

        // Configure PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

        // Load and render the PDF
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('cv-container');
            const errorDiv = document.getElementById('error-message');
            const loadingDiv = document.getElementById('loading');

            <?php if ($cv): ?>
                const url = '<?= $baseUrl ?>/cv/protectedView/<?= $cv->id ?>';

                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    const totalPages = pdf.numPages;

                    // Hide loading message once the PDF is loaded
                    loadingDiv.style.display = 'none';

                    // Loop through all pages and render each one
                    for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
                        // Create a div for each page
                        const pageDiv = document.createElement('div');
                        pageDiv.className = 'cv-page';

                        // Create a canvas for each page
                        const canvas = document.createElement('canvas');
                        canvas.className = 'cv-canvas';
                        pageDiv.appendChild(canvas);

                        // Create a transparent overlay to prevent interaction
                        const overlay = document.createElement('div');
                        overlay.className = 'overlay';
                        pageDiv.appendChild(overlay);

                        // Append the page div to the container
                        container.appendChild(pageDiv);

                        // Render the page
                        pdf.getPage(pageNum).then(function(page) {
                            const viewport = page.getViewport({ scale: 1.0 });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const context = canvas.getContext('2d');
                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            page.render(renderContext);
                        }).catch(function(error) {
                            errorDiv.style.display = 'block';
                            errorDiv.textContent = 'Error rendering page ' + pageNum + ': ' + error.message;
                        });
                    }
                }).catch(function(error) {
                    loadingDiv.style.display = 'none';
                    errorDiv.style.display = 'block';
                    errorDiv.textContent = 'Error loading PDF: ' + error.message;
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
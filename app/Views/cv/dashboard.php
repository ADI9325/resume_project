<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My CV Dashboard</title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cv-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            transition: filter 0.3s ease;
        }
        .cv-page {
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            background-color: #fff;
            position: relative;
            overflow: hidden;
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
            pointer-events: none;
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
        body {
            user-select: none;
        }
        @media print {
            .cv-container, .cv-page, .cv-canvas, body * {
                display: none !important;
            }
            body:after {
                content: 'Printing is disabled for security reasons.';
                color: #000;
                font-size: 20px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

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
    </div>

    <!-- Include PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>

    <script>
        let screenBlackoutTriggered = false;

        // Function to black out the screen for 1 second
        function blackOutScreen() {
            if (screenBlackoutTriggered) return;
            screenBlackoutTriggered = true;

            document.body.style.backgroundColor = 'black';
            document.body.style.color = 'black';
            document.body.innerHTML = '';

            setTimeout(() => {
                location.reload(); // Restore content after 1 second
            }, 1000);
        }

        // Disable PrintScreen key press
        document.addEventListener('keyup', function(event) {
            if (event.key === 'PrintScreen' || event.keyCode === 44) {
                navigator.clipboard.writeText('Screenshots are disabled for security reasons.'); // Overwrite clipboard
                blackOutScreen();
            }
        });

        // Detect tab switching or minimizing
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                blackOutScreen();
            }
        });

        // Detect focus loss (clicking outside the window)
        window.addEventListener('blur', function() {
            blackOutScreen();
        });

        // Disable right-click
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Disable Developer Tools (F12, Ctrl+U, Ctrl+Shift+I)
        document.addEventListener('keydown', function(event) {
            if ((event.ctrlKey || event.metaKey) && (event.key === 'u' || event.key === 'U')) {
                event.preventDefault();
            }
            if ((event.ctrlKey && event.shiftKey && event.key === 'I') || event.key === 'F12') {
                event.preventDefault();
            }
        });

        // PDF.js Worker Configuration
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
                    loadingDiv.style.display = 'none';

                    for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
                        const pageDiv = document.createElement('div');
                        pageDiv.className = 'cv-page';

                        const canvas = document.createElement('canvas');
                        canvas.className = 'cv-canvas';
                        pageDiv.appendChild(canvas);

                        const overlay = document.createElement('div');
                        overlay.className = 'overlay';
                        pageDiv.appendChild(overlay);

                        container.appendChild(pageDiv);

                        pdf.getPage(pageNum).then(function(page) {
                            const viewport = page.getViewport({ scale: 1.0 });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const context = canvas.getContext('2d');
                            const renderContext = { canvasContext: context, viewport: viewport };
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

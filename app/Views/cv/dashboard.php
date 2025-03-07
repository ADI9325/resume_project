<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My CV Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
        body {
            user-select: none;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        footer {
            margin-top: auto;
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
    <?php include __DIR__ . '/../components/navbar.html'; ?>

    <div class="container mt-5">
        <h2>My CV</h2>
        <?php if ($cv): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Uploaded CV</h5>
                    <p class="card-text">File: <?= esc($cv->file_name) ?></p>
                    <div id="cv-container" class="cv-container"></div>
                    <div id="error-message" class="error-message" style="display: none;"></div>
                </div>
            </div>
        <?php else: ?>
            <a href="/cv/upload" class="btn btn-primary">Upload CV</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../components/footer.html'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/cv.js') ?>"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js";

        <?php if ($cv): ?>
            const cvUrl = '<?= $baseUrl ?>/cv/protectedView/<?= $cv->id ?>';
            console.log('CV URL:', cvUrl);
            document.addEventListener('DOMContentLoaded', function() {
                renderCV(cvUrl);
            });
        <?php endif; ?>

        document.addEventListener("DOMContentLoaded", function () {
            let screenBlackoutTriggered = false;

            function blackOutScreen() {
                if (screenBlackoutTriggered) return;
                screenBlackoutTriggered = true;
                document.body.style.backgroundColor = "black";
                document.body.style.color = "black";
                document.body.innerHTML = "";
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }

            document.addEventListener("keyup", function (event) {
                if (event.key === "PrintScreen" || event.keyCode === 44) {
                    navigator.clipboard.writeText("Screenshots are disabled for security reasons.");
                    blackOutScreen();
                }
            });

            document.addEventListener("visibilitychange", function () {
                if (document.hidden) {
                    blackOutScreen();
                }
            });

            window.addEventListener("blur", function () {
                blackOutScreen();
            });

            document.addEventListener("contextmenu", (event) => event.preventDefault());

            document.addEventListener("keydown", function (event) {
                if ((event.ctrlKey || event.metaKey) && (event.key === "u" || event.key === "U")) {
                    event.preventDefault();
                }
                if ((event.ctrlKey && event.shiftKey && event.key === "I") || event.key === "F12") {
                    event.preventDefault();
                }
            });

            window.renderCV = function (url) {
                console.log("Rendering CV with URL:", url);
                const container = document.getElementById("cv-container");
                const errorDiv = document.getElementById("error-message");
                pdfjsLib.getDocument(url).promise.then(function (pdf) {
                    console.log("PDF loaded successfully, total pages:", pdf.numPages);
                    const totalPages = pdf.numPages;
                    for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
                        const pageDiv = document.createElement("div");
                        pageDiv.className = "cv-page";
                        const canvas = document.createElement("canvas");
                        canvas.className = "cv-canvas";
                        pageDiv.appendChild(canvas);
                        const overlay = document.createElement("div");
                        overlay.className = "overlay";
                        pageDiv.appendChild(overlay);
                        container.appendChild(pageDiv);
                        pdf.getPage(pageNum).then(function (page) {
                            const viewport = page.getViewport({ scale: 1.0 });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            const context = canvas.getContext("2d");
                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport,
                            };
                            page.render(renderContext).promise.then(function () {
                                console.log("Page rendered successfully:", pageNum);
                            });
                        }).catch(function (error) {
                            console.error("Error rendering page:", error);
                            errorDiv.style.display = "block";
                            errorDiv.textContent = "Error rendering page " + pageNum + ": " + error.message;
                        });
                    }
                }).catch(function (error) {
                    console.error("Error loading PDF:", error);
                    errorDiv.style.display = "block";
                    errorDiv.textContent = "Error loading PDF: " + error.message;
                });
            };
        });
    </script>
</body>
</html>
// PDF.js Worker Configuration
pdfjsLib.GlobalWorkerOptions.workerSrc =
  "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js";

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
      navigator.clipboard.writeText(
        "Screenshots are disabled for security reasons."
      );
      blackOutScreen();
    }
  });

  // Detect tab switching or minimizing
  document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
      blackOutScreen();
    }
  });

  // Detect focus loss (clicking outside the window)
  window.addEventListener("blur", function () {
    blackOutScreen();
  });

  document.addEventListener("contextmenu", (event) => event.preventDefault());
  document.addEventListener("keydown", function (event) {
    if (
      (event.ctrlKey || event.metaKey) &&
      (event.key === "u" || event.key === "U")
    ) {
      event.preventDefault();
    }
    if (
      (event.ctrlKey && event.shiftKey && event.key === "I") ||
      event.key === "F12"
    ) {
      event.preventDefault();
    }
  });

  // Function to render CV
  window.renderCV = function (url) {
    console.log("Rendering CV with URL:", url);
    const container = document.getElementById("cv-container");
    const errorDiv = document.getElementById("error-message");

    pdfjsLib
      .getDocument(url)
      .promise.then(function (pdf) {
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

          pdf
            .getPage(pageNum)
            .then(function (page) {
              const viewport = page.getViewport({ scale: 3.0 });
              canvas.width = viewport.width;
              canvas.height = viewport.height;

              const context = canvas.getContext("2d");

              context.imageSmoothingEnabled = true;
              context.imageSmoothingQuality = "high";

              const renderContext = {
                canvasContext: context,
                viewport: viewport,
              };

              page.render(renderContext).promise.then(function () {
                console.log("Page rendered successfully:", pageNum);
              });
            })
            .catch(function (error) {
              console.error("Error rendering page:", error);
              errorDiv.style.display = "block";
              errorDiv.textContent =
                "Error rendering page " + pageNum + ": " + error.message;
            });
        }
      })
      .catch(function (error) {
        console.error("Error loading PDF:", error);
        errorDiv.style.display = "block";
        errorDiv.textContent = "Error loading PDF: " + error.message;
      });
  };
});

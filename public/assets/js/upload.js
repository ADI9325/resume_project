$(document).ready(function () {
  const form = $("#uploadForm")[0];
  const $message = $("#message");

  $("#uploadForm").on("submit", function (e) {
    e.preventDefault();

    const $btn = $("#uploadBtn");
    const $fileInput = $("#cv_file");

    // Client-side validation
    if (!$fileInput[0].files.length) {
      $fileInput.addClass("is-invalid");
      return;
    }

    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if ($fileInput[0].files[0].size > maxSize) {
      $message
        .removeClass("d-none alert-success")
        .addClass("alert-danger")
        .text("File size exceeds 2MB limit.");
      return;
    }

    // Validate file type
    if (!$fileInput[0].files[0].type.includes("pdf")) {
      $message
        .removeClass("d-none alert-success")
        .addClass("alert-danger")
        .text("Please upload a PDF file.");
      return;
    }

    let formData = new FormData();
    formData.append("cv_file", $fileInput[0].files[0]);

    $btn.prop("disabled", true);
    $message.addClass("d-none").removeClass("alert-success alert-danger");

    $.ajax({
      url: "/cv/upload",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        $btn.prop("disabled", false);
        $message
          .removeClass("d-none")
          .addClass(response.success ? "alert-success" : "alert-danger")
          .text(response.message);
        if (response.success) {
          setTimeout(() => (location.href = "/cv/dashboard"), 1000);
        }
      },
      error: function (xhr, status, error) {
        $btn.prop("disabled", false);
        $message
          .removeClass("d-none alert-success")
          .addClass("alert-danger")
          .text(`Upload failed: ${xhr.responseJSON?.message || error}`);
      },
    });
  });

  // Clear validation on file input change
  $("#cv_file").on("change", function () {
    $(this).removeClass("is-invalid");
    $message.addClass("d-none");
  });
});

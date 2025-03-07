$(document).ready(function () {
  /**
   * Handles register form submission with AJAX
   */
  $("#registerForm").on("submit", function (e) {
    e.preventDefault();

    const $btn = $("#registerBtn");
    const $spinner = $btn.find(".spinner-border");
    const $message = $("#message");

    $spinner.show();
    $btn.prop("disabled", true);
    $message.addClass("d-none").removeClass("alert-success alert-danger");
    const formData = $(this).serialize();

    $.ajax({
      url: "/auth/register",
      method: "POST",
      data: formData,
      success: function (response) {
        $spinner.hide();
        $btn.prop("disabled", false);

        $message
          .removeClass("d-none")
          .addClass(response.success ? "alert-success" : "alert-danger")
          .text(response.message);

        if (response.success) {
          setTimeout(() => {
            window.location.href = response.redirect || "/cv/upload";
          }, 1000);
        }
      },
      error: function (xhr, status, error) {
        $spinner.hide();
        $btn.prop("disabled", false);

        $message
          .removeClass("d-none alert-success")
          .addClass("alert-danger")
          .text(`Registration failed: ${xhr.responseJSON?.message || error}`);

        console.error("Registration Error:", xhr.responseText, status, error);
      },
    });
  });
});

$(document).ready(function () {
  const form = $("#loginForm")[0];
  const $message = $("#message");

  $("#loginForm").on("submit", function (e) {
    e.preventDefault();

    const $btn = $("#loginBtn");
    const $spinner = $btn.find(".spinner-border");

    if (!form.checkValidity()) {
      e.stopPropagation();
      $(this).addClass("was-validated");
      return;
    }

    $spinner.show();
    $btn.prop("disabled", true);
    $message.addClass("d-none").removeClass("alert-success alert-danger");

    const formData = $(this).serialize();

    $.ajax({
      url: "/auth/login",
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
          .text(`Login failed: ${xhr.responseJSON?.message || error}`);
      },
    });
  });
});

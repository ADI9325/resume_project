$(document).ready(function () {
  // Add any navbar-specific JavaScript here (e.g., custom toggling or events)
  console.log("Navbar JS loaded successfully");

  // Example: Smooth scroll for anchor links (optional)
  $("a.nav-link").on("click", function (e) {
    const href = $(this).attr("href");
    if (href.startsWith("#")) {
      e.preventDefault();
      $("html, body").animate(
        {
          scrollTop: $(href).offset().top - 70, // Adjust for fixed navbar height
        },
        500
      );
    }
  });
});

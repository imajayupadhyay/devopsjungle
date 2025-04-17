<?php
$pageTitle = "Contact Us - DevOps Jungle";
require_once('includes/header.php');
?>

<link rel="stylesheet" href="assets/css/contact.css">

<section class="contact-hero py-5 text-white text-center bg-success bg-gradient">
    <div class="container">
        <h1 class="display-5 fw-bold">Contact Us</h1>
        <p class="lead">Have questions, suggestions, or feedback? We’d love to hear from you.</p>
    </div>
</section>

<section class="container my-5">
    <div class="row g-4">
        <!-- Contact Details -->
        <div class="col-md-5">
            <div class="bg-light p-4 rounded shadow-sm h-100">
                <h5 class="mb-3">Our Information</h5>
                <p><i class="bi bi-geo-alt-fill me-2 text-success"></i> 123 DevOps Street, Cloud City</p>
                <p><i class="bi bi-envelope-fill me-2 text-success"></i> support@devopsjungle.com</p>
                <p><i class="bi bi-telephone-fill me-2 text-success"></i> +91 9876543210</p>
                <p><i class="bi bi-clock-fill me-2 text-success"></i> Mon - Fri: 9:00 AM to 6:00 PM</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-md-7">
            <div class="p-4 bg-white rounded shadow-sm">
                <h5 class="mb-4">Send us a message</h5>
                <form action="contact-submit.php" method="POST" id="contactForm">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required minlength="2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" required minlength="10"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Thank You!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Your message was successfully submitted. We'll get back to you soon.
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("success") === "1") {
        const modal = new bootstrap.Modal(document.getElementById("successModal"));
        modal.show();
    }
});
</script>

<?php require_once('includes/footer.php'); ?>

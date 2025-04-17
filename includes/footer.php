<footer class="footer mt-5 pt-4 border-top bg-light text-dark">
  <div class="container">
    <div class="row gy-4">
      
      <!-- Logo + About -->
      <div class="col-md-4">
        <h5 class="fw-bold">DevOpsJungle</h5>
        <p class="text-muted small">
          Learn DevOps, Cloud & Automation step-by-step with tutorials, courses, and real-world tips.
        </p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-4">
        <h6 class="fw-semibold">Quick Links</h6>
        <ul class="list-unstyled small">
          <li><a href="/devopsjungle/tutorials" class="text-decoration-none text-dark">Tutorials</a></li>
          <li><a href="/devopsjungle/courses" class="text-decoration-none text-dark">Courses</a></li>
          <li><a href="/devopsjungle/blogs" class="text-decoration-none text-dark">Blogs</a></li>
          <li><a href="/devopsjungle/contact.php" class="text-decoration-none text-dark">Contact</a></li>
        </ul>
      </div>

      <!-- Social & Newsletter -->
      <div class="col-md-4">
        <h6 class="fw-semibold">Stay Connected</h6>
        <div class="d-flex gap-3 mb-3">
          <a href="#" class="text-dark fs-5"><i class="bi bi-twitter"></i></a>
          <a href="#" class="text-dark fs-5"><i class="bi bi-github"></i></a>
          <a href="#" class="text-dark fs-5"><i class="bi bi-linkedin"></i></a>
          <a href="#" class="text-dark fs-5"><i class="bi bi-youtube"></i></a>
        </div>
        <small class="text-muted">Made with ❤️ in India</small>
      </div>

    </div>

    <hr>
    <div class="text-center small text-muted pb-3">
      &copy; <?= date('Y') ?> DevOpsJungle. All rights reserved.
    </div>
  </div>
</footer>

<script>
  const mobileToggle = document.getElementById('mobileToggle');
  const mobileMenu = document.getElementById('mobileMenu');
  const mobileBackdrop = document.getElementById('mobileBackdrop');
  const mobileClose = document.getElementById('mobileClose');

  function toggleMobileMenu(show) {
    if (show) {
      mobileMenu.classList.add('show');
      mobileBackdrop.classList.add('show');
    } else {
      mobileMenu.classList.remove('show');
      mobileBackdrop.classList.remove('show');
    }
  }

  mobileToggle.addEventListener('click', () => toggleMobileMenu(true));
  mobileClose.addEventListener('click', () => toggleMobileMenu(false));
  mobileBackdrop.addEventListener('click', () => toggleMobileMenu(false));
</script>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

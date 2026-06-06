    <footer class="main-footer">
        <strong>PDAM Zernih</strong> - Sistem Informasi Rekening Air
    </footer>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script>
    // Reset sidebar collapse state
    if (document.body.classList.contains('sidebar-collapse')) {
        document.body.classList.remove('sidebar-collapse');
    }
    localStorage.removeItem('remember-sidebar');
</script>
<!-- Custom JS -->
<script src="<?= $baseUrl ?>assets/js/app.js"></script>
</body>
</html>

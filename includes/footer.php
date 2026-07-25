<?php
/**
 * Smart Hospital Management System
 * Shared Footer Template
 */
if (!isset($path_prefix)) {
    $path_prefix = './';
}
?>
            </div> <!-- End Page Body Container -->

            <!-- Footer -->
            <footer class="mt-auto py-3 bg-white border-top text-center text-muted small">
                <div class="container">
                    <span>&copy; <?php echo date('Y'); ?> Smart Hospital Management System | Designed for University DBMS Lab Project</span>
                </div>
            </footer>
        </div> <!-- End Content -->
    </div> <!-- End Wrapper -->

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo $path_prefix; ?>assets/js/main.js"></script>
</body>
</html>

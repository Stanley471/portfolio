        </div><!-- /admin-content -->
        
        <!-- Admin Footer -->
        <footer class="admin-footer">
            <p>&copy; <?php echo date('Y'); ?> Portfolio Admin. All rights reserved.</p>
        </footer>
    </div><!-- /admin-main -->
    
    <!-- Admin JavaScript -->
    <script>
        // Toggle sidebar
        const sidebar = document.getElementById('adminSidebar');
        const sidebarToggle = document.getElementById('adminSidebarToggle');
        const menuToggle = document.getElementById('adminMenuToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }
        
        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
        
        // Auto-hide flash messages
        document.querySelectorAll('.admin-flash').forEach(flash => {
            setTimeout(() => {
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 300);
            }, 5000);
        });
        
        // Confirm delete
        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm(btn.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });
    </script>
    
    <?php if (isset($extraScripts)): ?>
        <?php echo $extraScripts; ?>
    <?php endif; ?>
</body>
</html>

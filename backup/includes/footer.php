<!--begin::Footer-->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Version 1.0</div>
            <strong>Copyright &copy; 2025 <a href="#" class="text-decoration-none">Inventory Management System</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!--end::App Wrapper-->
    
    <!--begin::Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/adminlte.js"></script>
    
    <!-- ✅ JavaScript for Auto Description -->
    <script>
    document.getElementById('category_name').addEventListener('change', function() {
        const descriptions = {
            "Electronics": "Products that use electronic circuits to function, ranging from personal gadgets to household appliances.",
            "Apparel/Clothing": "Items worn on the body for protection, fashion, or other functions.",
            "Home Goods & Furniture": "Products used to furnish, decorate, or maintain a home.",
            "Beauty & Personal Care": "Products used for hygiene, grooming, and cosmetics.",
            "Food & Beverages": "Edible items and drinks for consumption.",
            "Sports & Outdoors": "Equipment and gear designed for athletic activities, fitness, and outdoor recreation.",
            "Digital Products": "Intangible goods or services delivered electronically."
        };

        const selected = this.value;
        const descriptionField = document.getElementById('description');

        // Auto-fill description if category selected
        descriptionField.value = descriptions[selected] || "";
    });
    </script>

    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            const isMobile = window.innerWidth <= 992;
            
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined' && !isMobile) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
</body>
</html>
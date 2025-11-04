        </main>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.js"></script>

    <!-- Script principal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Submenu animado
            var submenuItems = document.querySelectorAll('.has-submenu > a');
            submenuItems.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    var submenu = this.nextElementSibling;
                    submenu.classList.toggle('open');
                });
            });

            // Toggle sidebar
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('visible');
            });
        });
    </script>
</body>
</html>

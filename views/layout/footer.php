</main>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Submenus
        var submenuItems = document.querySelectorAll('.has-submenu > a');
        submenuItems.forEach(function(item) {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                var submenu = this.nextElementSibling;
                submenu.style.display = (submenu.style.display === 'block') ? 'none' : 'block';
            });
        });

        // Menu hamburguesa
        var hamburger = document.getElementById('hamburger');
        var sidebar = document.querySelector('.sidebar');

        // Overlay
        var overlay = document.createElement('div');
        overlay.classList.add('overlay');
        document.body.appendChild(overlay);

        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');

            // Bloquear scroll en body cuando sidebar abierto
            if(sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        overlay.addEventListener('click', function() {
            hamburger.classList.remove('active');
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        });


    });


    
    </script>
</body>
</html>

</main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var submenuItems = document.querySelectorAll('.has-submenu > a');
            submenuItems.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    var submenu = this.nextElementSibling;
                    if (submenu.style.display === 'block') {
                        submenu.style.display = 'none';
                    } else {
                        submenu.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html>

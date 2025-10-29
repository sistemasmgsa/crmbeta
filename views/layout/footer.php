        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submenus = document.querySelectorAll('.has-submenu');

            submenus.forEach(submenu => {
                const link = submenu.querySelector('a');
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const subMenuContent = submenu.querySelector('.submenu');
                    if (subMenuContent.style.display === 'block') {
                        subMenuContent.style.display = 'none';
                        submenu.classList.remove('open');
                    } else {
                        // Close other open submenus
                        document.querySelectorAll('.has-submenu.open').forEach(openSubmenu => {
                            openSubmenu.classList.remove('open');
                            openSubmenu.querySelector('.submenu').style.display = 'none';
                        });
                        subMenuContent.style.display = 'block';
                        submenu.classList.add('open');
                    }
                });
            });
        });
    </script>
</body>
</html>

</main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-left">
                    <div class="footer-admin-link-left">
                        <?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
                            <a href="/beauty-salon/login.php" class="admin-lock-left" aria-label="Вход для администратора">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <p>&copy; <?= date('Y') ?> Салон красоты "Эстетика".</p>
                </div>
                <div class="footer-social">
                    <a href="https://vk.com/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="ВКонтакте">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M15.07 13.79c.86-.86 2.11-2.21 2.11-2.21.14-.16.21-.36.21-.57 0-.29-.17-.46-.46-.46h-1.57c-.39 0-.63.14-.78.43 0 0-.89 1.94-1.32 2.57-.36.57-.57.71-.75.71-.18 0-.39-.14-.39-.57v-2.43c0-.5-.14-.71-.54-.71H8.76c-.32 0-.5.21-.5.43 0 .36.71.43.79 1.39v2.14c0 .57-.1.71-.32.71-.75 0-2.54-2.32-3.61-4.97-.21-.5-.39-.71-.82-.71H2.54c-.46 0-.54.21-.54.43 0 .5.68 3.21 3.18 6.75 1.68 2.36 4.07 3.68 6.25 3.68 1.32 0 1.46-.32 1.46-.86v-2.07c0-.5.11-.68.46-.68.25 0 .68.14 1.68 1.18 1.07 1.07 1.25 1.54 1.86 1.54h1.57c.46 0 .68-.21.57-.57-.07-.21-.36-.68-.86-1.18z"/></svg>
                    </a>
                    <a href="https://max.ru/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Max">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                    </a>
                    <a href="https://ok.ru/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Одноклассники">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 10c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm6.18 1.82L16 13.5l-2.18-1.68c-.32-.25-.76-.25-1.08 0L10.5 13.5l-2.18-1.68c-.32-.25-.76-.25-1.08 0L5 13.5l2.18 1.68c.32.25.76.25 1.08 0L10.5 13.5l2.18 1.68c.32.25.76.25 1.08 0L16 13.5l2.18-1.68c.32-.25.32-.68 0-.93zM12 4c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/></svg>
                    </a>
                </div>
                <div class="footer-right">
                    <button id="scrollToTopBtn" class="scroll-to-top-footer" aria-label="Наверх">↑</button>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="/beauty-salon/assets/js/script.js"></script>
    <script>
        // Кнопка "Наверх"
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");
        if (scrollToTopBtn) {
            scrollToTopBtn.addEventListener("click", function() {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
        }
    </script>
</body>
</html>
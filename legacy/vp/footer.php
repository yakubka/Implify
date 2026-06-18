<style>
    .site-footer {
    background-color: #ffffff;
    color: #333333;
    padding: 3rem 0 0;
    box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.05);
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 3rem;
}

.footer-logo {
    display: flex;
    flex-direction: column;
}

.footer-logo-img {
    width: 40px;
    height: auto;
    margin-bottom: 1rem;
}

.footer-site-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    background: linear-gradient(90deg, #000, #444);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.footer-slogan {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
}

.footer-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.footer-column {
    margin-bottom: 1.5rem;
}

.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
    color: #222;
    position: relative;
    padding-bottom: 0.5rem;
}

.footer-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 2px;
    background: linear-gradient(90deg, #478CFF, #47D8FF);
}

.footer-list {
    list-style: none;
}

.footer-list li {
    margin-bottom: 0.8rem;
}

.footer-link {
    color: #555;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}

.footer-link:hover {
    color: #478CFF;
    padding-left: 5px;
}

.footer-social {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #f5f5f5;
    color: #555;
    transition: all 0.3s ease;
}

.social-link:hover {
    background-color: #478CFF;
    color: white;
    transform: translateY(-3px);
}

.footer-bottom {
    background-color: #f9f9f9;
    padding: 1.5rem 0;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.footer-bottom-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.copyright {
    font-size: 0.9rem;
    color: #666;
}

.footer-lang {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.lang-text {
    font-size: 0.9rem;
    color: #666;
}

.lang-select {
    padding: 0.3rem 0.5rem;
    border-radius: 4px;
    border: 1px solid #ddd;
    background-color: white;
    color: #333;
    font-size: 0.9rem;
    cursor: pointer;
}

.lang-select:focus {
    outline: none;
    border-color: #478CFF;
}

.scroll-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #478CFF;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(71, 140, 255, 0.3);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 999;
}

.scroll-to-top.visible {
    opacity: 1;
    visibility: visible;
}

.scroll-to-top:hover {
    background-color: #3a7be0;
    transform: translateY(-3px);
}


@media (max-width: 992px) {
    .footer-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .footer-links {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .footer-links {
        grid-template-columns: 1fr;
    }
    
    .footer-bottom-container {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .scroll-to-top {
        width: 40px;
        height: 40px;
        bottom: 20px;
        right: 20px;
    }
}
</style>
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-logo">
            <img src="imgs/logo.png" alt="Логотип Implify" class="footer-logo-img">
            <span class="footer-site-name">Implify</span>
            <p class="footer-slogan">Объединяем волонтеров по всему миру</p>
        </div>
        
        <div class="footer-links">
            <div class="footer-column">
                <h3 class="footer-title">Навигация</h3>
                <ul class="footer-list">
                    <li><a href="index.php" class="footer-link">Главная</a></li>
                    <li><a href="offers.php" class="footer-link">Все предложения</a></li>
                    <li><a href="create_offer.php" class="footer-link">Создать офер</a></li>
                    <li><a href="profile.php" class="footer-link">Мой профиль</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Информация</h3>
                <ul class="footer-list">
                    <li><a href="about.php" class="footer-link">О проекте</a></li>
                    <li><a href="faq.php" class="footer-link">FAQ</a></li>
                    <li><a href="terms.php" class="footer-link">Условия использования</a></li>
                    <li><a href="privacy.php" class="footer-link">Политика конфиденциальности</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3 class="footer-title">Контакты</h3>
                <ul class="footer-list">
                    <li><a href="mailto:support@implify.com" class="footer-link"><i class="fas fa-envelope"></i> support@implify.com</a></li>
                    <li><a href="tel:+1234567890" class="footer-link"><i class="fas fa-phone-alt"></i> +1 (234) 567-890</a></li>
                    <li class="footer-social">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <div class="footer-bottom-container">
            <p class="copyright">&copy; <?= date('Y') ?> Implify. Все права защищены.</p>
            <div class="footer-lang">
                <span class="lang-text">Язык:</span>
                <select class="lang-select">
                    <option value="ru" selected>Русский</option>
                    <option value="en">English</option>
                </select>
            </div>
        </div>
    </div>
    
    <button class="scroll-to-top" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Кнопка "наверх"
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('visible');
        } else {
            scrollToTopBtn.classList.remove('visible');
        }
    });
    
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Анимация при наведении на логотип
    const footerLogo = document.querySelector('.footer-logo-img');
    if (footerLogo) {
        footerLogo.addEventListener('mouseenter', function() {
            this.style.transform = 'rotate(15deg) scale(1.1)';
            this.style.transition = 'all 0.3s ease';
        });
        
        footerLogo.addEventListener('mouseleave', function() {
            this.style.transform = 'rotate(0) scale(1)';
        });
    }
    
    // Изменение языка
    const langSelect = document.querySelector('.lang-select');
    if (langSelect) {
        langSelect.addEventListener('change', function() {
            // Здесь можно добавить логику смены языка
            console.log('Выбран язык:', this.value);
            // В реальном приложении здесь будет редирект или AJAX-запрос
        });
    }
    
    // Плавный скролл для якорных ссылок
    document.querySelectorAll('.footer-link[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

</script>

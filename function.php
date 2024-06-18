/**
 * Slide - Vitor Tomasi
 */
function lifetech_custom_slider_shortcode() {
    ob_start();
    ?>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap');

    .slider-container {
        position: relative;
        width: 100vw;
        height: 100vh; /* Ajustado para ocupar a altura total da tela */
        margin: 0 auto;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        width: 100%;
        height: 100%;
        transition: opacity 0.5s ease-in-out;
        opacity: 0;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .slide.active {
        opacity: 1;
        position: relative;
    }

    .video-slide video {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Mantém a regra original para cobrir toda a área */
    }

    .image-slide img {
        width: 100%;
        height: auto;
        object-fit: cover; /* Ajusta a imagem para cobrir, bom para ambos mobile e desktop */
    }

    .title {
        position: absolute;
        width: 100%;
        top: 50%;
        font-family: 'Ubuntu', sans-serif;
        font-size: 60px;
        color: white;
        text-align: center;
        transform: translateY(-50%);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        z-index: 10;
        line-height: 1.2; /* Espaçamento entre linhas */
    }

    .slider-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        z-index: 15;
    }

    .slider-nav button {
        border: none;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 10px 20px;
        cursor: pointer;
        outline: none;
        font-size: 24px;
    }
    </style>

    <div class="slider-container">
        <!-- Navegação -->
        <div class="slider-nav">
            <button onclick="changeSlide(-1)">&#10094;</button>
            <button onclick="changeSlide(1)" style="margin-left: auto;">&#10095;</button>
        </div>

        <!-- Slides -->
        <div class="slide video-slide active">
            <video autoplay loop muted>
                <source src="http://desenvolvimento.lifetech.movehost.com.br/wp-content/uploads/2024/06/Lifetech.mp4" type="video/mp4">
                Seu navegador não suporta vídeos.
            </video>
            <div class="title">Máquinas<br><b>Final de Linha</b></div>
        </div>
        <div class="slide image-slide">
            <img id="projetos-especiais-img" alt="Projetos Especial">
            <div class="title">Projetos<br><b>Especial</b></div>
        </div>
        <div class="slide image-slide">
            <img id="adequacoes-nr12-img" alt="Adequações NR-12">
			<div class="title">Adequações<br><b>NR-12</b></div>
        </div>

        <script>
        var currentSlide = 0;
        var slides = document.querySelectorAll('.slide');
        var totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        }

        function changeSlide(direction) {
            currentSlide += direction;
            if (currentSlide >= totalSlides) {
                currentSlide = 0;
            } else if (currentSlide < 0) {
                currentSlide = totalSlides - 1;
            }
            showSlide(currentSlide);
        }

        // Autoplay slides every 5 seconds
        setInterval(() => {
            changeSlide(1);
        }, 5000);

        // Image source control based on screen width
        function setImageSources() {
            var screenWidth = window.innerWidth || document.documentElement.clientWidth;
            var imgProjetosEspeciais = document.getElementById('projetos-especiais-img');
            var imgAdequacoesNR12 = document.getElementById('adequacoes-nr12-img');

            if (screenWidth >= 1024) { // Desktop
                imgProjetosEspeciais.src = "http://desenvolvimento.lifetech.movehost.com.br/wp-content/uploads/2024/06/Sem-nome-1580-x-700-px.png";
                imgAdequacoesNR12.src = "http://desenvolvimento.lifetech.movehost.com.br/wp-content/uploads/2024/05/30638.jpg";
            } else { // Mobile
                imgProjetosEspeciais.src = "http://desenvolvimento.lifetech.movehost.com.br/wp-content/uploads/2024/06/Sem-nome-1580-x-700-px-1080-x-1920-px-1.png";
                imgAdequacoesNR12.src = "http://desenvolvimento.lifetech.movehost.com.br/wp-content/uploads/2024/06/Sem-nome-1580-x-700-px-1080-x-1920-px.png";
            }
        }

        window.onload = setImageSources;
        window.onresize = setImageSources; // Adjust image sources when window is resized

        </script>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('lifetech_slider', 'lifetech_custom_slider_shortcode');

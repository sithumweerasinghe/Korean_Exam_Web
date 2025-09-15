<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="keywords" content="Topik Sir | Korean language proficiency test and secure your employment abroad." />
    <meta name="author" content="Virul Nirmala Wickramasinghe" />
    <title>Topik Sir | Korean language proficiency test and secure your employment abroad.</title>
    <meta property="og:url" content="https://topiksir.com/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Topik Sir">
    <meta property="og:image" content="https://opengraph.b-cdn.net/production/images/7ccbbf92-ba39-44c6-bac5-49bc09364734.png?token=09ysYKBiRQ9EiSFQ7w_cEVKlD1IsnFPaG-vPW7sP10U&height=630&width=1200&expires=33270101925">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="topiksir.com">
    <meta property="twitter:url" content="https://topiksir.com/">
    <meta name="twitter:title" content="Topik Sir">
    <meta name="twitter:description" content="Prepare for the EPS-TOPIK exam with our expert training program. Access study materials, mock tests, and personalized guidance to excel in your Korean language proficiency test and secure your employment abroad.">
    <meta name="twitter:image" content="https://opengraph.b-cdn.net/production/images/7ccbbf92-ba39-44c6-bac5-49bc09364734.png?token=09ysYKBiRQ9EiSFQ7w_cEVKlD1IsnFPaG-vPW7sP10U&height=630&width=1200&expires=33270101925">

    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <link rel="stylesheet" href="assets/plugins/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/plugins/css/nice-select.min.css" />
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('assets/images/section-bg-10.png');
            color: #333;
        }

        .container {
            text-align: center;
            padding: 20px;
            width: 90%;
            max-width: 600px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #4caf50;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .button {
            background: linear-gradient(135deg, #4caf50, #3e8e41);
            color: white;
            padding: 15px 30px;
            margin: 10px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            transition: transform 0.3s ease, background 0.3s ease;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .button:hover {
            background: linear-gradient(135deg, #45a049, #367c39);
            transform: scale(1.05);
        }

        .game-area {
            margin-top: 20px;
            display: none;
            position: relative;
            width: 100%;
            height: 50vw;
            max-height: 400px;
            border: 3px solid #4caf50;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .target {
            width: 10vw;
            height: 10vw;
            max-width: 50px;
            max-height: 50px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 50%;
            position: absolute;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        .target:hover {
            transform: scale(1.2);
        }

        .stats {
            margin-top: 20px;
            font-size: 1.1rem;
            width: 100%;
            text-align: left;
            box-sizing: border-box;
            padding: 15px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 10px;
            display: none;
        }

        @media (max-width: 768px) {
            .button {
                font-size: 0.9rem;
                padding: 12px 25px;
            }

            h1 {
                font-size: 2rem;
            }

            .stats {
                font-size: 1rem;
            }

            .timer {
                font-size: 1.2rem;
            }
        }

        .celebration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div id="ed-preloader" class="ed-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div class="container">
        <img src="assets/images/logo.png" alt="Site Logo" id="site-logo" style="width: 150px; margin-bottom: 20px;">
        <h1>Mouse Training Game</h1>
        <p>Sharpen your mouse skills with fun and engaging levels!</p>
        <div style="display: flex; flex-direction: row; gap: 10px;">
            <button class="button" onclick="startGame()">Start Game</button>
            <button class="button" onclick="window.location='https://topiksir.com'">Go to Home</button>
        </div>
        <div id="gameArea" class="game-area"></div>
        <div id="stats" class="stats"></div>
        <div id="timer" class="timer"></div>
        <canvas id="celebrationCanvas" class="celebration"></canvas>
    </div>
    <script src="assets/plugins/js/jquery.min.js"></script>
    <script src="assets/plugins/js/jquery-migrate.js"></script>
    <script src="assets/plugins/js/bootstrap.min.js"></script>
    <script src="assets/plugins/js/gsap/gsap.js"></script>
    <script src="assets/plugins/js/gsap/gsap-scroll-to-plugin.js"></script>
    <script src="assets/plugins/js/gsap/gsap-scroll-smoother.js"></script>
    <script src="assets/plugins/js/gsap/gsap-scroll-trigger.js"></script>
    <script src="assets/plugins/js/gsap/gsap-split-text.js"></script>
    <script src="assets/plugins/js/wow.min.js"></script>
    <script src="assets/plugins/js/owl.carousel.min.js"></script>
    <script src="assets/plugins/js/swiper-bundle.min.js"></script>
    <script src="assets/plugins/js/magnific-popup.min.js"></script>
    <script src="assets/plugins/js/jquery.counterup.min.js"></script>
    <script src="assets/plugins/js/waypoints.min.js"></script>
    <script src="assets/plugins/js/nice-select.min.js"></script>
    <script src="assets/plugins/js/backToTop.js"></script>
    <script src="assets/plugins/js/active.js"></script>
    <script>
        const logo = document.getElementById("site-logo");
        const gameArea = document.getElementById("gameArea");
        const stats = document.getElementById("stats");
        const timerDisplay = document.getElementById("timer");
        const celebrationCanvas = document.getElementById("celebrationCanvas");
        const ctx = celebrationCanvas.getContext("2d");
        let level = 1;
        let clicks = 0;
        let misses = 0;
        let startTime;
        let levelTime = 15;
        let timerInterval;
        celebrationCanvas.width = window.innerWidth;
        celebrationCanvas.height = window.innerHeight;

        function startGame() {
            level = 1;
            clicks = 0;
            misses = 0;
            logo.style.display = "none";
            stats.style.display = "block";
            timerDisplay.style.display = "block";
            updateStats();
            gameArea.innerHTML = "";
            gameArea.style.display = "block";
            nextLevel();
        }

        function nextLevel() {
            clearInterval(timerInterval);
            levelTime = 15;
            updateTimerDisplay();
            gameArea.innerHTML = "";
            const targetCount = level + 2;
            for (let i = 0; i < targetCount; i++) {
                const target = document.createElement("div");
                target.className = "target";
                target.style.top =
                    Math.random() * (gameArea.offsetHeight - 50) + "px";
                target.style.left =
                    Math.random() * (gameArea.offsetWidth - 50) + "px";
                target.addEventListener("click", () => {
                    clicks++;
                    target.remove();
                    if (gameArea.querySelectorAll(".target").length === 0) {
                        if (level < 5) {
                            level++;
                            nextLevel();
                        } else {
                            endGame();
                        }
                    }
                    updateStats();
                });
                gameArea.appendChild(target);
            }
            if (!startTime) startTime = new Date();
            timerInterval = setInterval(() => {
                levelTime--;
                updateTimerDisplay();
                if (levelTime <= 0) {
                    clearInterval(timerInterval);
                    endGame();
                }
            }, 1000);
        }
        gameArea.addEventListener("click", (e) => {
            if (!e.target.classList.contains("target")) {
                misses++;
                updateStats();
            }
        });

        function updateStats() {
            const accuracy =
                clicks + misses > 0 ?
                ((clicks / (clicks + misses)) * 100).toFixed(2) :
                100;
            stats.innerHTML = `
        <p><strong>Level:</strong> ${level}</p>
        <p><strong>Clicks:</strong> ${clicks}</p>
        <p><strong>Misses:</strong> ${misses}</p>
        <p><strong>Accuracy:</strong> ${accuracy}%</p>
      `;
        }

        function updateTimerDisplay() {
            timerDisplay.textContent = `Time Left: ${levelTime}s`;
        }

        function endGame() {
            logo.style.display = "";
            clearInterval(timerInterval);
            const totalTime = ((new Date() - startTime) / 1000).toFixed(2);
            gameArea.style.display = "none";
            gameArea.innerHTML = "";
            timerDisplay.style.display = "none";
            triggerCelebration();
            stats.innerHTML = `
          <p><strong>Game Over!</strong></p>
          <p><strong>Levels Completed:</strong> ${level}</p>
          <p><strong>Clicks:</strong> ${clicks}</p>
          <p><strong>Misses:</strong> ${misses}</p>
          <p><strong>Accuracy:</strong> ${(clicks / (clicks + misses) * 100).toFixed(2)}%</p>
          <p><strong>Total Time:</strong> ${totalTime}s</p>
        `;
            setTimeout(() => {
                ctx.clearRect(0, 0, celebrationCanvas.width, celebrationCanvas.height);
            }, 3000);
        }

        function triggerCelebration() {
            const particles = [];
            const particleCount = 200;
            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: celebrationCanvas.width / 2,
                    y: celebrationCanvas.height / 2,
                    radius: Math.random() * 4 + 1,
                    color: `hsl(${Math.random() * 360}, 70%, 50%)`,
                    speedX: Math.random() * 10 - 5,
                    speedY: Math.random() * 10 - 5,
                });
            }

            function animate() {
                ctx.clearRect(0, 0, celebrationCanvas.width, celebrationCanvas.height);
                particles.forEach((particle, index) => {
                    particle.x += particle.speedX;
                    particle.y += particle.speedY;
                    particle.speedY += 0.05;
                    ctx.beginPath();
                    ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
                    ctx.fillStyle = particle.color;
                    ctx.fill();
                    if (
                        particle.x < 0 ||
                        particle.x > celebrationCanvas.width ||
                        particle.y > celebrationCanvas.height
                    ) {
                        particles.splice(index, 1);
                    }
                });
                if (particles.length > 0) {
                    requestAnimationFrame(animate);
                }
            }
            animate();
        }
    </script>
</body>

</html>
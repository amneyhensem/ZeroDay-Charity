const canvas = document.getElementById('bg-canvas');
const ctx = canvas.getContext('2d');
let dots = [];

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}

function createDots() {
    dots = [];
    for (let i = 0; i < 30; i++) {
        dots.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: 8 + Math.random() * 12,
            dx: (Math.random() - 0.5) * 0.7,
            dy: (Math.random() - 0.5) * 0.7,
            color: Math.random() > 0.5 ? 'rgba(238,9,121,0.13)' : 'rgba(255,106,0,0.13)'
        });
    }
}

function animateDots() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let dot of dots) {
        ctx.beginPath();
        ctx.arc(dot.x, dot.y, dot.r, 0, 2 * Math.PI);
        ctx.fillStyle = dot.color;
        ctx.fill();
        dot.x += dot.dx;
        dot.y += dot.dy;
        if (dot.x < 0 || dot.x > canvas.width) dot.dx *= -1;
        if (dot.y < 0 || dot.y > canvas.height) dot.dy *= -1;
    }
    requestAnimationFrame(animateDots);
}

window.addEventListener('resize', () => {
    resizeCanvas();
    createDots();
});

resizeCanvas();
createDots();
animateDots();

// Hamburger menu toggle
const hamburgerBtn = document.getElementById('hamburgerBtn');
const adminNav = document.getElementById('adminNav');

hamburgerBtn.addEventListener('click', () => {
  const isOpen = adminNav.classList.toggle('show');
  hamburgerBtn.classList.toggle('open');
  hamburgerBtn.setAttribute('aria-expanded', isOpen);
});

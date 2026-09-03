// Dynamic Multi-Background Rotating Engine
document.addEventListener("DOMContentLoaded", function () {
    const bgImages = [
        '../assets/images/hero_banner.jpg',
        '../assets/images/smart_agri.jpg',
        '../assets/images/seeds.jpg',
        '../assets/images/fertilizer.jpg',
        '../assets/images/equipment.jpg',
        '../assets/images/pesticides.jpg'
    ];

    // Fallback URLs if local assets are missing
    const fallbackUrls = [
        'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1574943320219-553eb213f72d?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1585314062340-f1a5a7c9328d?auto=format&fit=crop&w=1920&q=80'
    ];

    // Create wrapper elements
    const wrapper = document.createElement('div');
    wrapper.className = 'dynamic-bg-wrapper';
    
    const overlay = document.createElement('div');
    overlay.className = 'dynamic-bg-overlay';

    document.body.prepend(overlay);
    document.body.prepend(wrapper);

    const slides = [];
    fallbackUrls.forEach((imgUrl, index) => {
        const slide = document.createElement('div');
        slide.className = 'dynamic-bg-slide' + (index === 0 ? ' active' : '');
        slide.style.backgroundImage = `url('${imgUrl}')`;
        wrapper.appendChild(slide);
        slides.push(slide);
    });

    let currentIdx = 0;
    // ⏱️ Rotate Background every 7.5 Seconds smoothly
    setInterval(() => {
        slides[currentIdx].classList.remove('active');
        currentIdx = (currentIdx + 1) % slides.length;
        slides[currentIdx].classList.add('active');
    }, 7500);
});
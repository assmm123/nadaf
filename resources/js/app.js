// Alpine تبدأ تلقائيًا مع Livewire (المنطق التفاعلي للقوالب يعمل عبر Alpine المدمجة)

// ==========================================================================
// لمسات نداف الفاخرة — شريط التقدم الذهبي + الكشف عند التمرير + زر الأعلى
// ==========================================================================

// 1) شريط التقدم الذهبي أعلى الصفحة
const progressBar = document.createElement('div');
progressBar.id = 'dsh-progress';
progressBar.style.cssText = `
    position: fixed; inset-inline: 0; top: 0; height: 2px; z-index: 200;
    background: linear-gradient(90deg, #a2833a, #d8b96b, #a2833a);
    width: 0; transition: width .18s ease; pointer-events: none;
`;
document.body.appendChild(progressBar);

// 2) زر العودة للأعلى — يظهر بعد 600px
const toTop = document.createElement('button');
toTop.id = 'dsh-totop';
toTop.setAttribute('aria-label', 'العودة للأعلى');
toTop.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px"><path d="M6 14.5l6-6 6 6"/></svg>';
toTop.style.cssText = `
    position: fixed; bottom: 26px; inset-inline-end: 26px; z-index: 150;
    width: 44px; height: 44px; border-radius: 50%;
    background: #0e1b2a; color: #d8b96b;
    border: 1px solid rgba(198, 164, 76, .4);
    display: grid; place-items: center; cursor: pointer;
    opacity: 0; pointer-events: none;
    transition: opacity .3s, transform .3s, background .3s;
`;
document.body.appendChild(toTop);

toTop.addEventListener('mouseenter', () => { toTop.style.background = '#c6a44c'; toTop.style.color = '#0e1b2a'; });
toTop.addEventListener('mouseleave', () => { toTop.style.background = '#0e1b2a'; toTop.style.color = '#d8b96b'; });
toTop.addEventListener('click', () => scrollTo({ top: 0, behavior: 'smooth' }));

// مراقبة التمرير — تُحدّث الشريط والزر معًا
const onScroll = () => {
    const h = document.documentElement;
    const max = h.scrollHeight - h.clientHeight;
    if (max > 0) progressBar.style.width = (h.scrollTop / max * 100) + '%';
    toTop.style.opacity = h.scrollTop > 600 ? '1' : '0';
    toTop.style.pointerEvents = h.scrollTop > 600 ? 'auto' : 'none';
};
addEventListener('scroll', onScroll, { passive: true });
onScroll();

// 3) الكشف عند التمرير — العناصر تظهر بنبالة
const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
if (! reduceMotion && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) {
                e.target.classList.add('rv-in');
                io.unobserve(e.target);
            }
        });
    }, { threshold: .1 });
    document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
} else {
    document.querySelectorAll('.reveal').forEach((el) => el.classList.add('rv-in'));
}

// تسجيل عامل الخدمة لتطبيق الويب (PWA) — في وضع الإنتاج فقط
if ('serviceWorker' in navigator && import.meta.env.PROD) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

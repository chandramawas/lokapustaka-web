document.addEventListener('DOMContentLoaded', () => {
    // Inisialisasi dark mode
    const prefersDark =
      localStorage.getItem('theme') === 'dark' ||
      (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
  
    if (prefersDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  
    // Handle tombol toggle (kalau ada)
    const toggleBtn = document.getElementById('theme-toggle');
    const iconLight = document.getElementById('icon-light');
    const iconDark = document.getElementById('icon-dark');
  
    function setIcon() {
      const isDark = document.documentElement.classList.contains('dark');
      iconLight?.classList.toggle('hidden', isDark);
      iconDark?.classList.toggle('hidden', !isDark);
    }
  
    setIcon();
  
    toggleBtn?.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      setIcon();
    });
  });
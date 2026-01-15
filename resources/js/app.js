import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Theme helpers: initialize and expose toggle function
(function(){
	try {
		window.setTheme = function(mode) {
			try {
				if (mode === 'dark') document.documentElement.classList.add('dark');
				else if (mode === 'light') document.documentElement.classList.remove('dark');
			} catch(e) {}
		};

		window.toggleTheme = function() {
			try {
				const isDark = document.documentElement.classList.toggle('dark');
				localStorage.setItem('theme', isDark ? 'dark' : 'light');
				return isDark ? 'dark' : 'light';
			} catch (e) { return null; }
		};

		// Sync on load (app.js may load after initial inline preferencing)
		var pref = null;
		try { pref = localStorage.getItem('theme'); } catch(e) { pref = null; }
		if (pref === 'dark') setTheme('dark');
		else if (pref === 'light') setTheme('light');
		// otherwise leave whatever was set by inline script or system
	} catch (e) {}
})();

Alpine.start();

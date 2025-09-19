async function initLanguage() {
    // Removed geolocation functionality to prevent location permission requests
    // Default to English language instead of trying to detect location
    
    const locationData = JSON.parse(localStorage.getItem('locationData'));
    const now = Date.now();
    const oneDaysInMillis = 1 * 24 * 60 * 60 * 1000;

    if (!locationData || now - locationData.timestamp > oneDaysInMillis) {
        // Set default language to English without requesting location permission
        localStorage.setItem('locationData', JSON.stringify({
            lang: 'en',
            timestamp: now
        }));

        const url = new URL(window.location.href);
        url.searchParams.set('lang', 'en');
        history.replaceState({}, '', url.pathname + '?' + url.searchParams.toString());
    } else {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', locationData.lang);
        history.replaceState({}, '', url.pathname + '?' + url.searchParams.toString());
    }
}

initLanguage();

async function changeLanguage(lang) {
    const locationData = JSON.parse(localStorage.getItem('locationData')) || {};
    locationData.lang = lang;
    locationData.timestamp = Date.now();
    localStorage.setItem('locationData', JSON.stringify(locationData));

    const url = new URL(window.location.href);
    url.searchParams.set('lang', lang);
    history.replaceState({}, '', url.pathname + '?' + url.searchParams.toString());

    location.reload();
}
async function initLanguage() {
    const locationData = JSON.parse(localStorage.getItem('locationData'));
    const now = Date.now();
    const  oneDaysInMillis= 1 * 24 * 60 * 60 * 1000;

    if (!locationData || now - locationData.timestamp > oneDaysInMillis) {
        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject);
            });
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`);
            const data = await response.json();
            const lang = data.address.country === 'Sri Lanka' ? 'si' : 'en';

            localStorage.setItem('locationData', JSON.stringify({
                lang,
                timestamp: now
            }));

            const url = new URL(window.location.href);
            url.searchParams.set('lang', lang);
            history.replaceState({}, '', url.pathname + '?' + url.searchParams.toString());
            location.reload();
        } catch (error) {
            localStorage.setItem('locationData', JSON.stringify({
                lang: 'en',
                timestamp: now
            }));

            const url = new URL(window.location.href);
            url.searchParams.set('lang', 'en');
            history.replaceState({}, '', url.pathname + '?' + url.searchParams.toString());
            location.reload();
        }
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
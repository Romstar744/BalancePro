function fadeInPage() {
    document.body.classList.add('hidden');
    
    const observer = new MutationObserver(() => {
        if (document.readyState === 'complete') {
            observer.disconnect();
            setTimeout(() => {
                document.body.classList.remove('hidden');
                document.body.classList.add('visible');
            }, 100); 
        }
    });
    
    observer.observe(document, { childList: true, subtree: true });
}

window.onload = fadeInPage;

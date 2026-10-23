(() => {
  const input = document.querySelector('[data-swi-search]');
  const target = document.getElementById('swi-results');
  if (!input || !target) return;
  let controller, timer;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(async () => {
      controller?.abort();
      controller = new AbortController();
      const url = new URL(location.href);
      url.searchParams.delete('page');
      if (input.value.trim()) url.searchParams.set('q', input.value.trim());
      else url.searchParams.delete('q');
      try {
        const response = await fetch(url, { signal: controller.signal });
        if (!response.ok) return;
        const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
        const next = doc.querySelector('#swi-results');
        if (!next) return;
        target.innerHTML = next.innerHTML;
        history.replaceState({}, '', url);
      } catch (error) { if (error.name !== 'AbortError') console.error('Inventory search failed', error); }
    }, 300);
  });
})();

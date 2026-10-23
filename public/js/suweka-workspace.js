function swSearch(input, selector){const term=input.value.trim().toLowerCase();document.querySelectorAll('.'+selector).forEach(el=>el.hidden=!el.dataset.search.includes(term))}
(function(){
  const KEY='sw_sale_cart';
  function cartCount(){try{const cart=JSON.parse(sessionStorage.getItem(KEY)||'[]');return Array.isArray(cart)?cart.reduce((n,l)=>n+Math.max(0,Number(l.quantity)||0),0):0;}catch(_){return 0;}}
  function update(){const count=cartCount();document.querySelectorAll('.sw-bottom-count').forEach(el=>{el.textContent=count;el.hidden=count===0;});}
  document.addEventListener('DOMContentLoaded',()=>{
    update();
    document.querySelectorAll('[data-sale-cart-nav]').forEach(link=>link.addEventListener('click',event=>{
      const onSale=location.pathname.includes('/workspace/sale/new');
      if(onSale){event.preventDefault();window.dispatchEvent(new Event('sw:open-cart'));}
      else {sessionStorage.setItem('sw_open_cart_next','1');}
    }));
    if(location.pathname.includes('/workspace/sale/new')&&sessionStorage.getItem('sw_open_cart_next')){
      sessionStorage.removeItem('sw_open_cart_next');
      setTimeout(()=>window.dispatchEvent(new Event('sw:open-cart')),150);
    }
  });
  window.addEventListener('sw:cart-changed',update);
  window.addEventListener('storage',event=>{if(event.key===KEY)update();});
  window.addEventListener('pageshow',update);
})();

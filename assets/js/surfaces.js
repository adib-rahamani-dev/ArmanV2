(()=>{
  'use strict';
  const $=(selector,scope=document)=>scope.querySelector(selector);
  const $$=(selector,scope=document)=>[...scope.querySelectorAll(selector)];
  const root=document.documentElement;
  const header=$('.surface-header');
  const menu=$('.surface-menu');
  const mobileMenu=$('.surface-mobile-menu');
  const toast=$('.surface-toast');
  const themeButtons=$$('.surface-theme');
  const progressBar=$('.surface-progress i');
  const themeMeta=$('meta[name="theme-color"]');

  const applyTheme=theme=>{
    root.dataset.theme=theme;
    const light=theme==='light';
    themeButtons.forEach(button=>{
      button.setAttribute('aria-label',light?'تغییر به حالت تیره':'تغییر به حالت روشن');
      button.setAttribute('title',light?'حالت تیره':'حالت روشن');
      button.setAttribute('aria-pressed',String(light));
    });
    themeMeta?.setAttribute('content',light?'#F4F2ED':'#0B0C0F');
  };
  try{
    const savedTheme=localStorage.getItem('redt-theme')||localStorage.getItem('redt-surface-theme');
    applyTheme(savedTheme==='light'||savedTheme==='dark'?savedTheme:(root.dataset.theme||'dark'));
  }catch{}

  const notify=(message,error=false)=>{
    if(!toast)return;
    toast.textContent=message;
    toast.style.background=error?'#8f1620':'';
    toast.classList.add('show');
    clearTimeout(notify.timer);
    notify.timer=setTimeout(()=>toast.classList.remove('show'),3400);
  };
  const closeMenu=()=>{
    mobileMenu?.classList.remove('open');
    menu?.setAttribute('aria-expanded','false');
    menu?.setAttribute('aria-label','بازکردن منو');
    mobileMenu?.setAttribute('aria-hidden','true');
  };

  themeButtons.forEach(button=>button.addEventListener('click',()=>{
    const theme=root.dataset.theme==='dark'?'light':'dark';
    applyTheme(theme);
    try{localStorage.setItem('redt-theme',theme);}catch{}
    window.dataLayer=window.dataLayer||[];
    window.dataLayer.push({event:'theme_change',theme});
  }));
  menu?.addEventListener('click',()=>{
    const open=!mobileMenu?.classList.contains('open');
    mobileMenu?.classList.toggle('open',open);
    menu.setAttribute('aria-expanded',String(open));
    menu.setAttribute('aria-label',open?'بستن منو':'بازکردن منو');
    mobileMenu?.setAttribute('aria-hidden',String(!open));
  });
  $$('.surface-mobile-menu a').forEach(link=>link.addEventListener('click',closeMenu));
  addEventListener('resize',()=>{if(innerWidth>900)closeMenu();},{passive:true});
  let progressFrame=0;
  const updateScrollState=()=>{
    header?.classList.toggle('is-compact',scrollY>45);
    if(progressBar){
      const max=document.documentElement.scrollHeight-innerHeight;
      progressBar.style.width=`${max>0?Math.min(100,Math.max(0,scrollY/max*100)):0}%`;
    }
    progressFrame=0;
  };
  addEventListener('scroll',()=>{
    if(!progressFrame)progressFrame=requestAnimationFrame(updateScrollState);
  },{passive:true});
  updateScrollState();

  $$('[data-service-pick]').forEach(link=>link.addEventListener('click',()=>{
    const input=$(`input[name="service"][value="${CSS.escape(link.dataset.servicePick)}"]`);
    if(input)input.checked=true;
  }));

  if('IntersectionObserver' in window){
    const sections=$$('main [id]');
    const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{
      if(!entry.isIntersecting)return;
      $$('.app-dock a[href^="#"]').forEach(link=>link.classList.toggle('active',link.getAttribute('href')===`#${entry.target.id}`));
    }),{rootMargin:'-30% 0px -60%'});
    sections.forEach(section=>observer.observe(section));

    if(!matchMedia('(prefers-reduced-motion:reduce)').matches){
      const revealItems=$$('main > section, .offer-card, .case-card, .digital-card, .steps-grid article, .process-cards article');
      const revealObserver=new IntersectionObserver(entries=>entries.forEach(entry=>{
        if(entry.isIntersecting){
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      }),{rootMargin:'0px 0px -8%'});
      revealItems.forEach(item=>{
        item.classList.add('surface-reveal');
        revealObserver.observe(item);
      });
    }
  }
  if(matchMedia('(pointer:coarse)').matches&&!matchMedia('(prefers-reduced-motion:reduce)').matches){
    $$('.app-dock a,.surface-btn,.digital-card button').forEach(item=>item.addEventListener('click',()=>navigator.vibrate?.(7)));
  }

  $$('[data-lead-form]').forEach(form=>form.addEventListener('submit',async event=>{
    event.preventDefault();
    const message=$('.form-message',form);
    const button=$('button[type="submit"]',form);
    const original=button.innerHTML;
    message.textContent='';
    message.classList.remove('success');
    button.disabled=true;
    button.textContent='در حال ثبت درخواست…';
    try{
      const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{Accept:'application/json'}});
      const data=await response.json();
      if(!response.ok||!data.ok)throw new Error(data.message||'ثبت درخواست انجام نشد.');
      message.textContent='درخواست ثبت شد؛ برای هماهنگی با شما تماس می‌گیریم.';
      message.classList.add('success');
      form.reset();
      notify('درخواست شما با موفقیت ثبت شد.');
    }catch(reason){
      message.textContent=reason.message||'ارتباط با سرور برقرار نشد.';
      notify(message.textContent,true);
    }finally{
      button.disabled=false;
      button.innerHTML=original;
    }
  }));

  let digitalFilter='all';
  let digitalQuery='';
  const searchInput=$('[data-digital-search]');
  const applyDigital=()=>{
    let count=0;
    $$('[data-digital-card]').forEach(card=>{
      const matchesFilter=digitalFilter==='all'||card.dataset.group===digitalFilter;
      const matchesText=!digitalQuery||(card.dataset.search||'').toLocaleLowerCase('fa').includes(digitalQuery);
      card.hidden=!(matchesFilter&&matchesText);
      if(!card.hidden)count++;
    });
    const empty=$('.digital-empty');
    if(empty)empty.hidden=count>0;
    const countLabel=$('[data-digital-count]');
    if(countLabel)countLabel.textContent=`${count.toLocaleString('fa-IR')} سرویس قابل بررسی`;
  };
  searchInput?.addEventListener('input',event=>{
    digitalQuery=event.target.value.trim().toLocaleLowerCase('fa');
    applyDigital();
  });
  $$('[data-search-term]').forEach(button=>button.addEventListener('click',()=>{
    if(!searchInput)return;
    searchInput.value=button.dataset.searchTerm;
    digitalQuery=button.dataset.searchTerm.toLocaleLowerCase('fa');
    applyDigital();
    document.querySelector('#catalog')?.scrollIntoView({behavior:'smooth'});
  }));
  addEventListener('keydown',event=>{
    if(event.key==='/'&&searchInput&&document.activeElement?.tagName!=='INPUT'&&document.activeElement?.tagName!=='TEXTAREA'){
      event.preventDefault();searchInput.focus();
    }
  });
  $$('[data-digital-filter]').forEach(button=>button.addEventListener('click',()=>{
    digitalFilter=button.dataset.digitalFilter;
    $$('[data-digital-filter]').forEach(item=>item.classList.toggle('active',item===button));
    applyDigital();
  }));

  const sheet=$('.order-sheet');
  const purchaseForm=$('[data-digital-purchase]');
  let lastFocus=null;
  const closeSheet=()=>{
    if(!sheet)return;
    sheet.hidden=true;
    document.body.classList.remove('sheet-open');
    lastFocus?.focus?.();
  };
  $$('[data-sheet-close]').forEach(button=>button.addEventListener('click',closeSheet));
  $$('[data-digital-order]').forEach(button=>button.addEventListener('click',()=>{
    if(!sheet||!purchaseForm)return;
    lastFocus=button;
    $('[name="product_id"]',purchaseForm).value=button.dataset.id;
    $('[name="plan"]',purchaseForm).value='هماهنگی با کارشناس';
    $('[data-order-title]').textContent=button.dataset.title;
    $('[data-order-kind]').textContent=button.dataset.kind;
    purchaseForm.hidden=false;
    $('.sheet-success').hidden=true;
    sheet.hidden=false;
    document.body.classList.add('sheet-open');
    $('[name="name"]',purchaseForm)?.focus();
  }));
  addEventListener('keydown',event=>{
    if(event.key!=='Escape')return;
    if(!sheet?.hidden)closeSheet();
    else if(mobileMenu?.classList.contains('open'))closeMenu();
  });
  purchaseForm?.addEventListener('submit',async event=>{
    event.preventDefault();
    const message=$('.form-message',purchaseForm);
    const button=$('button[type="submit"]',purchaseForm);
    const original=button.innerHTML;
    message.textContent='';button.disabled=true;button.textContent='در حال ثبت امن درخواست…';
    try{
      const response=await fetch(purchaseForm.action,{method:'POST',body:new FormData(purchaseForm),headers:{Accept:'application/json'}});
      const data=await response.json();
      if(!response.ok||!data.ok)throw new Error(data.message||'ثبت سفارش انجام نشد.');
      purchaseForm.hidden=true;
      const success=$('.sheet-success');success.hidden=false;
      $('[data-sheet-code]').textContent=data.order_id;
      const verificationLink=$('[data-sheet-verify]');
      if(verificationLink&&data.verification_url)verificationLink.href=data.verification_url;
      purchaseForm.reset();
      notify('درخواست سرویس با موفقیت ثبت شد.');
    }catch(reason){
      message.textContent=reason.message||'ارتباط با سرور برقرار نشد.';
      notify(message.textContent,true);
    }finally{
      button.disabled=false;button.innerHTML=original;
    }
  });
})();

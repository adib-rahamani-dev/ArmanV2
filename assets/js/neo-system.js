(()=>{
  'use strict';
  const $=(selector,scope=document)=>scope.querySelector(selector);
  const $$=(selector,scope=document)=>[...scope.querySelectorAll(selector)];

  const track=(event,detail={})=>{
    window.dataLayer=window.dataLayer||[];
    window.dataLayer.push({event,...detail});
    window.dispatchEvent(new CustomEvent('redt:analytics',{detail:{event,...detail}}));
  };

  $$('[data-track]').forEach(element=>element.addEventListener('click',()=>track(element.dataset.track||'link_click',{
    surface:document.body.className.match(/surface-(\w+)/)?.[1]||'unknown',
    label:(element.textContent||'').trim().slice(0,80)
  })));

  const revealTargets=$$('[data-reveal]');
  if('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches){
    const revealObserver=new IntersectionObserver(entries=>entries.forEach(entry=>{
      if(entry.isIntersecting){entry.target.classList.add('is-visible');revealObserver.unobserve(entry.target);}
    }),{threshold:.12,rootMargin:'0px 0px -30px'});
    revealTargets.forEach(target=>revealObserver.observe(target));
  }else revealTargets.forEach(target=>target.classList.add('is-visible'));

  const process=$('[data-signal-process]');
  if(process){
    const activate=()=>process.classList.add('is-visible');
    if('IntersectionObserver' in window){
      const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){activate();observer.disconnect();}}),{threshold:.3});
      observer.observe(process);
    }else activate();
  }

  $$('.faq-list details').forEach((details,index)=>details.addEventListener('toggle',()=>{
    if(details.open)track('faq_open',{faq_index:index+1,question:details.querySelector('summary')?.textContent.trim().slice(0,100)});
  }));

  const compareChecks=$$('[data-compare-product]');
  const comparePanel=$('[data-compare-panel]');
  const compareTable=$('[data-compare-table]');
  const compareSummary=$('[data-compare-summary]');
  const renderComparison=()=>{
    const cards=compareChecks.filter(check=>check.checked).map(check=>check.closest('[data-digital-card]')).filter(Boolean);
    if(comparePanel)comparePanel.hidden=cards.length===0;
    if(compareSummary)compareSummary.textContent=cards.length?`${cards.length.toLocaleString('fa-IR')} محصول انتخاب شده`:'محصولی انتخاب نشده است';
    if(!compareTable)return;
    compareTable.replaceChildren();
    if(!cards.length)return;
    const table=document.createElement('table');
    const body=document.createElement('tbody');
    [['محصول','title'],['قیمت','price'],['تحویل','delivery'],['پشتیبانی','warranty']].forEach(([label,key])=>{
      const row=document.createElement('tr');
      const heading=document.createElement('th');heading.scope='row';heading.textContent=label;row.append(heading);
      cards.forEach(card=>{const cell=document.createElement('td');cell.textContent=card.dataset[key]||'—';row.append(cell);});
      body.append(row);
    });
    table.append(body);compareTable.append(table);
  };
  compareChecks.forEach(check=>check.addEventListener('change',()=>{
    if(compareChecks.filter(item=>item.checked).length>3){check.checked=false;const toast=$('.surface-toast');if(toast){toast.textContent='برای مقایسه هم‌زمان حداکثر سه محصول انتخاب کنید.';toast.classList.add('show');setTimeout(()=>toast.classList.remove('show'),3000);}return;}
    renderComparison();
  }));
  $('[data-compare-clear]')?.addEventListener('click',()=>{compareChecks.forEach(check=>{check.checked=false;});renderComparison();});

  const trackingForm=$('[data-order-tracking]');
  trackingForm?.addEventListener('submit',async event=>{
    event.preventDefault();
    const message=$('.form-message',trackingForm);const button=$('button[type="submit"]',trackingForm);
    if(message)message.textContent='';if(button)button.disabled=true;
    try{
      const response=await fetch(trackingForm.action,{method:'POST',body:new FormData(trackingForm),headers:{Accept:'application/json'}});
      const payload=await response.json();
      if(!response.ok||!payload.ok)throw new Error(payload.message||'امکان پیگیری سفارش نبود.');
      if(message)message.textContent=`وضعیت سفارش: ${payload.status_label}`;
    }catch(error){if(message)message.textContent=error instanceof Error?error.message:'امکان پیگیری سفارش نبود.';}
    finally{if(button)button.disabled=false;}
  });

  const form=$('[data-consultation-form]');
  if(!form)return;

  const steps=$$('[data-form-step]',form);
  const markers=$$('.form-signal span',form);
  const rails=$$('.form-signal i',form);
  const stepLabel=$('[data-step-label]',form);
  const success=$('[data-form-success]',form);
  const stepNames=['موضوع درخواست','هدف و زمان','اطلاعات تماس'];
  let current=0;
  let started=false;
  let submitted=false;

  const normalizeDigits=value=>value.replace(/[۰-۹]/g,d=>'۰۱۲۳۴۵۶۷۸۹'.indexOf(d).toString()).replace(/[٠-٩]/g,d=>'٠١٢٣٤٥٦٧٨٩'.indexOf(d).toString());
  const showStep=index=>{
    current=Math.max(0,Math.min(index,steps.length-1));
    steps.forEach((step,i)=>step.classList.toggle('is-active',i===current));
    markers.forEach((marker,i)=>marker.classList.toggle('is-active',i<=current));
    rails.forEach((rail,i)=>rail.classList.toggle('is-active',i<current));
    if(stepLabel)stepLabel.textContent=stepNames[current];
    const legend=$('legend',steps[current]);
    if(legend){legend.setAttribute('tabindex','-1');legend.focus({preventScroll:true});}
    form.scrollIntoView({behavior:matchMedia('(prefers-reduced-motion: reduce)').matches?'auto':'smooth',block:'center'});
    track('consultation_form_step',{step:current+1,step_name:stepNames[current]});
  };
  const setError=(step,message,field)=>{
    const error=$('[data-error]',step);
    if(error)error.textContent=message;
    if(field){field.setAttribute('aria-invalid','true');field.focus();}
    track('consultation_form_error',{step:current+1,error:message});
    return false;
  };
  const clearErrors=step=>{
    const error=$('[data-error]',step);
    if(error)error.textContent='';
    $$('[aria-invalid="true"]',step).forEach(field=>field.removeAttribute('aria-invalid'));
  };
  const validate=index=>{
    const step=steps[index];
    clearErrors(step);
    if(index===0){
      const selected=$('input[name="service"]:checked',step);
      if(!selected)return setError(step,'لطفاً یک موضوع را انتخاب کنید.',$('input[name="service"]',step));
    }
    if(index===1){
      const message=$('[name="message"]',step);
      if(!message||message.value.trim().length<10)return setError(step,'لطفاً هدف یا مشکل را در حد یک جمله توضیح دهید.',message);
    }
    if(index===2){
      const name=$('[name="name"]',step);
      const phone=$('[name="phone"]',step);
      if(!name||name.value.trim().length<3)return setError(step,'لطفاً نام و نام خانوادگی را وارد کنید.',name);
      const normalized=normalizeDigits(phone?.value||'').replace(/[^0-9+]/g,'');
      if(!/^(?:\+98|0)?9\d{9}$/.test(normalized))return setError(step,'شماره موبایل معتبر وارد کنید؛ مانند 0912 000 0000.',phone);
      phone.value=normalized;
    }
    return true;
  };

  form.addEventListener('input',()=>{
    if(!started){started=true;track('consultation_form_start',{step:1});}
    clearErrors(steps[current]);
  },{once:false});
  $$('.form-next',form).forEach(button=>button.addEventListener('click',()=>{if(validate(current))showStep(current+1);}));
  $$('.form-back',form).forEach(button=>button.addEventListener('click',()=>showStep(current-1)));

  form.addEventListener('submit',async event=>{
    event.preventDefault();
    if(current!==2){if(validate(current))showStep(current+1);return;}
    if(!validate(2))return;
    const button=$('.form-submit',form);
    const original=button?.innerHTML||'';
    if(button){button.disabled=true;button.textContent='در حال ارسال…';}
    try{
      const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{Accept:'application/json'}});
      const payload=await response.json();
      if(!response.ok||!payload.ok)throw new Error(payload.message||'ارسال درخواست انجام نشد.');
      submitted=true;
      steps.forEach(step=>step.classList.remove('is-active'));
      if(success)success.hidden=false;
      track('consultation_form_submit',{service:new FormData(form).get('service')||''});
      form.reset();
    }catch(error){
      setError(steps[2],error instanceof Error?error.message:'ارسال درخواست انجام نشد.');
    }finally{
      if(button){button.disabled=false;button.innerHTML=original;}
    }
  });

  addEventListener('pagehide',()=>{
    if(started&&!submitted)track('consultation_form_abandon',{last_step:current+1});
  });
})();

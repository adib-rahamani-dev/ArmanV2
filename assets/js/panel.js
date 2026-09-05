(()=>{
  'use strict';
  const $=(s,c=document)=>c.querySelector(s), $$=(s,c=document)=>[...c.querySelectorAll(s)];
  const root=document.documentElement, sidebar=$('#sidebar'), toast=$('.toast'), admin=window.REDT_ADMIN;
  const notify=(message,isError=false)=>{
    if(!toast)return;
    toast.textContent=message; toast.classList.toggle('error',isError); toast.classList.add('show');
    clearTimeout(notify.timer); notify.timer=setTimeout(()=>toast.classList.remove('show'),3200);
  };
  const showView=()=>{
    const key=(location.hash||'#overview').slice(1), target=$(`[data-view="${CSS.escape(key)}"]`)||$('[data-view="overview"]');
    $$('.panel-view').forEach(view=>view.classList.toggle('active',view===target));
    $$('[data-view-link]').forEach(link=>link.classList.toggle('active',link.dataset.viewLink===target.dataset.view));
    sidebar?.classList.remove('open'); scrollTo({top:0,behavior:'smooth'});
  };
  addEventListener('hashchange',showView); showView();
  $$('[data-sidebar]').forEach(button=>button.addEventListener('click',()=>sidebar?.classList.toggle('open')));
  $('.theme-switch')?.addEventListener('click',()=>{
    const theme=root.dataset.theme==='dark'?'light':'dark'; root.dataset.theme=theme;
    try{localStorage.setItem('redt-panel-theme',theme);}catch{}
  });
  try{root.dataset.theme=localStorage.getItem('redt-panel-theme')||'light';}catch{}
  $$('[data-toast]').forEach(button=>button.addEventListener('click',()=>notify(button.dataset.toast)));
  $$('[data-modal-open]').forEach(button=>button.addEventListener('click',()=>{const modal=document.getElementById(button.dataset.modalOpen);if(modal)modal.hidden=false;}));
  $$('[data-modal-close]').forEach(button=>button.addEventListener('click',()=>button.closest('.modal').hidden=true));
  addEventListener('keydown',event=>{
    if(event.key==='Escape')$$('.modal').forEach(modal=>modal.hidden=true);
    if((event.ctrlKey||event.metaKey)&&event.key.toLowerCase()==='k'){event.preventDefault();$('.search input')?.focus();}
  });

  const orderRows=$$('[data-order-row]');
  const applyOrderFilters=()=>{
    const query=($('[data-admin-search]')?.value||'').trim().toLocaleLowerCase('fa');
    const active=$('[data-order-filter].active')?.dataset.orderFilter||'all';
    orderRows.forEach(row=>{
      const matchesText=!query||(row.dataset.search||'').toLocaleLowerCase('fa').includes(query);
      const matchesType=active==='all'||row.dataset.state===active||row.dataset.source===active;
      row.hidden=!(matchesText&&matchesType);
    });
  };
  $('[data-admin-search]')?.addEventListener('input',applyOrderFilters);
  $$('[data-order-filter]').forEach(button=>button.addEventListener('click',()=>{
    $$('[data-order-filter]').forEach(item=>item.classList.toggle('active',item===button)); applyOrderFilters();
  }));
  const send=async values=>{
    if(!admin)throw new Error('اتصال پنل فعال نیست.');
    const body=new FormData(); Object.entries({...values,csrf:admin.csrf}).forEach(([key,value])=>body.append(key,String(value)));
    const response=await fetch(admin.endpoint,{method:'POST',body,headers:{Accept:'application/json'}});
    const data=await response.json();
    if(!response.ok||!data.ok)throw new Error(data.message||'عملیات انجام نشد.');
    return data;
  };
  $$('[data-order-status]').forEach(select=>select.addEventListener('change',async()=>{
    const previous=select.dataset.previous||'new', row=select.closest('[data-order-row]');
    select.classList.add('status-saving'); select.disabled=true;
    try{
      const data=await send({action:'order_status',key:select.dataset.key,status:select.value});
      select.dataset.previous=select.value; row.dataset.state=select.value;
      select.className=`status-select status-${select.value}`; notify(data.message);
    }catch(error){select.value=previous;notify(error.message,true);}
    finally{select.disabled=false;select.classList.remove('status-saving');}
  }));
  $$('[data-order-status]').forEach(select=>select.dataset.previous=select.value);
  $$('[data-publish]').forEach(input=>input.addEventListener('change',async()=>{
    const previous=!input.checked, item=input.closest('[data-inventory-item]'), label=input.closest('.publish-switch').querySelector('em');
    item?.classList.add('is-saving'); input.disabled=true;
    try{
      const data=await send({action:'catalog_toggle',group:input.dataset.group,id:input.dataset.id,active:input.checked?'1':'0'});
      label.textContent=input.checked?'فعال':'غیرفعال'; notify(data.message);
    }catch(error){input.checked=previous;notify(error.message,true);}
    finally{input.disabled=false;item?.classList.remove('is-saving');}
  }));
})();

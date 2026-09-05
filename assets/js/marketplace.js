(()=>{
  'use strict';
  const $=(s,c=document)=>c.querySelector(s), $$=(s,c=document)=>[...c.querySelectorAll(s)];
  let filter='all', query='', selected=[];
  const grid=$('.market-grid'), cards=$$('.market-card');
  cards.forEach((card,index)=>card.dataset.originalOrder=String(index));
  const toNumber=value=>Number(String(value||'').replace(/[۰-۹]/g,d=>'۰۱۲۳۴۵۶۷۸۹'.indexOf(d)).replace(/[^0-9]/g,''))||0;
  const apply=()=>{
    let visible=0;
    cards.forEach(card=>{
      const state=card.classList.contains('is-active')?'active':'inactive';
      card.hidden=!((filter==='all'||filter===state)&&(!query||(card.dataset.search||'').toLocaleLowerCase('fa').includes(query)));
      if(!card.hidden)visible++;
    });
    const empty=$('.search-empty:not(.article-empty)');
    if(empty)empty.hidden=visible>0;
  };
  const renderCompare=()=>{
    const drawer=$('.compare-drawer');
    if(!drawer)return;
    drawer.hidden=selected.length===0;
    $('[data-compare-count]').textContent=`${selected.length.toLocaleString('fa-IR')} محصول انتخاب شده`;
    $('.compare-chips').innerHTML=selected.map(item=>`<span>${item.title}</span>`).join('');
  };
  $$('[data-filter]').forEach(button=>button.addEventListener('click',()=>{
    filter=button.dataset.filter;
    $$('[data-filter]').forEach(item=>item.classList.toggle('active',item===button));
    apply();
  }));
  $('[data-store-search]')?.addEventListener('input',event=>{query=event.target.value.trim().toLocaleLowerCase('fa');apply();});
  $('.market-toolbar select')?.addEventListener('change',event=>{
    if(!grid)return;
    const direction=event.target.selectedIndex===1?1:event.target.selectedIndex===2?-1:0;
    [...cards].sort((a,b)=>direction?direction*(toNumber(a.dataset.price)-toNumber(b.dataset.price)):Number(a.dataset.originalOrder)-Number(b.dataset.originalOrder)).forEach(card=>grid.append(card));
  });
  $('[data-article-search]')?.addEventListener('input',event=>{
    const value=event.target.value.trim().toLocaleLowerCase('fa');
    let visible=0;
    $$('.article-card').forEach(card=>{card.hidden=!!value&&!(card.dataset.search||'').toLocaleLowerCase('fa').includes(value);if(!card.hidden)visible++;});
    const empty=$('.article-empty'); if(empty)empty.hidden=visible>0;
  });
  $$('[data-compare]').forEach(input=>input.addEventListener('change',()=>{
    const card=input.closest('.market-card'), id=card.dataset.productId;
    if(input.checked){
      if(selected.length>=3){input.checked=false;return;}
      selected.push({id,title:card.dataset.title,price:card.dataset.price,category:card.dataset.category});
    }else selected=selected.filter(item=>item.id!==id);
    renderCompare();
  }));
  $('[data-compare-open]')?.addEventListener('click',()=>{
    if(selected.length<2)return;
    $('.dynamic-compare').innerHTML=`<table><thead><tr><th>مشخصات</th>${selected.map(x=>`<th>${x.title}</th>`).join('')}</tr></thead><tbody><tr><td>دسته‌بندی</td>${selected.map(x=>`<td>${x.category}</td>`).join('')}</tr><tr><td>قیمت</td>${selected.map(x=>`<td><b>${x.price}</b></td>`).join('')}</tr><tr><td>وضعیت</td>${selected.map(()=>'<td>موجود و قابل سفارش</td>').join('')}</tr></tbody></table>`;
    $('.compare-modal').hidden=false;
  });
  $$('[data-compare-close]').forEach(button=>button.addEventListener('click',()=>$('.compare-modal').hidden=true));

  const modal=$('.purchase-modal'), form=$('[data-purchase-form]');
  const syncPlan=()=>{
    const checked=$('.plan-picker input:checked'), plan=checked?.value||'پلن استاندارد', price=checked?.dataset.planPrice;
    if($('[data-purchase-plan]'))$('[data-purchase-plan]').value=plan;
    if(price){$('[data-product-price]').textContent=`${price} تومان`;$('[data-purchase-price]').textContent=`${price} تومان`;}
  };
  $$('.plan-picker input').forEach(input=>input.addEventListener('change',syncPlan));
  syncPlan();
  const closePurchase=()=>{if(!modal)return;modal.hidden=true;document.body.style.overflow='';};
  $$('[data-purchase-close]').forEach(button=>button.addEventListener('click',closePurchase));
  $('[data-buy]')?.addEventListener('click',()=>{
    if(!modal)return;
    syncPlan(); modal.hidden=false; document.body.style.overflow='hidden';
    form?.querySelector('[name="name"]')?.focus();
  });
  addEventListener('keydown',event=>{if(event.key==='Escape'&&!modal?.hidden)closePurchase();});
  form?.addEventListener('submit',async event=>{
    event.preventDefault();
    const error=$('.purchase-error'), submit=$('.purchase-submit');
    error.textContent=''; submit.disabled=true; submit.textContent='در حال ثبت امن سفارش…';
    try{
      const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{Accept:'application/json'}});
      const data=await response.json();
      if(!response.ok||!data.ok)throw new Error(data.message||'ثبت سفارش انجام نشد.');
      form.hidden=true;
      const success=$('.purchase-success'); success.hidden=false;
      $('[data-order-id]').textContent=data.order_id;
      const notice=$('.mini-cart'); if(notice){notice.hidden=false;setTimeout(()=>notice.hidden=true,5000);}
    }catch(reason){error.textContent=reason.message||'ارتباط با سرور برقرار نشد.';}
    finally{submit.disabled=false;submit.innerHTML='ثبت امن درخواست <i>←</i>';}
  });
})();

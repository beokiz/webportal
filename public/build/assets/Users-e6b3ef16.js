import{d as Be}from"./index-0d7197fb.js";import{K as Ae,j as u,k as Z,l as $,A as ge,T as R,r as i,o as k,f as X,a as e,u as m,w as l,F as be,Z as De,b as n,m as v,d as f,c as L,h as B,q as xe,t as T,i as Te,O as Oe}from"./app-d4e270fa.js";import{c as we,b as je,f as qe}from"./common-ea192878.js";import{_ as Ie}from"./AuthenticatedLayout-70341763.js";import"./ApplicationLogo-20741013.js";const Ze=n("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Benutzer",-1),Re={class:"tw-flex tw-items-center tw-justify-end"},We=n("span",{class:"tw-text-h5"},"Neuen Benutzer hinzufügen",-1),Je=n("p",null,"Sind Sie sicher, dass Sie den aktuellen Benutzer löschen möchten?",-1),Ke={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},Ge={class:"tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6"},He={class:"tw-w-full"},Me={align:"center"},Qe={class:"tw-flex tw-items-center"},Xe=n("span",null,"Verifizierungsmail erneut senden",-1),Ye={class:"tw-text-center"},el=n("span",null,"Verifikations-Email erneut senden",-1),ll=n("span",null,"Zulassung senden",-1),al=n("span",null,"Benutzer bearbeiten",-1),tl=n("span",null,"Benutzer löschen",-1),ol={class:"tw-py-6"},sl={key:0,class:"tw-mb-4"},nl=n("h3",{class:"tw-mb-4"},"Die Tabelle ist leer. Bitte setzen Sie die Suchfilter zurück.",-1),cl={__name:"Users",props:{items:Array,currentPage:Number,perPage:Number,lastPage:Number,total:Number,paging:Boolean,orderBy:String,sort:String,filters:Object,errors:Object,roles:Array},setup(W){const g=W;Be.Inertia.on("success",o=>{let a=o.detail.page.props;o.detail.page.component==="Users/Users"&&a&&(O.value=a.currentPage,A.value=a.perPage,he.value=a.orderBy,Ve.value=a.sort,ee.value=a.total,ke.value=a.lastPage)});const Y=Ae().props.auth.user??{},O=u(g.currentPage),A=u(g.perPage),he=u(g.orderBy),Ve=u(g.sort),ee=u(g.total),ke=u(g.lastPage),y=u(g.filters.status??null),U=u(g.filters.full_name??null),F=u(g.filters.email??null),S=u(null),le=u(""),b=u(g.errors||{}),J=u(!1),K=u(!1),z=u(!1),j=u(!1),q=u(!1),ae=u(null),te=u(null),V=u(null),h=u(null),ye=[{title:"Status",key:"is_online",width:"5%",sortable:!1,align:"center"},{title:"Name",key:"first_name",width:"20%",sortable:!0},{title:"Email",key:"email",width:"30%",sortable:!0},{title:"Rolle",key:"primary_role_name",width:"10%",sortable:!0},{title:"Letzter Login",key:"last_seen_at",width:"15%",sortable:!0},{title:"Erster Login",key:"first_login_at",width:"10%",sortable:!0},{title:"Aktionen",key:"actions",width:"10%",sortable:!1,align:"center"}],Ue=[{title:"Ja",value:"true"},{title:"Nein",value:"false"}],Fe=Z(()=>g.items.map(o=>{const a={...o};for(const c in a)(a[c]===null||a[c]===void 0)&&(a[c]="-");return a})),oe=Z(()=>y.value===null&&U.value===null&&F.value===null&&S.value===null&&V.value===null&&h.value===null),Se=Z(()=>y.value!==null||U.value!==null||F.value!==null||S.value!==null||V.value!==null||h.value!==null);$(j,o=>{o||P()}),$(y,o=>{E()}),$(U,ge.debounce(o=>{E()},500)),$(F,ge.debounce(o=>{E()},500)),$(S,o=>{E()}),$(V,o=>{ae.value=o?we(o):null,E()}),$(h,o=>{te.value=o?we(o):null,E()});const E=()=>{z.value=!0,le.value=String(Date.now())},se=async({page:o,itemsPerPage:a,sortBy:c,clearFilters:w})=>{if(w&&(y.value=null,U.value=null,F.value=null,S.value=null,V.value=null,h.value=null),o===O.value&&w||oe||Se){z.value=!0;let s={page:o,per_page:a};c&&c.length>0?(s.order_by=c[0].key,s.sort=c[0].order):(s.order_by=null,s.sort=null),y.value&&(s.status=y.value),U.value&&(s.full_name=U.value),F.value&&(s.email=F.value),S.value&&(s.with_roles=S.value),V.value&&(s.first_login_at=V.value.toLocaleString()),h.value&&(console.log(h.value.toLocaleString()),s.last_seen_at=h.value.toLocaleString()),await Oe.get(route(route().current()),s,{preserveScroll:!0,preserveState:!0,onCancelToken:_=>{},onCancel:()=>{},onBefore:_=>{z.value=!0},onStart:_=>{},onProgress:_=>{},onSuccess:_=>{O.value=s.page,A.value=s.per_page},onError:_=>{console.log(_)},onFinish:_=>{z.value=!1}})}},ze=o=>{N.id=o.id,q.value=!0},N=R({id:null}),Pe=async()=>{N.processing=!0;let o={onSuccess:a=>{P()},onError:a=>{b.value=a},onFinish:()=>{N.processing=!1}};N.delete(route("users.destroy",{id:N.id}),o)},P=()=>{j.value=!1,q.value=!1,N.id=null,d.reset(),d.clearErrors(),b.value={}},d=R({first_name:null,last_name:null,email:null,role:null,two_factor_auth_enabled:!1,phone_number:null,send_invite_email:!1}),$e=async()=>{d.processing=!0;let o={onSuccess:a=>{P()},onError:a=>{b.value=a},onFinish:()=>{d.processing=!1}};d.post(route("users.store"),o)},G=R({}),Ee=async o=>{G.processing=!0;let a={onSuccess:c=>{P()},onError:c=>{b.value=c},onFinish:()=>{G.processing=!1}};G.post(route("users.send_verification_link",{user:o==null?void 0:o.id}),a)},H=R({}),Ne=Z(()=>Y.is_admin||Y.is_super_admin),Ce=async o=>{H.processing=!0;let a={onSuccess:c=>{P()},onError:c=>{b.value=c},onFinish:()=>{H.processing=!1}};H.post(route("users.send_welcome_notification",{user:o==null?void 0:o.id}),a)};return(o,a)=>{const c=i("v-card-title"),w=i("v-text-field"),s=i("v-col"),_=i("v-row"),M=i("v-select"),ne=i("v-checkbox"),re=i("v-container"),ue=i("v-card-text"),ie=i("v-spacer"),I=i("v-btn"),D=i("v-hover"),de=i("v-btn-primary"),me=i("v-card-actions"),ce=i("v-card"),_e=i("v-dialog"),pe=i("v-date-picker"),ve=i("v-menu"),fe=i("v-locale-provider"),C=i("v-icon"),x=i("v-tooltip"),Le=i("v-data-table-server");return k(),X(be,null,[e(m(De),{title:"Benutzer"}),e(Ie,{errors:b.value},{header:l(()=>[Ze,n("div",Re,[e(D,null,{default:l(({isHovering:t,props:p})=>[e(I,v(p,{color:t?"accent":"primary",dark:""}),{default:l(()=>[f(" Neuen Benutzer hinzufügen "),e(_e,{modelValue:j.value,"onUpdate:modelValue":a[7]||(a[7]=r=>j.value=r),activator:"parent",width:"80vw"},{default:l(()=>[e(ce,{height:"80vh"},{default:l(()=>[e(c,null,{default:l(()=>[We]),_:1}),e(ue,null,{default:l(()=>[e(re,null,{default:l(()=>[e(_,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(w,{modelValue:m(d).first_name,"onUpdate:modelValue":a[0]||(a[0]=r=>m(d).first_name=r),"error-messages":b.value.first_name,label:"Vorname",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(w,{modelValue:m(d).last_name,"onUpdate:modelValue":a[1]||(a[1]=r=>m(d).last_name=r),"error-messages":b.value.last_name,label:"Nachname",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(_,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(w,{modelValue:m(d).email,"onUpdate:modelValue":a[2]||(a[2]=r=>m(d).email=r),"error-messages":b.value.email,label:"Email",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(w,{modelValue:m(d).phone_number,"onUpdate:modelValue":a[3]||(a[3]=r=>m(d).phone_number=r),"error-messages":b.value.phone_number,label:"Telefonnummer"},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(_,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(M,{modelValue:m(d).role,"onUpdate:modelValue":a[4]||(a[4]=r=>m(d).role=r),items:W.roles,"error-messages":b.value.role,"item-title":"human_name","item-value":"id",label:"Rolle",required:""},null,8,["modelValue","items","error-messages"])]),_:1}),e(s,{cols:"12",md:"4",sm:"6"},{default:l(()=>[e(ne,{modelValue:m(d).two_factor_auth_enabled,"onUpdate:modelValue":a[5]||(a[5]=r=>m(d).two_factor_auth_enabled=r),label:"Zwei-Faktor-Authentifizierung",value:!0},null,8,["modelValue"])]),_:1})]),_:1}),Ne.value?(k(),L(_,{key:0},{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(ne,{modelValue:m(d).send_invite_email,"onUpdate:modelValue":a[6]||(a[6]=r=>m(d).send_invite_email=r),label:"Einladungsmail senden",value:!0},null,8,["modelValue"])]),_:1})]),_:1})):B("",!0)]),_:1})]),_:1}),e(me,null,{default:l(()=>[e(ie),e(D,null,{default:l(({isHovering:r,props:Q})=>[e(I,v({onClick:P},Q,{color:r?"accent":"primary"}),{default:l(()=>[f("Abbrechen")]),_:2},1040,["color"])]),_:2},1024),e(D,null,{default:l(({isHovering:r,props:Q})=>[e(de,v({onClick:$e},Q,{color:r?"accent":"primary"}),{default:l(()=>[f("Speichern")]),_:2},1040,["color"])]),_:2},1024)]),_:2},1024)]),_:2},1024)]),_:2},1032,["modelValue"])]),_:2},1040,["color"])]),_:1})]),e(_e,{modelValue:q.value,"onUpdate:modelValue":a[8]||(a[8]=t=>q.value=t),width:"20vw"},{default:l(()=>[e(ce,{height:"30vh"},{default:l(()=>[e(ue,null,{default:l(()=>[e(re,null,{default:l(()=>[e(_,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[Je]),_:1})]),_:1})]),_:1})]),_:1}),e(me,null,{default:l(()=>[e(ie),e(D,null,{default:l(({isHovering:t,props:p})=>[e(I,v({onClick:P},p,{color:t?"accent":"primary"}),{default:l(()=>[f("Abbrechen")]),_:2},1040,["color"])]),_:1}),e(D,null,{default:l(({isHovering:t,props:p})=>[e(de,v({onClick:Pe},p,{color:t?"accent":"primary"}),{default:l(()=>[f("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),default:l(()=>[n("div",Ke,[n("div",Ge,[n("div",He,[e(_,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(M,{modelValue:y.value,"onUpdate:modelValue":a[9]||(a[9]=t=>y.value=t),items:Ue,"item-title":"title","item-value":"value",label:"Status",multiple:"",disabled:z.value,clearable:""},null,8,["modelValue","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(w,{modelValue:U.value,"onUpdate:modelValue":a[10]||(a[10]=t=>U.value=t),label:"Name",clearable:""},null,8,["modelValue"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(w,{modelValue:F.value,"onUpdate:modelValue":a[11]||(a[11]=t=>F.value=t),label:"Email",clearable:""},null,8,["modelValue"])]),_:1})]),_:1}),e(_,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(M,{modelValue:S.value,"onUpdate:modelValue":a[12]||(a[12]=t=>S.value=t),items:W.roles,"item-title":"human_name","item-value":"name",label:"Rolle",multiple:"",disabled:z.value,clearable:""},null,8,["modelValue","items","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(fe,{locale:"de"},{default:l(()=>[e(ve,{modelValue:J.value,"onUpdate:modelValue":a[15]||(a[15]=t=>J.value=t),"return-value":V.value,"close-on-content-click":!1},{activator:l(({props:t})=>[e(w,v({label:"Erster Login",class:"tw-cursor-pointer","model-value":ae.value,"prepend-icon":"mdi-calendar",readonly:""},t),null,16,["model-value"])]),default:l(()=>[e(pe,{"onUpdate:modelValue":[a[13]||(a[13]=t=>J.value=!1),a[14]||(a[14]=t=>V.value=t)],modelValue:V.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(fe,{locale:"de"},{default:l(()=>[e(ve,{modelValue:K.value,"onUpdate:modelValue":a[18]||(a[18]=t=>K.value=t),"return-value":h.value,"close-on-content-click":!1},{activator:l(({props:t})=>[e(w,v({label:"Letzter Login",class:"tw-cursor-pointer","model-value":te.value,"prepend-icon":"mdi-calendar",readonly:""},t),null,16,["model-value"])]),default:l(()=>[e(pe,{"onUpdate:modelValue":[a[16]||(a[16]=t=>K.value=!1),a[17]||(a[17]=t=>h.value=t)],modelValue:h.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1})]),_:1})])]),e(Le,{"items-per-page":A.value,"onUpdate:itemsPerPage":a[20]||(a[20]=t=>A.value=t),headers:ye,page:O.value,"items-length":ee.value,items:Fe.value,search:le.value,loading:z.value,class:"data-table-container elevation-1","item-value":"name","onUpdate:options":se},{item:l(({item:t})=>[n("tr",null,[n("td",Me,[e(C,{size:"medium",class:xe({active:t.is_online})},{default:l(()=>[f("mdi-circle")]),_:2},1032,["class"])]),n("td",null,T(t.full_name),1),n("td",null,[n("div",Qe,[!t.email_verified_at||t.email_verified_at==="-"?(k(),L(x,{key:0},{activator:l(({props:p})=>[e(C,v(p,{size:"small",class:"tw-me-2"}),{default:l(()=>[f("mdi-alert-circle")]),_:2},1040)]),default:l(()=>[Xe]),_:1})):B("",!0),n("span",null,T(t.email),1)])]),n("td",null,T(t.primary_role_human_name),1),n("td",null,T(!t.last_seen_at||t.last_seen_at==="-"?t.last_seen_at:m(je)(t.last_seen_at,"de-DE")),1),n("td",null,T(!t.first_login_at||t.first_login_at==="-"?t.first_login_at:m(qe)(t.first_login_at,"de-DE")),1),n("td",Ye,[(o.$page.props.auth.user.is_super_admin||o.$page.props.auth.user.is_admin)&&(!t.email_verified_at||t.email_verified_at==="-")?(k(),L(x,{key:0,location:"top"},{activator:l(({props:p})=>[e(C,v(p,{size:"small",class:"tw-me-2",onClick:r=>Ee(t)}),{default:l(()=>[f("mdi-email-sync-outline")]),_:2},1040,["onClick"])]),default:l(()=>[el]),_:2},1024)):B("",!0),(o.$page.props.auth.user.is_super_admin||o.$page.props.auth.user.is_admin)&&o.$page.props.auth.user.id!==t.id?(k(),L(x,{key:1,location:"top"},{activator:l(({props:p})=>[e(C,v(p,{size:"small",class:"tw-me-2",onClick:r=>Ce(t)}),{default:l(()=>[f("mdi-account-check-outline ")]),_:2},1040,["onClick"])]),default:l(()=>[ll]),_:2},1024)):B("",!0),o.$page.props.auth.user.is_super_admin||(o.$page.props.auth.user.is_admin||o.$page.props.auth.user.is_manager)&&!t.is_super_admin&&!t.is_admin||o.$page.props.auth.user.is_admin&&o.$page.props.auth.user.id===t.id?(k(),L(x,{key:2,location:"top"},{activator:l(({props:p})=>[e(m(Te),{href:o.route("users.edit",{id:t.id})},{default:l(()=>[e(C,v(p,{size:"small",class:"tw-me-2"}),{default:l(()=>[f("mdi-pencil")]),_:2},1040)]),_:2},1032,["href"])]),default:l(()=>[al]),_:2},1024)):B("",!0),o.$page.props.auth.user.is_super_admin||o.$page.props.auth.user.is_admin||o.$page.props.auth.user.is_manager&&!t.is_super_admin&&!t.is_admin?(k(),L(x,{key:3,location:"top"},{activator:l(({props:p})=>[e(C,v(p,{size:"small",class:"tw-me-2",onClick:r=>ze(t)}),{default:l(()=>[f("mdi-delete")]),_:2},1040,["onClick"])]),default:l(()=>[tl]),_:2},1024)):B("",!0)])])]),"no-data":l(()=>[n("div",ol,[oe.value?(k(),X("h3",sl,"Die Tabelle ist leer.")):(k(),X(be,{key:1},[nl,e(I,{color:"primary",onClick:a[19]||(a[19]=t=>se({page:1,itemsPerPage:A.value,clearFilters:!0}))},{default:l(()=>[f("Zurücksetzen")]),_:1})],64))])]),_:1},8,["items-per-page","page","items-length","items","search","loading"])])]),_:1},8,["errors"])],64)}}};export{cl as default};
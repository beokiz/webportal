import{d as ul}from"./index-b6d87c97.js";import{K as nl,j as o,k as ye,l as S,A as $e,T as xe,r as m,o as T,f as ee,a as e,u as i,w as l,F as ke,Z as sl,b as r,m as f,d as g,c as M,h as R,t as D,i as ol,g as je,O as rl}from"./app-bea39257.js";import{_ as dl}from"./AuthenticatedLayout-d45af27a.js";import{s as Be}from"./VueTimepikcer.esm-36d442fc.js";import{c as le,f as Ie,d as il,b as cl}from"./common-ea192878.js";import"./ApplicationLogo-fa8c9e42.js";const ml=r("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Schulungen",-1),vl={class:"tw-flex tw-items-center tw-justify-end"},pl=r("span",{class:"tw-text-h5"},"Verwalte Schulungen",-1),_l=r("p",null,"Sind Sie sicher, dass Sie die ausgewählte Schulung löschen möchten?",-1),fl={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},gl={key:0,class:"tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6"},bl={class:"tw-w-full"},Vl=["data-id","data-order"],hl={class:"tw-cursor-pointer"},wl={class:"text-center"},yl=["onClick"],kl=r("span",null,"Schulungstermin bestätigen",-1),Sl=["onClick"],Tl=r("span",null,"Training abschließen und Einrichtungen zulassen",-1),Fl=["onClick"],Dl=r("span",null,"Training abbrechen",-1),Cl=r("span",null,"Schulung bearbeiten",-1),Ul=r("span",null,"Schulung löschen",-1),$l={class:"tw-py-6"},xl={key:0,class:"tw-mb-4"},Pl=r("h3",{class:"tw-mb-4"},"Die Tabelle ist leer. Bitte setzen Sie die Suchfilter zurück.",-1),El=r("span",{class:"tw-text-h5"},"Schulung gegenüber den KiTas bestätigen?",-1),zl={class:"tw-font-bold tw-font-italic"},Ol=r("p",{class:"mb-4"},"Sind Sie sich sicher, dass Sie die Termine gegenüber den folgenden KiTas bestätigen wollen? Im Folgenden gibt es individuelle E-Mail-Vorschläge für jede KiTa.",-1),Al=r("p",null,"Bitte klicken Sie auf den Namen der KiTa, um diesen zu erhalten.",-1),jl=r("p",null,"Möchten Sie die ausgewählte Ausbildung wirklich absolvieren?",-1),Bl=r("p",null,"Sind Sie sicher, dass Sie die ausgewählte Schulung stornieren möchten?",-1),Ml={__name:"Trainings",props:{items:Array,currentPage:Number,perPage:Number,lastPage:Number,total:Number,paging:Boolean,orderBy:String,sort:String,filters:Object,errors:Object,multipliers:Array,kitas:Array,statuses:Array,types:Array},setup(q){const y=q;ul.Inertia.on("success",u=>{let a=u.detail.page.props;u.detail.page.component==="Trainings/Trainings"&&a&&(ce.value=a.currentPage,ae.value=a.perPage,Ne.value=a.orderBy,Le.value=a.sort,Pe.value=a.total,Ke.value=a.lastPage)});const ie=nl().props.auth.user??{},ce=o(y.currentPage),ae=o(y.perPage),Ne=o(y.orderBy),Le=o(y.sort),Pe=o(y.total),Ke=o(y.lastPage),O=o(y.filters.location??null),A=o(y.filters.participant_count??null),j=o(y.filters.max_participant_count??null),B=o(y.filters.type??null),I=o(y.filters.multi_id??null),N=o(y.filters.status??null),L=o(y.filters.with_kitas??null),Ee=o(""),w=o(y.errors||{}),Se=o(!1),Te=o(!1),Fe=o(!1),De=o(!1),v=o(!1),me=o(!1),ve=o(!1),pe=o(!1),_e=o(!1),fe=o(!1),Ze=o(null),ge=o(null),be=o(null),$=o(null),x=o(null),Ve=o(null),he=o(null),K=o(null),Z=o(null),C=o(null),te=o([]),we=o([]),He=[{title:"Erster Schulungstag",key:"first_date",width:"4%",sortable:!0},{title:"Zweiter Schulungstag",key:"second_date",width:"4%",sortable:!0},{title:"Ort",key:"location",width:"19%",sortable:!0},{title:"Teilnehmer ",key:"prepared_participant_count",width:"5%",sortable:!0},{title:"KiTa",key:"kitas_list",width:"17%",sortable:!1},{title:"Typ",key:"type",width:"7%",sortable:!0},{title:"Status",key:"status",width:"5%",sortable:!0},{title:"Multiplikator",key:"multi_id",width:"7%",sortable:!0},{title:"Geändert am",key:"updated_at",width:"7%",sortable:!0},{title:"Aktion",key:"actions",width:"10%",sortable:!1,align:"center"}],Me=ye(()=>y.items.map(u=>{const a={...u};for(const V in a)(a[V]===null||a[V]===void 0)&&(a[V]="-");return a})),ze=ye(()=>$.value===null&&x.value===null&&O.value===null&&A.value===null&&j.value===null&&B.value===null&&I.value===null&&N.value===null&&L.value===null),qe=ye(()=>$.value!==null||x.value!==null||O.value!==null||A.value!==null||j.value!==null||B.value!==null||I.value!==null||N.value!==null||L.value!==null),Ge=ye(()=>{var u,a,V,p,c,h,k;return[{label:"Erster Schulungstag",value:`${le((u=C.value)==null?void 0:u.first_date)} ${(a=C.value)==null?void 0:a.first_date_start_and_end_time}`},{label:"Zweiter Schulungstag",value:`${le((V=C.value)==null?void 0:V.second_date)} ${(p=C.value)==null?void 0:p.second_date_start_and_end_time}`},{label:"Ort",value:(c=C.value)==null?void 0:c.formatted_location},{label:"Typ",value:(h=C.value)==null?void 0:h.type},{label:"Teilnehmerzahl",value:(k=C.value)==null?void 0:k.participant_count}]});S(me,u=>{u||P()}),S($,u=>{ge.value=u?le(u):null,U()}),S(x,u=>{be.value=u?le(u):null,U()}),S(ge,u=>{U()}),S(be,u=>{U()}),S(O,$e.debounce(u=>{U()},500)),S(A,$e.debounce(u=>{U()},500)),S(j,$e.debounce(u=>{U()},500)),S(B,u=>{U()}),S(I,u=>{U()}),S(N,u=>{U()}),S(K,u=>{Ve.value=le(u)}),S(Z,u=>{he.value=le(u)}),S(L,u=>{U()});const U=()=>{v.value=!0,Ee.value=String(Date.now())},Re=()=>{$.value=null,ge.value=null},Je=()=>{x.value=null,be.value=null},Oe=async({page:u,itemsPerPage:a,sortBy:V,clearFilters:p})=>{if(p&&($.value=null,x.value=null,O.value=null,A.value=null,j.value=null,B.value=null,I.value=null,N.value=null,L.value=null),u===ce.value&&p||ze||qe){v.value=!0;let c={page:u,per_page:a};V&&V.length>0?(c.order_by=V[0].key,c.sort=V[0].order):(c.order_by=null,c.sort=null),$.value&&(c.first_date=$.value.toLocaleString()),x.value&&(c.second_date=x.value.toLocaleString()),O.value&&(c.location=O.value),A.value&&(c.participant_count=A.value),j.value&&(c.max_participant_count=j.value),B.value&&(c.type=B.value),I.value&&(c.with_multipliers=I.value),N.value&&(c.status=N.value),L.value&&(c.with_kitas=L.value),await rl.get(route(route().current()),c,{preserveScroll:!0,preserveState:!0,onCancelToken:h=>{},onCancel:()=>{},onBefore:h=>{v.value=!0},onStart:h=>{},onProgress:h=>{},onSuccess:h=>{ce.value=c.page,ae.value=c.per_page},onError:h=>{console.log(h)},onFinish:h=>{v.value=!1}})}},Qe=u=>{Ze.value=u.name,ue.id=u.id,fe.value=!0},ue=xe({id:null}),We=async()=>{ue.processing=!0;let u={onSuccess:a=>{P()},onError:a=>{P(),w.value=a},onFinish:()=>{ue.processing=!1}};ue.delete(route("trainings.destroy",{id:ue.id}),u)},P=()=>{me.value=!1,ve.value=!1,pe.value=!1,_e.value=!1,fe.value=!1,d.reset(),d.clearErrors(),E.reset(),E.clearErrors(),Ve.value=null,he.value=null,K.value=null,Z.value=null,C.value=null,te.value=null,w.value={}},Xe=()=>{d.reset(),d.clearErrors(),Ve.value=null,he.value=null,K.value=null,Z.value=null},d=xe({multi_id:ie!=null&&ie.is_user_multiplier?ie.id:null,first_date:null,first_date_start_and_end_time:"12:00",second_date:null,second_date_start_and_end_time:"12:00",location:null,max_participant_count:null,type:null,street:null,house_number:null,zip_code:null,city:null,district:null,notes:null}),Ye=async()=>{d.processing=!0,d.first_date=K.value?new Date(new Date(K.value).setHours(12,0,0,0)).toISOString():null,d.second_date=Z.value?new Date(new Date(Z.value).setHours(12,0,0,0)).toISOString():null,d.post(route("trainings.store"),{onSuccess:u=>{P()},onError:u=>{w.value=u},onFinish:()=>{d.processing=!1}})},Ce=(u,a)=>{switch(a){case"confirmed":ve.value=!0;break;case"completed":pe.value=!0;break;case"cancelled":_e.value=!0;break}E.id=u==null?void 0:u.id,E.status=a,C.value=u,te.value=u==null?void 0:u.kitas},E=xe({id:null,status:null}),Ue=async u=>{E.processing=!0,E.status=u,E.put(route("trainings.update",{training:E.id}),{onSuccess:a=>{var V,p,c,h;if(u==="confirmed"){let k=[];if(we.value.map(s=>{s==null||s.users_emails.map(H=>{k.push(H)})}),k=k.filter((s,H,b)=>b.indexOf(s)===H),k&&k.length){const s=document.createElement("a"),H=(p=(V=C.value)==null?void 0:V.email_messages[u])==null?void 0:p.subject,b=(h=(c=C.value)==null?void 0:c.email_messages[u])==null?void 0:h.body;s.href=`mailto:?bcc=${k.join(",")}&subject=${H}&body=${b}`,document.body.appendChild(s),s.click(),document.body.removeChild(s)}we.value=[]}P()},onError:a=>{w.value=a},onFinish:()=>{E.processing=!1}})};return(u,a)=>{const V=m("v-card-title"),p=m("v-text-field"),c=m("v-date-picker"),h=m("v-menu"),k=m("v-locale-provider"),s=m("v-col"),H=m("v-label"),b=m("v-row"),J=m("v-select"),Ae=m("v-textarea"),Q=m("v-container"),ne=m("v-card-text"),W=m("v-btn-primary"),F=m("v-hover"),se=m("v-spacer"),G=m("v-btn"),oe=m("v-card-actions"),re=m("v-card"),de=m("v-dialog"),X=m("v-icon"),Y=m("v-tooltip"),el=m("v-data-table-server"),ll=m("v-checkbox"),al=m("v-list-item"),tl=m("v-list");return T(),ee(ke,null,[e(i(sl),{title:"Schulungen"}),e(dl,{errors:w.value},{header:l(()=>[ml,r("div",vl,[e(F,null,{default:l(({isHovering:t,props:_})=>[e(G,f(_,{color:t?"accent":"primary",dark:""}),{default:l(()=>[g(" Anlegen "),e(de,{modelValue:me.value,"onUpdate:modelValue":a[18]||(a[18]=n=>me.value=n),activator:"parent",width:"80vw"},{default:l(()=>[e(re,{height:"80vh"},{default:l(()=>[e(V,null,{default:l(()=>[pl]),_:1}),e(ne,null,{default:l(()=>[e(Q,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(h,{modelValue:Fe.value,"onUpdate:modelValue":a[2]||(a[2]=n=>Fe.value=n),"return-value":K.value,"close-on-content-click":!1},{activator:l(({props:n})=>[e(p,f({label:"Erster Schulungstag*",class:"tw-cursor-pointer","model-value":Ve.value,"error-messages":w.value.first_date,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},n,{disabled:v.value}),null,16,["model-value","error-messages","disabled"])]),default:l(()=>[e(c,{"onUpdate:modelValue":[a[0]||(a[0]=n=>Fe.value=!1),a[1]||(a[1]=n=>K.value=n)],modelValue:K.value},null,8,["modelValue"])]),_:2},1032,["modelValue","return-value"])]),_:2},1024)]),_:2},1024),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(H,{class:"tw-mt-6 tw-mr-2"},{default:l(()=>[g("Zeitraum erster Schulungstag*")]),_:1}),e(i(Be),{modelValue:i(d).first_date_start_and_end_time,"onUpdate:modelValue":a[3]||(a[3]=n=>i(d).first_date_start_and_end_time=n),hideClearButton:!0,format:"HH:mm",hourLabel:"Std.",minuteLabel:"Protokoll",disabled:v.value},null,8,["modelValue","hourLabel","disabled"])]),_:1})]),_:2},1024),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(h,{modelValue:De.value,"onUpdate:modelValue":a[6]||(a[6]=n=>De.value=n),"return-value":Z.value,"close-on-content-click":!1},{activator:l(({props:n})=>[e(p,f({label:"Zweiter Schulungstag*",class:"tw-cursor-pointer","model-value":he.value,"error-messages":w.value.second_date,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},n,{disabled:v.value}),null,16,["model-value","error-messages","disabled"])]),default:l(()=>[e(c,{"onUpdate:modelValue":[a[4]||(a[4]=n=>De.value=!1),a[5]||(a[5]=n=>Z.value=n)],modelValue:Z.value},null,8,["modelValue"])]),_:2},1032,["modelValue","return-value"])]),_:2},1024)]),_:2},1024),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(H,{class:"tw-mt-6 tw-mr-2"},{default:l(()=>[g("Zeitraum zweiter Schulungstag*")]),_:1}),e(i(Be),{modelValue:i(d).second_date_start_and_end_time,"onUpdate:modelValue":a[7]||(a[7]=n=>i(d).second_date_start_and_end_time=n),hideClearButton:!0,format:"HH:mm",hourLabel:"Std.",minuteLabel:"Protokoll",disabled:v.value},null,8,["modelValue","hourLabel","disabled"])]),_:1})]),_:2},1024),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{type:"number",modelValue:i(d).max_participant_count,"onUpdate:modelValue":a[8]||(a[8]=n=>i(d).max_participant_count=n),"error-messages":w.value.max_participant_count,label:"Max. Teilnehmerzahl*",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(J,{modelValue:i(d).type,"onUpdate:modelValue":a[9]||(a[9]=n=>i(d).type=n),"error-messages":w.value.type,items:q.types,"item-title":"title","item-value":"value",label:"Typ*",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","items","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(J,{modelValue:i(d).multi_id,"onUpdate:modelValue":a[10]||(a[10]=n=>i(d).multi_id=n),"error-messages":w.value.multi_id,items:q.multipliers,"item-title":"full_name","item-value":"id",label:"Multiplikator*",disabled:v.value||u.$page.props.auth.user.is_user_multiplier,clearable:""},null,8,["modelValue","error-messages","items","disabled"])]),_:2},1024)]),_:2},1024),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{modelValue:i(d).street,"onUpdate:modelValue":a[11]||(a[11]=n=>i(d).street=n),"error-messages":w.value.street,label:"Straße*",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{modelValue:i(d).house_number,"onUpdate:modelValue":a[12]||(a[12]=n=>i(d).house_number=n),"error-messages":w.value.house_number,label:"Hausnummer*",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{modelValue:i(d).zip_code,"onUpdate:modelValue":a[13]||(a[13]=n=>i(d).zip_code=n),"error-messages":w.value.zip_code,label:"Postleitzahl*",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(p,{modelValue:i(d).district,"onUpdate:modelValue":a[14]||(a[14]=n=>i(d).district=n),"error-messages":w.value.district,label:"Bezirk",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(p,{modelValue:i(d).city,"onUpdate:modelValue":a[15]||(a[15]=n=>i(d).city=n),"error-messages":w.value.city,label:"Stadt*",disabled:v.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(Ae,{modelValue:i(d).location,"onUpdate:modelValue":a[16]||(a[16]=n=>i(d).location=n),"error-messages":w.value.location,label:"Ort*",disabled:v.value,required:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(Ae,{modelValue:i(d).notes,"onUpdate:modelValue":a[17]||(a[17]=n=>i(d).notes=n),"error-messages":w.value.notes,label:"Notizen",disabled:v.value,required:""},null,8,["modelValue","error-messages","disabled"])]),_:1})]),_:1})]),_:2},1024)]),_:2},1024),e(oe,null,{default:l(()=>[e(F,null,{default:l(({isHovering:n,props:z})=>[e(W,f({onClick:Xe},z,{color:n?"accent":"primary"}),{default:l(()=>[g("Zurücksetzen")]),_:2},1040,["color"])]),_:2},1024),e(se),e(F,null,{default:l(({isHovering:n,props:z})=>[e(G,f({onClick:P},z,{color:n?"accent":"primary"}),{default:l(()=>[g("Stornieren")]),_:2},1040,["color"])]),_:2},1024),e(F,null,{default:l(({isHovering:n,props:z})=>[e(W,f({onClick:Ye},z,{color:n?"accent":"primary"}),{default:l(()=>[g("Speichern")]),_:2},1040,["color"])]),_:2},1024)]),_:2},1024)]),_:2},1024)]),_:2},1032,["modelValue"])]),_:2},1040,["color"])]),_:1})]),e(de,{modelValue:fe.value,"onUpdate:modelValue":a[19]||(a[19]=t=>fe.value=t),width:"20vw"},{default:l(()=>[e(re,{height:"30vh"},{default:l(()=>[e(ne,null,{default:l(()=>[e(Q,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[_l]),_:1})]),_:1})]),_:1})]),_:1}),e(oe,null,{default:l(()=>[e(se),e(F,null,{default:l(({isHovering:t,props:_})=>[e(G,f({onClick:P},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Abbrechen")]),_:2},1040,["color"])]),_:1}),e(F,null,{default:l(({isHovering:t,props:_})=>[e(W,f({onClick:We},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),default:l(()=>[r("div",fl,[u.$page.props.auth.user.is_super_admin||u.$page.props.auth.user.is_admin?(T(),ee("div",gl,[r("div",bl,[e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(h,{modelValue:Se.value,"onUpdate:modelValue":a[22]||(a[22]=t=>Se.value=t),"return-value":$.value,"close-on-content-click":!1},{activator:l(({props:t})=>[e(p,f({label:"Erster Schulungstag",class:"tw-cursor-pointer","model-value":ge.value,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},t,{"onClick:clear":Re}),null,16,["model-value"])]),default:l(()=>[e(c,{"onUpdate:modelValue":[a[20]||(a[20]=t=>Se.value=!1),a[21]||(a[21]=t=>$.value=t)],modelValue:$.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(h,{modelValue:Te.value,"onUpdate:modelValue":a[25]||(a[25]=t=>Te.value=t),"return-value":x.value,"close-on-content-click":!1},{activator:l(({props:t})=>[e(p,f({label:"Zweiter Schulungstag",class:"tw-cursor-pointer","model-value":be.value,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},t,{"onClick:clear":Je}),null,16,["model-value"])]),default:l(()=>[e(c,{modelValue:x.value,"onUpdate:modelValue":[a[23]||(a[23]=t=>x.value=t),a[24]||(a[24]=t=>Te.value=!1)]},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{modelValue:O.value,"onUpdate:modelValue":a[26]||(a[26]=t=>O.value=t),label:"Ort",clearable:""},null,8,["modelValue"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{type:"number",modelValue:A.value,"onUpdate:modelValue":a[27]||(a[27]=t=>A.value=t),label:"Teilnehmerzahl",clearable:""},null,8,["modelValue"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(p,{type:"number",modelValue:j.value,"onUpdate:modelValue":a[28]||(a[28]=t=>j.value=t),label:"Max. Teilnehmerzahl",clearable:""},null,8,["modelValue"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(J,{modelValue:B.value,"onUpdate:modelValue":a[29]||(a[29]=t=>B.value=t),items:q.types,"item-title":"title","item-value":"value",label:"Typ",multiple:"",disabled:v.value,clearable:""},null,8,["modelValue","items","disabled"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(J,{modelValue:N.value,"onUpdate:modelValue":a[30]||(a[30]=t=>N.value=t),items:q.statuses,"item-title":"title","item-value":"value",label:"Status",multiple:"",disabled:v.value,clearable:""},null,8,["modelValue","items","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[u.$page.props.auth.user.is_super_admin||u.$page.props.auth.user.is_admin?(T(),M(J,{key:0,modelValue:I.value,"onUpdate:modelValue":a[31]||(a[31]=t=>I.value=t),items:q.multipliers,"item-title":"full_name","item-value":"id",label:"Multiplikator",multiple:"",disabled:v.value,clearable:""},null,8,["modelValue","items","disabled"])):R("",!0)]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(J,{modelValue:L.value,"onUpdate:modelValue":a[32]||(a[32]=t=>L.value=t),items:q.kitas,"item-title":"name","item-value":"id",label:"KiTa",multiple:"",disabled:v.value,clearable:""},null,8,["modelValue","items","disabled"])]),_:1})]),_:1})])])):R("",!0),e(el,{"items-per-page":ae.value,"onUpdate:itemsPerPage":a[34]||(a[34]=t=>ae.value=t),"items-per-page-options":[{value:10,title:"10"},{value:25,title:"25"},{value:50,title:"50"},{value:100,title:"100"},{value:-1,title:"$vuetify.dataFooter.itemsPerPageAll"}],"items-per-page-text":"Objekte pro Seite:",headers:He,page:ce.value,"items-length":Pe.value,items:Me.value,search:Ee.value,loading:v.value,class:"data-table-container elevation-1","item-value":"name","onUpdate:options":Oe},{item:l(({item:t})=>{var _;return[r("tr",{"data-id":t.id,"data-order":t.order},[r("td",null,D(!t.first_date||t.first_date==="-"?t.first_date:i(Ie)(t.first_date,"de-DE")),1),r("td",null,D(!t.second_date||t.second_date==="-"?t.second_date:i(Ie)(t.second_date,"de-DE")),1),r("td",null,D(t.formatted_location),1),r("td",null,D(t.prepared_participant_count),1),r("td",null,D(t!=null&&t.kitas_list&&(t!=null&&t.kitas_list.length)?t==null?void 0:t.kitas_list.join(","):"-"),1),r("td",null,D(t.formatted_type),1),r("td",null,[e(Y,{location:"top"},{activator:l(({props:n})=>[r("span",hl,[e(X,f(n,{size:"small",class:"tw-me-2"}),{default:l(()=>[g(D(i(il)(t.status)),1)]),_:2},1040)])]),default:l(()=>[r("span",null,D(t.formatted_status),1)]),_:2},1024)]),r("td",null,D(t!=null&&t.multiplier?(_=t==null?void 0:t.multiplier)==null?void 0:_.full_name:"-"),1),r("td",null,D(!t.updated_at||t.updated_at==="-"?t.updated_at:i(cl)(t.updated_at,"de-DE")),1),r("td",wl,[t.status==="planned"?(T(),M(Y,{key:0,location:"top"},{activator:l(({props:n})=>[r("span",{class:"tw-cursor-pointer",onClick:z=>Ce(t,"confirmed")},[e(X,f(n,{size:"small",class:"tw-me-2"}),{default:l(()=>[g("mdi-progress-check")]),_:2},1040)],8,yl)]),default:l(()=>[kl]),_:2},1024)):R("",!0),t.status==="confirmed"?(T(),M(Y,{key:1,location:"top"},{activator:l(({props:n})=>[r("span",{class:"tw-cursor-pointer",onClick:z=>Ce(t,"completed")},[e(X,f(n,{size:"small",class:"tw-me-2"}),{default:l(()=>[g("mdi-check")]),_:2},1040)],8,Sl)]),default:l(()=>[Tl]),_:2},1024)):R("",!0),t.status!=="completed"&&t.status!=="cancelled"&&(u.$page.props.auth.user.is_super_admin||u.$page.props.auth.user.is_admin)?(T(),M(Y,{key:2,location:"top"},{activator:l(({props:n})=>[r("span",{class:"tw-cursor-pointer",onClick:z=>Ce(t,"cancelled")},[e(X,f(n,{size:"small",class:"tw-me-2"}),{default:l(()=>[g("mdi-close")]),_:2},1040)],8,Fl)]),default:l(()=>[Dl]),_:2},1024)):R("",!0),e(Y,{location:"top"},{activator:l(({props:n})=>[e(i(ol),{href:u.route("trainings.show",{id:t.id})},{default:l(()=>[e(X,f(n,{size:"small",class:"tw-me-2"}),{default:l(()=>[g("mdi-pencil")]),_:2},1040)]),_:2},1032,["href"])]),default:l(()=>[Cl]),_:2},1024),u.$page.props.auth.user.is_super_admin||u.$page.props.auth.user.is_admin?(T(),M(Y,{key:3,location:"top"},{activator:l(({props:n})=>[e(X,f(n,{size:"small",class:"tw-me-2",onClick:z=>Qe(t)}),{default:l(()=>[g("mdi-delete")]),_:2},1040,["onClick"])]),default:l(()=>[Ul]),_:2},1024)):R("",!0)])],8,Vl)]}),"no-data":l(()=>[r("div",$l,[ze.value?(T(),ee("h3",xl,"Die Tabelle ist leer.")):(T(),ee(ke,{key:1},[Pl,e(G,{color:"primary",onClick:a[33]||(a[33]=t=>Oe({page:1,itemsPerPage:ae.value,clearFilters:!0}))},{default:l(()=>[g("Reset")]),_:1})],64))])]),_:1},8,["items-per-page","items-per-page-options","page","items-length","items","search","loading"]),e(Q,null,{default:l(()=>[e(b,null,{default:l(()=>[e(de,{modelValue:ve.value,"onUpdate:modelValue":a[37]||(a[37]=t=>ve.value=t),width:"80vw"},{default:l(()=>[e(re,{height:"80vw"},{default:l(()=>[e(V,null,{default:l(()=>[El]),_:1}),e(ne,null,{default:l(()=>[e(Q,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[(T(!0),ee(ke,null,je(Ge.value,t=>(T(),M(b,null,{default:l(()=>[e(s,{cols:"12",sm:"2"},{default:l(()=>[r("span",zl,D(t==null?void 0:t.label)+":",1)]),_:2},1024),e(s,{cols:"12",sm:"10"},{default:l(()=>[r("span",null,D(t==null?void 0:t.value),1)]),_:2},1024)]),_:2},1024))),256))]),_:1})]),_:1}),te.value&&te.value.length?(T(),M(b,{key:0},{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[Ol,Al]),_:1}),e(s,{cols:"12",class:"tw--mt-6"},{default:l(()=>[e(tl,null,{default:l(()=>[(T(!0),ee(ke,null,je(te.value,t=>(T(),M(al,{class:"hide-details",key:t.id},{default:l(()=>[e(ll,{class:"!tw-font-bold !tw-font-italic !tw-text-black",label:t.name,value:t,modelValue:we.value,"onUpdate:modelValue":a[35]||(a[35]=_=>we.value=_)},null,8,["label","value","modelValue"])]),_:2},1024))),128))]),_:1})]),_:1})]),_:1})):R("",!0)]),_:1})]),_:1}),e(oe,null,{default:l(()=>[e(se),e(F,null,{default:l(({isHovering:t,props:_})=>[e(G,f({onClick:P},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Abbrechen")]),_:2},1040,["color"])]),_:1}),e(F,null,{default:l(({isHovering:t,props:_})=>[e(W,f({onClick:a[36]||(a[36]=n=>Ue("confirmed"))},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Einreichen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"]),e(de,{modelValue:pe.value,"onUpdate:modelValue":a[39]||(a[39]=t=>pe.value=t),width:"20vw"},{default:l(()=>[e(re,{height:"30vh"},{default:l(()=>[e(ne,null,{default:l(()=>[e(Q,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[jl]),_:1})]),_:1})]),_:1})]),_:1}),e(oe,null,{default:l(()=>[e(se),e(F,null,{default:l(({isHovering:t,props:_})=>[e(G,f({onClick:P},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Abbrechen")]),_:2},1040,["color"])]),_:1}),e(F,null,{default:l(({isHovering:t,props:_})=>[e(W,f({onClick:a[38]||(a[38]=n=>Ue("completed"))},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Einreichen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"]),e(de,{modelValue:_e.value,"onUpdate:modelValue":a[41]||(a[41]=t=>_e.value=t),width:"20vw"},{default:l(()=>[e(re,{height:"30vh"},{default:l(()=>[e(ne,null,{default:l(()=>[e(Q,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[Bl]),_:1})]),_:1})]),_:1})]),_:1}),e(oe,null,{default:l(()=>[e(se),e(F,null,{default:l(({isHovering:t,props:_})=>[e(G,f({onClick:P},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Abbrechen")]),_:2},1040,["color"])]),_:1}),e(F,null,{default:l(({isHovering:t,props:_})=>[e(W,f({onClick:a[40]||(a[40]=n=>Ue("cancelled"))},_,{color:t?"accent":"primary"}),{default:l(()=>[g("Einreichen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})]),_:1})])]),_:1},8,["errors"])],64)}}};export{Ml as default};
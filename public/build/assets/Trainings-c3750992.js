import{d as sl}from"./index-974f2ef9.js";import{K as ol,k as o,h as ye,m as S,A as xe,T as Pe,r as i,n as rl,f as W,a as e,u as m,w as l,F as Ve,o as y,Z as dl,b as r,c as D,p,d as _,g as L,t as F,q as il,i as cl,x as Oe,O as vl}from"./app-40497319.js";import{_ as ml}from"./AuthenticatedLayout-a44e4e10.js";import{s as Ne}from"./VueTimepikcer.esm-ecc64900.js";import{b as X,c as Be,f as je}from"./common-1f9d3448.js";import"./ApplicationLogo-2dee5152.js";const pl=r("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Schulungen",-1),_l={class:"tw-flex tw-items-center tw-justify-end"},fl=r("span",{class:"tw-text-h5"},"Verwalte Schulungen",-1),gl=r("p",null,"Sind Sie sicher, dass Sie die ausgewählte Schulung löschen möchten?",-1),bl={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},hl={key:0,class:"tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6"},wl={class:"tw-w-full"},yl=["data-id","data-order"],Vl={class:"text-center"},kl=["onClick"],Sl=r("span",null,"Schulungstermin bestätigen",-1),Fl=["onClick"],Tl=r("span",null,"Training abschließen und Einrichtungen zulassen",-1),Cl=["onClick"],$l=r("span",null,"Training abbrechen",-1),Dl=r("span",null,"Schulung bearbeiten",-1),Ul=r("span",null,"Schulung löschen",-1),xl={class:"tw-py-6"},Pl={key:0,class:"tw-mb-4"},El=r("h3",{class:"tw-mb-4"},"Die Tabelle ist leer. Bitte setzen Sie die Suchfilter zurück.",-1),zl=r("span",{class:"tw-text-h5"},"Schulung gegenüber den Kitas bestätigen?",-1),Al={class:"tw-font-bold tw-font-italic"},Ll=r("p",{class:"mb-4"},"Sind Sie sich sicher, dass Sie die Termine gegenüber den folgenden Kitas bestätigen wollen? Im Folgenden gibt es individuelle E-Mail-Vorschläge für jede Kita.",-1),Ol=r("p",null,"Bitte klicken Sie auf den Namen der Kita, um diesen zu erhalten.",-1),Nl=r("p",null,"Möchten Sie die ausgewählte Ausbildung wirklich absolvieren?",-1),Bl=r("p",null,"Sind Sie sicher, dass Sie die ausgewählte Schulung stornieren möchten?",-1),Gl={__name:"Trainings",props:{items:Array,currentPage:Number,perPage:Number,lastPage:Number,total:Number,paging:Boolean,orderBy:String,sort:String,filters:Object,errors:Object,multipliers:Array,statuses:Array,types:Array},setup(H){const V=H;sl.Inertia.on("success",n=>{let t=n.detail.page.props;n.detail.page.component==="Trainings/Trainings"&&t&&(de.value=t.currentPage,Y.value=t.perPage,Ie.value=t.orderBy,Ze.value=t.sort,ke.value=t.total,Ke.value=t.lastPage)}),ol().props.auth.user;const de=o(V.currentPage),Y=o(V.perPage),Ie=o(V.orderBy),Ze=o(V.sort),ke=o(V.total),Ke=o(V.lastPage),O=o(V.filters.location??null),N=o(V.filters.participant_count??null),B=o(V.filters.max_participant_count??null),j=o(V.filters.type??null),I=o(V.filters.multi_id??null),Z=o(V.filters.status??null),Ee=o(""),T=o(V.errors||{}),Se=o(!1),Fe=o(!1),Te=o(!1),Ce=o(!1),h=o(!1),ie=o(!1),ce=o(!1),ve=o(!1),me=o(!1),pe=o(!1),Me=o(null),_e=o(null),fe=o(null),U=o(null),x=o(null),ge=o(null),be=o(null),K=o(null),M=o(null),C=o(null),he=o([]),we=o([]),qe=[{title:"Erster Schulungstag",key:"first_date",width:"4%",sortable:!0},{title:"Zweiter Schulungstag",key:"second_date",width:"4%",sortable:!0},{title:"Ort",key:"location",width:"10%",sortable:!0},{title:"Teilnehmer ",key:"prepared_participant_count",width:"5%",sortable:!0},{title:"Typ",key:"type",width:"7%",sortable:!0},{title:"Status",key:"status",width:"10%",sortable:!0},{title:"Multiplikator",key:"multi_id",width:"10%",sortable:!0},{title:"Notizen",key:"notes",width:"20%",sortable:!0},{title:"Erstellt am",key:"created_at",width:"10%",sortable:!0},{title:"Geändert am",key:"updated_at",width:"10%",sortable:!0},{title:"Aktion",key:"actions",width:"10%",sortable:!1,align:"center"}],Ge=ye(()=>V.items.map(n=>{const t={...n};for(const f in t)(t[f]===null||t[f]===void 0)&&(t[f]="-");return t})),ze=ye(()=>U.value===null&&x.value===null&&O.value===null&&N.value===null&&B.value===null&&j.value===null&&I.value===null&&Z.value===null),Re=ye(()=>U.value!==null||x.value!==null||O.value!==null||N.value!==null||B.value!==null||j.value!==null||I.value!==null||Z.value!==null),He=ye(()=>{var n,t,f,w,d,g,k;return[{label:"Erster Schukungstag",value:`${X((n=C.value)==null?void 0:n.first_date)} ${(t=C.value)==null?void 0:t.first_date_start_and_end_time}`},{label:"Zweiter Schulungstag",value:`${X((f=C.value)==null?void 0:f.second_date)} ${(w=C.value)==null?void 0:w.second_date_start_and_end_time}`},{label:"Ort",value:(d=C.value)==null?void 0:d.location},{label:"Typ",value:(g=C.value)==null?void 0:g.type},{label:"Teinhemheranzahl",value:(k=C.value)==null?void 0:k.participant_count}]});S(ie,n=>{n||E()}),S(U,n=>{_e.value=n?X(n):null,P()}),S(x,n=>{fe.value=n?X(n):null,P()}),S(_e,n=>{P()}),S(fe,n=>{P()}),S(O,xe.debounce(n=>{P()},500)),S(N,xe.debounce(n=>{P()},500)),S(B,xe.debounce(n=>{P()},500)),S(j,n=>{P()}),S(I,n=>{P()}),S(Z,n=>{P()}),S(K,n=>{ge.value=X(n)}),S(M,n=>{be.value=X(n)});const P=()=>{h.value=!0,Ee.value=String(Date.now())},Je=()=>{U.value=null,_e.value=null},Qe=()=>{x.value=null,fe.value=null},Ae=async({page:n,itemsPerPage:t,sortBy:f,clearFilters:w})=>{if(w&&(U.value=null,x.value=null,O.value=null,N.value=null,B.value=null,j.value=null,I.value=null,Z.value=null),n===de.value&&w||ze||Re){h.value=!0;let d={page:n,per_page:t};f&&f.length>0?(d.order_by=f[0].key,d.sort=f[0].order):(d.order_by=null,d.sort=null),U.value&&(d.first_date=U.value.toLocaleString()),x.value&&(d.second_date=x.value.toLocaleString()),O.value&&(d.location=O.value),N.value&&(d.participant_count=N.value),B.value&&(d.max_participant_count=B.value),j.value&&(d.type=j.value),I.value&&(d.with_multipliers=I.value),Z.value&&(d.status=Z.value),await vl.get(route(route().current()),d,{preserveScroll:!0,preserveState:!0,onCancelToken:g=>{},onCancel:()=>{},onBefore:g=>{h.value=!0},onStart:g=>{},onProgress:g=>{},onSuccess:g=>{de.value=d.page,Y.value=d.per_page},onError:g=>{console.log(g)},onFinish:g=>{h.value=!1}})}},We=n=>{Me.value=n.name,ee.id=n.id,pe.value=!0},ee=Pe({id:null}),Xe=async()=>{ee.processing=!0;let n={onSuccess:t=>{E()},onError:t=>{E(),T.value=t},onFinish:()=>{ee.processing=!1}};ee.delete(route("trainings.destroy",{id:ee.id}),n)},E=()=>{ie.value=!1,pe.value=!1,ce.value=!1,ve.value=!1,me.value=!1,c.reset(),c.clearErrors(),z.reset(),z.clearErrors(),ge.value=null,be.value=null,K.value=null,M.value=null,C.value=null,he.value=null,T.value={}},Ye=()=>{c.reset(),c.clearErrors(),ge.value=null,be.value=null,K.value=null,M.value=null},c=Pe({multi_id:null,first_date:null,first_date_start_and_end_time:"12:00",second_date:null,second_date_start_and_end_time:"12:00",location:null,max_participant_count:null,type:null,notes:null}),el=async()=>{c.processing=!0,c.first_date=K.value?new Date(K.value).toLocaleString():null,c.second_date=M.value?new Date(M.value).toLocaleString():null,c.post(route("trainings.store"),{onSuccess:n=>{E()},onError:n=>{T.value=n},onFinish:()=>{c.processing=!1}})},$e=(n,t)=>{switch(t){case"confirmed":ce.value=!0;break;case"completed":ve.value=!0;break;case"cancelled":me.value=!0;break}z.id=n==null?void 0:n.id,z.status=t,C.value=n,he.value=n==null?void 0:n.kitas},z=Pe({id:null,status:null}),De=async n=>{z.processing=!0,z.status=n,z.put(route("trainings.update",{training:z.id}),{onSuccess:t=>{var f,w,d,g;if(n==="confirmed"){let k=[];if(we.value.map(s=>{s==null||s.users_emails.map(q=>{k.push(q)})}),k=k.filter((s,q,b)=>b.indexOf(s)===q),k&&k.length){const s=document.createElement("a");console.log(C.value);const q=(w=(f=C.value)==null?void 0:f.email_messages[n])==null?void 0:w.subject,b=(g=(d=C.value)==null?void 0:d.email_messages[n])==null?void 0:g.body;s.href=`mailto:?bcc=${k.join(",")}&subject=${q}&body=${b}`,document.body.appendChild(s),s.click(),document.body.removeChild(s)}we.value=[]}E()},onError:t=>{T.value=t},onFinish:()=>{z.processing=!1}})};return(n,t)=>{const f=i("v-card-title"),w=i("v-text-field"),d=i("v-date-picker"),g=i("v-menu"),k=i("v-locale-provider"),s=i("v-col"),q=i("v-label"),b=i("v-row"),le=i("v-select"),Le=i("v-textarea"),J=i("v-container"),te=i("v-card-text"),Q=i("v-btn-primary"),$=i("v-hover"),ae=i("v-spacer"),R=i("v-btn"),ne=i("v-card-actions"),ue=i("v-card"),se=i("v-dialog"),oe=i("v-icon"),re=i("v-tooltip"),ll=i("v-data-table-server"),tl=i("v-checkbox"),al=i("v-list-item"),nl=i("v-list"),ul=rl("sortable-data-table");return y(),W(Ve,null,[e(m(dl),{title:"Schulungen"}),e(ml,{errors:T.value},{header:l(()=>[pl,r("div",_l,[n.$page.props.auth.user.is_super_admin||n.$page.props.auth.user.is_admin?(y(),D($,{key:0},{default:l(({isHovering:a,props:v})=>[e(R,p(v,{color:a?"accent":"primary",dark:""}),{default:l(()=>[_(" Anlegen "),e(se,{modelValue:ie.value,"onUpdate:modelValue":t[13]||(t[13]=u=>ie.value=u),activator:"parent",width:"80vw"},{default:l(()=>[e(ue,{height:"80vh"},{default:l(()=>[e(f,null,{default:l(()=>[fl]),_:1}),e(te,null,{default:l(()=>[e(J,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(g,{modelValue:Te.value,"onUpdate:modelValue":t[2]||(t[2]=u=>Te.value=u),"return-value":K.value,"close-on-content-click":!1},{activator:l(({props:u})=>[e(w,p({label:"Erster Schulungstag*",class:"tw-cursor-pointer","model-value":ge.value,"error-messages":T.value.first_date,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},u,{disabled:h.value}),null,16,["model-value","error-messages","disabled"])]),default:l(()=>[e(d,{"onUpdate:modelValue":[t[0]||(t[0]=u=>Te.value=!1),t[1]||(t[1]=u=>K.value=u)],modelValue:K.value},null,8,["modelValue"])]),_:2},1032,["modelValue","return-value"])]),_:2},1024)]),_:2},1024),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(q,{class:"tw-mt-6 tw-mr-2"},{default:l(()=>[_("Zeitraum erster Schulungstag*")]),_:1}),e(m(Ne),{modelValue:m(c).first_date_start_and_end_time,"onUpdate:modelValue":t[3]||(t[3]=u=>m(c).first_date_start_and_end_time=u),hideClearButton:!0,hourLabel:"Std.",minuteLabel:"Protokoll",disabled:h.value},null,8,["modelValue","hourLabel","disabled"])]),_:1})]),_:2},1024),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(g,{modelValue:Ce.value,"onUpdate:modelValue":t[6]||(t[6]=u=>Ce.value=u),"return-value":M.value,"close-on-content-click":!1},{activator:l(({props:u})=>[e(w,p({label:"Zweiter Schulungstag*",class:"tw-cursor-pointer","model-value":be.value,"error-messages":T.value.second_date,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},u,{disabled:h.value}),null,16,["model-value","error-messages","disabled"])]),default:l(()=>[e(d,{"onUpdate:modelValue":[t[4]||(t[4]=u=>Ce.value=!1),t[5]||(t[5]=u=>M.value=u)],modelValue:M.value},null,8,["modelValue"])]),_:2},1032,["modelValue","return-value"])]),_:2},1024)]),_:2},1024),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(q,{class:"tw-mt-6 tw-mr-2"},{default:l(()=>[_("Zeitraum zweiter Schulungstag*")]),_:1}),e(m(Ne),{modelValue:m(c).first_date_start_and_end_time,"onUpdate:modelValue":t[7]||(t[7]=u=>m(c).first_date_start_and_end_time=u),hideClearButton:!0,hourLabel:"Std.",minuteLabel:"Protokoll",disabled:h.value},null,8,["modelValue","hourLabel","disabled"])]),_:1})]),_:2},1024),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(w,{type:"number",modelValue:m(c).max_participant_count,"onUpdate:modelValue":t[8]||(t[8]=u=>m(c).max_participant_count=u),"error-messages":T.value.max_participant_count,label:"Max. Teilnehmerzahl*",disabled:h.value,clearable:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(le,{modelValue:m(c).type,"onUpdate:modelValue":t[9]||(t[9]=u=>m(c).type=u),"error-messages":T.value.type,items:H.types,"item-title":"title","item-value":"value",label:"Typ*",disabled:h.value,clearable:""},null,8,["modelValue","error-messages","items","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(le,{modelValue:m(c).multi_id,"onUpdate:modelValue":t[10]||(t[10]=u=>m(c).multi_id=u),"error-messages":T.value.multi_id,items:H.multipliers,"item-title":"full_name","item-value":"id",label:"Multiplikator*",disabled:h.value,clearable:""},null,8,["modelValue","error-messages","items","disabled"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"6"},{default:l(()=>[e(Le,{modelValue:m(c).location,"onUpdate:modelValue":t[11]||(t[11]=u=>m(c).location=u),"error-messages":T.value.location,label:"Ort*",disabled:h.value,required:""},null,8,["modelValue","error-messages","disabled"])]),_:1}),e(s,{cols:"12",sm:"6"},{default:l(()=>[e(Le,{modelValue:m(c).notes,"onUpdate:modelValue":t[12]||(t[12]=u=>m(c).notes=u),"error-messages":T.value.notes,label:"Notizen",disabled:h.value,required:""},null,8,["modelValue","error-messages","disabled"])]),_:1})]),_:1})]),_:2},1024)]),_:2},1024),e(ne,null,{default:l(()=>[e($,null,{default:l(({isHovering:u,props:G})=>[e(Q,p({onClick:Ye},G,{color:u?"accent":"primary"}),{default:l(()=>[_("Zurücksetzen")]),_:2},1040,["color"])]),_:2},1024),e(ae),e($,null,{default:l(({isHovering:u,props:G})=>[e(R,p({onClick:E},G,{color:u?"accent":"primary"}),{default:l(()=>[_("Stornieren")]),_:2},1040,["color"])]),_:2},1024),e($,null,{default:l(({isHovering:u,props:G})=>[e(Q,p({onClick:el},G,{color:u?"accent":"primary"}),{default:l(()=>[_("Speichern")]),_:2},1040,["color"])]),_:2},1024)]),_:2},1024)]),_:2},1024)]),_:2},1032,["modelValue"])]),_:2},1040,["color"])]),_:1})):L("",!0)]),n.$page.props.auth.user.is_super_admin||n.$page.props.auth.user.is_admin?(y(),D(se,{key:0,modelValue:pe.value,"onUpdate:modelValue":t[14]||(t[14]=a=>pe.value=a),width:"20vw"},{default:l(()=>[e(ue,{height:"30vh"},{default:l(()=>[e(te,null,{default:l(()=>[e(J,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[gl]),_:1})]),_:1})]),_:1})]),_:1}),e(ne,null,{default:l(()=>[e(ae),e($,null,{default:l(({isHovering:a,props:v})=>[e(R,p({onClick:E},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Abbrechen")]),_:2},1040,["color"])]),_:1}),e($,null,{default:l(({isHovering:a,props:v})=>[e(Q,p({onClick:Xe},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])):L("",!0)]),default:l(()=>[r("div",bl,[n.$page.props.auth.user.is_super_admin||n.$page.props.auth.user.is_admin?(y(),W("div",hl,[r("div",wl,[e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(g,{modelValue:Se.value,"onUpdate:modelValue":t[17]||(t[17]=a=>Se.value=a),"return-value":U.value,"close-on-content-click":!1},{activator:l(({props:a})=>[e(w,p({label:"Erster Schulungstag",class:"tw-cursor-pointer","model-value":_e.value,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},a,{"onClick:clear":Je}),null,16,["model-value"])]),default:l(()=>[e(d,{"onUpdate:modelValue":[t[15]||(t[15]=a=>Se.value=!1),t[16]||(t[16]=a=>U.value=a)],modelValue:U.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(k,{locale:"de"},{default:l(()=>[e(g,{modelValue:Fe.value,"onUpdate:modelValue":t[20]||(t[20]=a=>Fe.value=a),"return-value":x.value,"close-on-content-click":!1},{activator:l(({props:a})=>[e(w,p({label:"Zweiter Schulungstag",class:"tw-cursor-pointer","model-value":fe.value,"prepend-icon":"mdi-calendar",readonly:"",clearable:""},a,{"onClick:clear":Qe}),null,16,["model-value"])]),default:l(()=>[e(d,{modelValue:x.value,"onUpdate:modelValue":[t[18]||(t[18]=a=>x.value=a),t[19]||(t[19]=a=>Fe.value=!1)]},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(w,{modelValue:O.value,"onUpdate:modelValue":t[21]||(t[21]=a=>O.value=a),label:"Ort",clearable:""},null,8,["modelValue"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(w,{type:"number",modelValue:N.value,"onUpdate:modelValue":t[22]||(t[22]=a=>N.value=a),label:"Teilnehmerzahl",clearable:""},null,8,["modelValue"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(w,{type:"number",modelValue:B.value,"onUpdate:modelValue":t[23]||(t[23]=a=>B.value=a),label:"Max. Teilnehmerzahl",clearable:""},null,8,["modelValue"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[e(le,{modelValue:j.value,"onUpdate:modelValue":t[24]||(t[24]=a=>j.value=a),items:H.types,"item-title":"title","item-value":"value",label:"Typ",multiple:"",disabled:h.value,clearable:""},null,8,["modelValue","items","disabled"])]),_:1})]),_:1}),e(b,null,{default:l(()=>[e(s,{cols:"12",sm:"4"},{default:l(()=>[e(le,{modelValue:Z.value,"onUpdate:modelValue":t[25]||(t[25]=a=>Z.value=a),items:H.statuses,"item-title":"title","item-value":"value",label:"Status",multiple:"",disabled:h.value,clearable:""},null,8,["modelValue","items","disabled"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:l(()=>[n.$page.props.auth.user.is_super_admin||n.$page.props.auth.user.is_admin?(y(),D(le,{key:0,modelValue:I.value,"onUpdate:modelValue":t[26]||(t[26]=a=>I.value=a),items:H.multipliers,"item-title":"full_name","item-value":"id",label:"Multiplikator",multiple:"",disabled:h.value,clearable:""},null,8,["modelValue","items","disabled"])):L("",!0)]),_:1}),e(s,{cols:"12",sm:"4"})]),_:1})])])):L("",!0),_(F(ke.value)+" ",1),il((y(),D(ll,{"items-per-page":Y.value,"onUpdate:itemsPerPage":t[28]||(t[28]=a=>Y.value=a),"items-per-page-options":[{value:10,title:"10"},{value:25,title:"25"},{value:50,title:"50"},{value:100,title:"100"},{value:-1,title:"$vuetify.dataFooter.itemsPerPageAll"}],"items-per-page-text":"Objekte pro Seite:",headers:qe,page:de.value,"items-length":ke.value,items:Ge.value,search:Ee.value,loading:h.value,class:"data-table-container elevation-1","item-value":"name","onUpdate:options":Ae},{item:l(({item:a})=>{var v,u,G;return[r("tr",{"data-id":a.selectable.id,"data-order":a.selectable.order},[r("td",null,F(!a.selectable.first_date||a.selectable.first_date==="-"?a.selectable.first_date:m(Be)(a.selectable.first_date,"fr-CA")),1),r("td",null,F(!a.selectable.second_date||a.selectable.second_date==="-"?a.selectable.second_date:m(Be)(a.selectable.second_date,"fr-CA")),1),r("td",null,F(a.selectable.location),1),r("td",null,F(a.selectable.prepared_participant_count),1),r("td",null,F(a.selectable.type),1),r("td",null,F(a.selectable.status),1),r("td",null,F((v=a.selectable)!=null&&v.multiplier?(G=(u=a.selectable)==null?void 0:u.multiplier)==null?void 0:G.full_name:"-"),1),r("td",null,F(a.selectable.notes),1),r("td",null,F(!a.selectable.created_at||a.selectable.created_at==="-"?a.selectable.created_at:m(je)(a.selectable.created_at,"sv-SE")),1),r("td",null,F(!a.selectable.updated_at||a.selectable.updated_at==="-"?a.selectable.updated_at:m(je)(a.selectable.updated_at,"sv-SE")),1),r("td",Vl,[a.selectable.status==="planned"?(y(),D(re,{key:0,location:"top"},{activator:l(({props:A})=>[r("span",{class:"tw-cursor-pointer",onClick:Ue=>$e(a.selectable,"confirmed")},[e(oe,p(A,{size:"small",class:"tw-me-2"}),{default:l(()=>[_("mdi-progress-check")]),_:2},1040)],8,kl)]),default:l(()=>[Sl]),_:2},1024)):L("",!0),a.selectable.status==="confirmed"?(y(),D(re,{key:1,location:"top"},{activator:l(({props:A})=>[r("span",{class:"tw-cursor-pointer",onClick:Ue=>$e(a.selectable,"completed")},[e(oe,p(A,{size:"small",class:"tw-me-2"}),{default:l(()=>[_("mdi-check")]),_:2},1040)],8,Fl)]),default:l(()=>[Tl]),_:2},1024)):L("",!0),a.selectable.status!=="completed"&&a.selectable.status!=="cancelled"&&(n.$page.props.auth.user.is_super_admin||n.$page.props.auth.user.is_admin)?(y(),D(re,{key:2,location:"top"},{activator:l(({props:A})=>[r("span",{class:"tw-cursor-pointer",onClick:Ue=>$e(a.selectable,"cancelled")},[e(oe,p(A,{size:"small",class:"tw-me-2"}),{default:l(()=>[_("mdi-close")]),_:2},1040)],8,Cl)]),default:l(()=>[$l]),_:2},1024)):L("",!0),e(re,{location:"top"},{activator:l(({props:A})=>[e(m(cl),{href:n.route("trainings.show",{id:a.selectable.id})},{default:l(()=>[e(oe,p(A,{size:"small",class:"tw-me-2"}),{default:l(()=>[_("mdi-pencil")]),_:2},1040)]),_:2},1032,["href"])]),default:l(()=>[Dl]),_:2},1024),n.$page.props.auth.user.is_super_admin||n.$page.props.auth.user.is_admin?(y(),D(re,{key:3,location:"top"},{activator:l(({props:A})=>[e(oe,p(A,{size:"small",class:"tw-me-2",onClick:Ue=>We(a.raw)}),{default:l(()=>[_("mdi-delete")]),_:2},1040,["onClick"])]),default:l(()=>[Ul]),_:2},1024)):L("",!0)])],8,yl)]}),"no-data":l(()=>[r("div",xl,[ze.value?(y(),W("h3",Pl,"Die Tabelle ist leer.")):(y(),W(Ve,{key:1},[El,e(R,{color:"primary",onClick:t[27]||(t[27]=a=>Ae({page:1,itemsPerPage:Y.value,clearFilters:!0}))},{default:l(()=>[_("Reset")]),_:1})],64))])]),_:1},8,["items-per-page","items-per-page-options","page","items-length","items","search","loading"])),[[ul]]),e(J,null,{default:l(()=>[e(b,null,{default:l(()=>[e(se,{modelValue:ce.value,"onUpdate:modelValue":t[31]||(t[31]=a=>ce.value=a),width:"80vw"},{default:l(()=>[e(ue,{height:"80vw"},{default:l(()=>[e(f,null,{default:l(()=>[zl]),_:1}),e(te,null,{default:l(()=>[e(J,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[(y(!0),W(Ve,null,Oe(He.value,a=>(y(),D(b,null,{default:l(()=>[e(s,{cols:"12",sm:"2"},{default:l(()=>[r("span",Al,F(a==null?void 0:a.label)+":",1)]),_:2},1024),e(s,{cols:"12",sm:"10"},{default:l(()=>[r("span",null,F(a==null?void 0:a.value),1)]),_:2},1024)]),_:2},1024))),256))]),_:1})]),_:1}),he.value?(y(),D(b,{key:0},{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[Ll,Ol]),_:1}),e(s,{cols:"12",class:"tw--mt-6"},{default:l(()=>[e(nl,null,{default:l(()=>[(y(!0),W(Ve,null,Oe(he.value,a=>(y(),D(al,{class:"hide-details",key:a.id},{default:l(()=>[e(tl,{class:"!tw-font-bold !tw-font-italic !tw-text-black",label:a.name,value:a,modelValue:we.value,"onUpdate:modelValue":t[29]||(t[29]=v=>we.value=v)},null,8,["label","value","modelValue"])]),_:2},1024))),128))]),_:1})]),_:1})]),_:1})):L("",!0)]),_:1})]),_:1}),e(ne,null,{default:l(()=>[e(ae),e($,null,{default:l(({isHovering:a,props:v})=>[e(R,p({onClick:E},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Abbrechen")]),_:2},1040,["color"])]),_:1}),e($,null,{default:l(({isHovering:a,props:v})=>[e(Q,p({onClick:t[30]||(t[30]=u=>De("confirmed"))},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"]),e(se,{modelValue:ve.value,"onUpdate:modelValue":t[33]||(t[33]=a=>ve.value=a),width:"20vw"},{default:l(()=>[e(ue,{height:"30vh"},{default:l(()=>[e(te,null,{default:l(()=>[e(J,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[Nl]),_:1})]),_:1})]),_:1})]),_:1}),e(ne,null,{default:l(()=>[e(ae),e($,null,{default:l(({isHovering:a,props:v})=>[e(R,p({onClick:E},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Abbrechen")]),_:2},1040,["color"])]),_:1}),e($,null,{default:l(({isHovering:a,props:v})=>[e(Q,p({onClick:t[32]||(t[32]=u=>De("completed"))},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"]),e(se,{modelValue:me.value,"onUpdate:modelValue":t[35]||(t[35]=a=>me.value=a),width:"20vw"},{default:l(()=>[e(ue,{height:"30vh"},{default:l(()=>[e(te,null,{default:l(()=>[e(J,null,{default:l(()=>[e(b,null,{default:l(()=>[e(s,{cols:"12"},{default:l(()=>[Bl]),_:1})]),_:1})]),_:1})]),_:1}),e(ne,null,{default:l(()=>[e(ae),e($,null,{default:l(({isHovering:a,props:v})=>[e(R,p({onClick:E},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Abbrechen")]),_:2},1040,["color"])]),_:1}),e($,null,{default:l(({isHovering:a,props:v})=>[e(Q,p({onClick:t[34]||(t[34]=u=>De("cancelled"))},v,{color:a?"accent":"primary"}),{default:l(()=>[_("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1})]),_:1})])]),_:1},8,["errors"])],64)}}};export{Gl as default};
import{d as ge}from"./index-a2fa661b.js";import{K as fe,k as p,h as F,l as we,m as be,T as j,r as i,n as ye,f as R,a as e,u as r,w as t,F as ee,o as b,Z as ze,b as _,p as g,d as v,c as B,g as A,t as G,q as Ve,i as ke,O as Se}from"./app-67c437eb.js";import{S as De}from"./sortable.esm-0d19b1d3.js";import{_ as xe}from"./AuthenticatedLayout-fda7996a.js";import"./ApplicationLogo-c274dc9f.js";const Pe=_("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Domänen",-1),Ue={class:"tw-flex tw-items-center tw-justify-end"},Be=_("span",{class:"tw-text-h5"},"Verwalte Domäne",-1),Ne=_("p",null,"Schwellenwerte für Altersgruppe bis 2,5 Jahre",-1),qe=_("p",null,"Schwellenwerte für Altersgruppe bis 4,5 Jahre",-1),Ce={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},Fe={class:"tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6"},Ae={class:"tw-w-full"},Ee={class:"tw-ml-6"},Ie=["data-id","data-order"],Oe=_("span",null,"neu anordnen",-1),Te=_("span",null,"Domäne ansehen",-1),$e=_("span",null,"Domäne löschen",-1),je={class:"tw-py-6"},Re={key:0,class:"tw-mb-4"},Ge=_("h3",{class:"tw-mb-4"},"Die Tabelle ist leer. Bitte setzen Sie die Suchfilter zurück.",-1),He={__name:"Domains",props:{items:Array,currentPage:Number,perPage:Number,lastPage:Number,total:Number,paging:Boolean,orderBy:String,sort:String,filters:Object,errors:Object,roles:Array},setup(le){const f=le;ge.Inertia.on("success",s=>{let l=s.detail.page.props;s.detail.page.component==="Domains/Domains"&&l&&(V.value=l.currentPage,k.value=l.perPage,te.value=l.orderBy,ae.value=l.sort,K.value=l.total,oe.value=l.lastPage,y.value=l.filters.search??null)}),fe().props.auth.user;const V=p(f.currentPage),k=p(f.perPage),te=p(f.orderBy),ae=p(f.sort),K=p(f.total),oe=p(f.lastPage),y=p(f.filters.search??null),Z=p(""),m=p(f.errors||{}),re=p(null),E=p(!1),N=p(!1),q=p(!1),J=p(null),se=[{title:"Kürzel",key:"is_online",width:"15%",sortable:!1},{title:"Name",key:"first_name",width:"75%",sortable:!1},{title:"Aktion",key:"actions",width:"10%",sortable:!1,align:"center"}],ne=F(()=>f.items.map(s=>{const l={...s};for(const c in l)(l[c]===null||l[c]===void 0)&&(l[c]="-");return l})),w=F(()=>a.daz_dependent?3:6),L=F(()=>y.value===null),de=F(()=>y.value!==null);we(()=>{const s={handle:".v-data-table tbody .glyphicon-move",animation:150,onUpdate:function(l){ce(l)}};De.create(document.querySelector(".v-data-table tbody"),s)}),be(N,s=>{s||D()});const M=async({page:s,itemsPerPage:l,sortBy:c,clearFilters:u})=>{if(u&&(y.value=null),s===V.value&&u||L||de){E.value=!0;let n={data:{page:s,per_page:l}};c&&c.length>0&&(n.data.order_by=c[0].key,n.data.sort=c[0].order),n.data.search=y.value,await Se.reload(n),V.value=s,k.value=l,E.value=!1}},ue=s=>{J.value=s.name,S.id=s.id,q.value=!0},S=j({id:null}),_e=async()=>{S.processing=!0;let s={onSuccess:l=>{D()},onError:l=>{m.value=l},onFinish:()=>{S.processing=!1}};S.delete(route("domains.destroy",{id:S.id}),s)},D=()=>{N.value=!1,q.value=!1,a.reset(),a.clearErrors(),a.daz_dependent=!1,m.value={}},ie=()=>{a.reset(),a.clearErrors(),a.daz_dependent=!1},a=j({name:null,abbreviation:null,age_2_red_threshold:null,age_2_red_threshold_daz:null,age_2_yellow_threshold:null,age_2_yellow_threshold_daz:null,age_4_red_threshold:null,age_4_red_threshold_daz:null,age_4_yellow_threshold:null,age_4_yellow_threshold_daz:null,daz_dependent:!1}),me=async()=>{a.processing=!0,a.daz_dependent||(a.age_2_red_threshold_daz=a.age_2_red_threshold,a.age_2_yellow_threshold_daz=a.age_2_yellow_threshold,a.age_4_red_threshold_daz=a.age_4_red_threshold,a.age_4_yellow_threshold_daz=a.age_4_yellow_threshold),a.post(route("domains.store"),{onSuccess:s=>{D()},onError:s=>{m.value=s},onFinish:()=>{a.processing=!1}})},C=j({items:[]}),ce=s=>{C.processing=!0;let l=[],c=0;V.value>1&&(c=10*V.value-10),[].forEach.call(s.from.querySelectorAll("tr"),function(u,n){u.setAttribute("data-order",c+n),l.push({id:u.getAttribute("data-id"),order:c+n})}),C.items=l,C.post(route("domains.reorder"),{preserveScroll:!0,preserveState:!1,onSuccess:u=>{},onError:u=>{m.value=u},onFinish:()=>{C.processing=!1}})};return(s,l)=>{const c=i("v-card-title"),u=i("v-text-field"),n=i("v-col"),pe=i("v-switch"),x=i("v-row"),I=i("v-spacer"),H=i("v-container"),Q=i("v-card-text"),O=i("v-btn-primary"),z=i("v-hover"),P=i("v-btn"),W=i("v-card-actions"),X=i("v-card"),Y=i("v-dialog"),T=i("v-icon"),$=i("v-tooltip"),he=i("v-data-table-server"),ve=ye("sortable-data-table");return b(),R(ee,null,[e(r(ze),{title:"Domänen"}),e(xe,{errors:m.value},{header:t(()=>[Pe,_("div",Ue,[e(z,null,{default:t(({isHovering:d,props:h})=>[e(P,g(h,{color:d?"accent":"primary",dark:""}),{default:t(()=>[v(" Anlegen "),e(Y,{modelValue:N.value,"onUpdate:modelValue":l[11]||(l[11]=o=>N.value=o),activator:"parent",width:"80vw"},{default:t(()=>[e(X,{height:"80vh"},{default:t(()=>[e(c,null,{default:t(()=>[Be]),_:1}),e(Q,null,{default:t(()=>[e(H,null,{default:t(()=>[e(x,null,{default:t(()=>[e(n,{cols:"12",sm:"3"},{default:t(()=>[e(u,{modelValue:r(a).abbreviation,"onUpdate:modelValue":l[0]||(l[0]=o=>r(a).abbreviation=o),"error-messages":m.value.abbreviation,label:"Kürzel*",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:r(a).name,"onUpdate:modelValue":l[1]||(l[1]=o=>r(a).name=o),"error-messages":m.value.name,label:"Name der Domäne*",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"3"},{default:t(()=>[e(pe,{modelValue:r(a).daz_dependent,"onUpdate:modelValue":l[2]||(l[2]=o=>r(a).daz_dependent=o),"hide-details":"",label:"Einstellen mit Daz"},null,8,["modelValue"])]),_:1})]),_:1}),e(x,null,{default:t(()=>[e(n,{cols:"12"},{default:t(()=>[Ne]),_:1}),e(n,{cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_2_red_threshold,"onUpdate:modelValue":l[3]||(l[3]=o=>r(a).age_2_red_threshold=o),"error-messages":m.value.age_2_red_threshold,label:"Schwellwert Rot*",placeholder:"z.B. 5",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"]),r(a).daz_dependent?(b(),B(n,{key:0,cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_2_red_threshold_daz,"onUpdate:modelValue":l[4]||(l[4]=o=>r(a).age_2_red_threshold_daz=o),"error-messages":m.value.age_2_red_threshold_daz,label:"Schwellwert Rot mit Daz*",placeholder:"z.B. 3",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"])):A("",!0),e(n,{cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_2_yellow_threshold,"onUpdate:modelValue":l[5]||(l[5]=o=>r(a).age_2_yellow_threshold=o),"error-messages":m.value.age_2_yellow_threshold,label:"Schwellwert Gelb*",placeholder:"z.B. 10",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"]),r(a).daz_dependent?(b(),B(n,{key:1,cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_2_yellow_threshold_daz,"onUpdate:modelValue":l[6]||(l[6]=o=>r(a).age_2_yellow_threshold_daz=o),"error-messages":m.value.age_2_yellow_threshold_daz,label:"Schwellwert Gelb mit Daz*",placeholder:"z.B. 8",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"])):A("",!0)]),_:1}),e(x,null,{default:t(()=>[e(I),e(n,{cols:"12"},{default:t(()=>[qe]),_:1}),e(n,{cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_4_red_threshold,"onUpdate:modelValue":l[7]||(l[7]=o=>r(a).age_4_red_threshold=o),"error-messages":m.value.age_4_red_threshold,label:"Schwellwert Rot*",placeholder:"z.B. 5",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"]),r(a).daz_dependent?(b(),B(n,{key:0,cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_4_red_threshold_daz,"onUpdate:modelValue":l[8]||(l[8]=o=>r(a).age_4_red_threshold_daz=o),"error-messages":m.value.age_4_red_threshold_daz,label:"Schwellwert Rot mit Daz*",placeholder:"z.B. 3",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"])):A("",!0),e(n,{cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_4_yellow_threshold,"onUpdate:modelValue":l[9]||(l[9]=o=>r(a).age_4_yellow_threshold=o),"error-messages":m.value.age_4_yellow_threshold,label:"Schwellwert Gelb*",placeholder:"z.B. 10",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"]),r(a).daz_dependent?(b(),B(n,{key:1,cols:"12",sm:w.value},{default:t(()=>[e(u,{modelValue:r(a).age_4_yellow_threshold_daz,"onUpdate:modelValue":l[10]||(l[10]=o=>r(a).age_4_yellow_threshold_daz=o),"error-messages":m.value.age_4_yellow_threshold_daz,label:"Schwellwert Gelb mit Daz*",placeholder:"z.B. 8",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1},8,["sm"])):A("",!0)]),_:1})]),_:1})]),_:1}),e(W,null,{default:t(()=>[e(z,null,{default:t(({isHovering:o,props:U})=>[e(O,g({onClick:ie},U,{color:o?"primary":"accent"}),{default:t(()=>[v("Zurücksetzen")]),_:2},1040,["color"])]),_:2},1024),e(I),e(z,null,{default:t(({isHovering:o,props:U})=>[e(P,g({onClick:D},U,{color:o?"accent":"primary"}),{default:t(()=>[v("Zurück")]),_:2},1040,["color"])]),_:2},1024),e(z,null,{default:t(({isHovering:o,props:U})=>[e(O,g({onClick:me},U,{color:o?"accent":"primary"}),{default:t(()=>[v("Speichern")]),_:2},1040,["color"])]),_:2},1024)]),_:2},1024)]),_:2},1024)]),_:2},1032,["modelValue"])]),_:2},1040,["color"])]),_:1})]),e(Y,{modelValue:q.value,"onUpdate:modelValue":l[12]||(l[12]=d=>q.value=d),width:"20vw"},{default:t(()=>[e(X,{height:"30vh"},{default:t(()=>[e(Q,null,{default:t(()=>[e(H,null,{default:t(()=>[e(x,null,{default:t(()=>[e(n,{cols:"12"},{default:t(()=>[_("p",null,"Sind Sie sicher, dass Sie die Domäne "+G(J.value)+" löschen möchten?",1)]),_:1})]),_:1})]),_:1})]),_:1}),e(W,null,{default:t(()=>[e(I),e(z,null,{default:t(({isHovering:d,props:h})=>[e(P,g({onClick:D},h,{color:d?"accent":"primary"}),{default:t(()=>[v("Abbrechen")]),_:2},1040,["color"])]),_:1}),e(z,null,{default:t(({isHovering:d,props:h})=>[e(O,g({onClick:_e},h,{color:d?"accent":"primary"}),{default:t(()=>[v("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),default:t(()=>[_("div",Ce,[_("div",Fe,[_("div",Ae,[e(x,null,{default:t(()=>[e(n,{cols:"12",sm:"5"},{default:t(()=>[e(u,{modelValue:y.value,"onUpdate:modelValue":l[13]||(l[13]=d=>y.value=d),label:"Kürzel/Name"},null,8,["modelValue"])]),_:1})]),_:1})]),_("div",Ee,[e(z,null,{default:t(({isHovering:d,props:h})=>[e(P,g({class:"tw-mt-2"},h,{color:d?"accent":"primary",onClick:l[14]||(l[14]=o=>Z.value=String(Date.now())),dark:""}),{default:t(()=>[v("Suche")]),_:2},1040,["color"])]),_:1})])]),Ve((b(),B(he,{"items-per-page":k.value,"onUpdate:itemsPerPage":l[16]||(l[16]=d=>k.value=d),"items-per-page-options":[{value:10,title:"10"},{value:25,title:"25"},{value:50,title:"50"},{value:100,title:"100"},{value:-1,title:"$vuetify.dataFooter.itemsPerPageAll"}],"items-per-page-text":"Objekte pro Seite:",headers:se,page:V.value,"items-length":K.value,items:ne.value,search:Z.value,loading:E.value,class:"data-table-container elevation-1","item-value":"name","onUpdate:options":M},{item:t(({item:d})=>[_("tr",{"data-id":d.selectable.id,"data-order":d.selectable.order},[_("td",null,G(d.selectable.abbreviation),1),_("td",null,G(d.selectable.name),1),_("td",null,[e($,{location:"top"},{activator:t(({props:h})=>[e(T,g({draggable:"true",onDragstart:o=>re.value=d.raw,color:"primary"},h,{size:"small",class:"tw-me-2 glyphicon-move"}),{default:t(()=>[v("mdi-arrow-collapse-vertical")]),_:2},1040,["onDragstart"])]),default:t(()=>[Oe]),_:2},1024),e($,{location:"top"},{activator:t(({props:h})=>[e(r(ke),{href:s.route("domains.show",{id:d.selectable.id})},{default:t(()=>[e(T,g(h,{size:"small",class:"tw-me-2"}),{default:t(()=>[v("mdi-eye")]),_:2},1040)]),_:2},1032,["href"])]),default:t(()=>[Te]),_:2},1024),e($,{location:"top"},{activator:t(({props:h})=>[e(T,g(h,{size:"small",class:"tw-me-2",onClick:o=>ue(d.raw)}),{default:t(()=>[v("mdi-delete")]),_:2},1040,["onClick"])]),default:t(()=>[$e]),_:2},1024)])],8,Ie)]),"no-data":t(()=>[_("div",je,[L.value?(b(),R("h3",Re,"Die Tabelle ist leer.")):(b(),R(ee,{key:1},[Ge,e(P,{color:"primary",onClick:l[15]||(l[15]=d=>M({page:1,itemsPerPage:k.value,clearFilters:!0}))},{default:t(()=>[v("Reset")]),_:1})],64))])]),_:1},8,["items-per-page","items-per-page-options","page","items-length","items","search","loading"])),[[ve]])])]),_:1},8,["errors"])],64)}}};export{He as default};
import{d as de}from"./index-0d7197fb.js";import{K as pe,j as i,k as J,l as _e,T as V,r as u,o as z,f as Q,a as t,u as w,w as e,F as ve,Z as me,i as W,m,d,h as F,b as o,t as y,c as A,O as fe}from"./app-d4e270fa.js";import{b as B}from"./common-ea192878.js";import{_ as he}from"./AuthenticatedLayout-70341763.js";import{_ as ge}from"./EvaluationDomainsList-5d80ae98.js";import"./ApplicationLogo-20741013.js";const be=o("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Einschätzungen",-1),we={key:0,class:"tw-flex tw-items-center tw-justify-end"},ye={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},ke=["data-id","data-order"],Ee={align:"center"},ze=o("span",null,"Einschätzung bearbeiten",-1),Se=o("span",null,"Einschätzung ansehen",-1),Pe=o("span",null,"Einschätzung löschen",-1),De=o("div",{class:"tw-py-6"},[o("h3",{class:"tw-mb-4"},"Die Tabelle ist leer.")],-1),$e={class:"tw-text-right"},Fe={class:"tw-text-center"},xe=o("h1",{class:"tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8"}," Einschätzung wurde eingereicht ",-1),Ce=o("p",{class:"tw-mb-8"}," Folgendes Einschätzung wurde eingereicht und kann nur bis 15 Minuten nach Einreichung bearbeitet werden. Danach verschwindet es aus Ihrer Übersicht. Sollten Sie es zurückziehen oder bearbeiten wollen, klicke Sie auf 'Abgabe zurückziehen. Nachfolgend erhalten Sie eine Übersicht des eingereichten Screenings, welches Sie über den Download-Button als PDF herunterladen können. ",-1),Ie=o("span",{class:"tw-font-black"},"Bezeichner des Einschätzung",-1),je={__name:"Evaluations",props:{items:Array,currentPage:Number,perPage:Number,lastPage:Number,total:Number,paging:Boolean,orderBy:String,sort:String,filters:Object,errors:Object},setup(X){const v=X;de.Inertia.on("success",l=>{let a=l.detail.page.props;l.detail.page.component==="Evaluations/Evaluations"&&a&&(g.value=a.currentPage,k.value=a.perPage,T.value=a.orderBy,Y.value=a.sort,O.value=a.total,ee.value=a.lastPage)}),pe().props.auth.user;const g=i(v.currentPage),k=i(v.perPage),T=i(v.orderBy),Y=i(v.sort),O=i(v.total),ee=i(v.lastPage),te=i(""),f=i(v.errors||{}),x=i(!1),j=i(!1),S=i(!1),R=i(null),P=i(!1),p=i(null),U=i(null),ae=[{title:"ID",key:"id",width:"40%",sortable:!1},{title:"Zuletzt bearbeitet",key:"updated_at",width:"15%",sortable:!1},{title:"Abgegeben am",key:"finished_at",width:"15%",sortable:!1},{title:"Bearbeitungszeit endet",key:"not_editable_at",width:"20%",sortable:!1},{title:"Aktion",key:"actions",width:"10%",sortable:!1,align:"center"}],le=J(()=>v.items.map(l=>{const a={...l};for(const r in a)(a[r]===null||a[r]===void 0)&&(a[r]="-");return a})),ne=J(()=>p.value.age?U.value.map(l=>({...l,subdomains:l.subdomains.map(a=>({...a,milestones:a.milestones.filter(r=>parseFloat(r.age)===parseFloat(p.value.age))})).filter(a=>a.milestones.length>0)})).filter(l=>l.subdomains.length>0):[]);_e(j,l=>{l||h()});const oe=async({page:l,itemsPerPage:a,sortBy:r,clearFilters:_})=>{if(l!==g.value||l===g.value&&_||[l,a,r]!==[g.value,k.value,T.value]){x.value=!0;let c={data:{page:l,per_page:a}};r&&r.length>0?(c.data.order_by=r[0].key,c.data.sort=r[0].order):(c.data.order_by=null,c.data.sort=null),await fe.reload(c),g.value=l,k.value=a,x.value=!1}},se=l=>{R.value=l.custom_unique_id,E.id=l.id,S.value=!0},b=V({id:null}),re=async l=>{b.id=l.id,b.processing=!0,b.post(route("evaluations.show_popup",{id:b.id}),{onSuccess:a=>{b.clearErrors(),f.value={},P.value=!0,p.value=a.props.data.item,U.value=a.props.data.domains},onError:a=>{f.value=a},onFinish:()=>{b.processing=!1}})},h=()=>{j.value=!1,S.value=!1,P.value=!1,f.value={}},E=V({id:null}),ie=async()=>{E.processing=!0;let l={onSuccess:a=>{h()},onError:a=>{h(),f.value=a},onFinish:()=>{E.processing=!1}};E.delete(route("evaluations.destroy",{id:E.id}),l)},D=V({}),ue=async l=>{D.processing=!0,D.post(route("evaluations.unfinished",{id:l}),{preserveState:!1,onSuccess:a=>{D.clearErrors(),f.value={},h()},onError:a=>{f.value=a},onFinish:()=>{D.processing=!1}})};return(l,a)=>{const r=u("v-btn-primary"),_=u("v-hover"),c=u("v-col"),C=u("v-row"),q=u("v-container"),Z=u("v-card-text"),H=u("v-spacer"),I=u("v-btn"),K=u("v-card-actions"),L=u("v-card"),M=u("v-dialog"),$=u("v-icon"),N=u("v-tooltip"),ce=u("v-data-table-server");return z(),Q(ve,null,[t(w(me),{title:"Einschätzungen"}),t(he,{errors:f.value},{header:e(()=>[be,l.$page.props.auth.user.is_manager||l.$page.props.auth.user.is_employer?(z(),Q("div",we,[t(w(W),{href:l.route("evaluations.create")},{default:e(()=>[t(_,null,{default:e(({isHovering:n,props:s})=>[t(r,m(s,{color:n?"accent":"primary"}),{default:e(()=>[d(" Anlegen ")]),_:2},1040,["color"])]),_:1})]),_:1},8,["href"])])):F("",!0),t(M,{modelValue:S.value,"onUpdate:modelValue":a[0]||(a[0]=n=>S.value=n),width:"20vw"},{default:e(()=>[t(L,{height:"30vh"},{default:e(()=>[t(Z,null,{default:e(()=>[t(q,null,{default:e(()=>[t(C,null,{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[o("p",null,"Sind Sie sicher, dass Sie die Einrichtung "+y(R.value)+" löschen möchten?",1)]),_:1})]),_:1})]),_:1})]),_:1}),t(K,null,{default:e(()=>[t(H),t(_,null,{default:e(({isHovering:n,props:s})=>[t(I,m({onClick:h},s,{color:n?"accent":"primary"}),{default:e(()=>[d("Abbrechen")]),_:2},1040,["color"])]),_:1}),t(_,null,{default:e(({isHovering:n,props:s})=>[t(r,m({onClick:ie},s,{color:n?"accent":"primary"}),{default:e(()=>[d("Löschen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),default:e(()=>[o("div",ye,[t(ce,{"items-per-page":k.value,"onUpdate:itemsPerPage":a[1]||(a[1]=n=>k.value=n),"items-per-page-options":[{value:10,title:"10"},{value:25,title:"25"},{value:50,title:"50"},{value:100,title:"100"},{value:-1,title:"$vuetify.dataFooter.itemsPerPageAll"}],"items-per-page-text":"Objekte pro Seite:",headers:ae,page:g.value,"items-length":O.value,items:le.value,search:te.value,loading:x.value,class:"data-table-container elevation-1","item-value":"name","onUpdate:options":oe},{item:e(({item:n})=>[o("tr",{"data-id":n.id,"data-order":n.order},[o("td",null,y(`${n.kita.formatted_name}_${n.custom_unique_id}`),1),o("td",null,y(w(B)(n.updated_at,"de-DE")),1),o("td",null,y(w(B)(n.finished_at,"de-DE")),1),o("td",null,y(w(B)(n.not_editable_at,"de-DE")),1),o("td",Ee,[n.editable&&(l.$page.props.auth.user.is_manager||l.$page.props.auth.user.is_employer)?(z(),A(N,{key:0,location:"top"},{activator:e(({props:s})=>[t(w(W),{href:l.route("evaluations.edit",{id:n.id})},{default:e(()=>[t($,m(s,{size:"small",class:"tw-me-2"}),{default:e(()=>[d("mdi-pencil")]),_:2},1040)]),_:2},1032,["href"])]),default:e(()=>[ze]),_:2},1024)):F("",!0),n.finished?(z(),A(N,{key:1,location:"top"},{activator:e(({props:s})=>[t($,m(s,{size:"small",class:"tw-me-2",onClick:G=>re(n)}),{default:e(()=>[d("mdi-eye")]),_:2},1040,["onClick"])]),default:e(()=>[Se]),_:2},1024)):F("",!0),t(N,{location:"top"},{activator:e(({props:s})=>[t($,m(s,{size:"small",class:"tw-me-2",onClick:G=>se(n)}),{default:e(()=>[d("mdi-delete")]),_:2},1040,["onClick"])]),default:e(()=>[Pe]),_:2},1024)])],8,ke)]),"no-data":e(()=>[De]),_:1},8,["items-per-page","items-per-page-options","page","items-length","items","search","loading"])]),t(M,{modelValue:P.value,"onUpdate:modelValue":a[3]||(a[3]=n=>P.value=n),width:"95vw"},{default:e(()=>[t(L,{height:"95vh"},{default:e(()=>[t(Z,null,{default:e(()=>[t(q,null,{default:e(()=>[t(C,{class:"result-evaluation-domains"},{default:e(()=>[t(c,{cols:"12"},{default:e(()=>[t(_,null,{default:e(({isHovering:n,props:s})=>[o("div",$e,[t($,m(s,{size:"small",class:"tw-me-2",onClick:h,title:"Fenster schließen"}),{default:e(()=>[d("mdi-close")]),_:2},1040)])]),_:1})]),_:1})]),_:1}),t(C,{class:"result-evaluation-domains"},{default:e(()=>[t(c,{cols:"8",offset:"2"},{default:e(()=>[o("div",Fe,[xe,Ce,t(_,null,{default:e(({isHovering:n,props:s})=>[t(I,{href:l.route("evaluations.pdf",{id:p.value.id}),class:"tw-px-2 tw-py-3 tw-mb-4 tw-mr-4 tw-normal-case",color:n?"primary":"accent"},{default:e(()=>[d(" Einschätzung als PDF downloaden ")]),_:2},1032,["href","color"])]),_:1}),p.value.editable&&(l.$page.props.auth.user.is_manager||l.$page.props.auth.user.is_employer)?(z(),A(_,{key:0},{default:e(({isHovering:n,props:s})=>[t(I,{onClick:a[2]||(a[2]=G=>ue(p.value.id)),class:"tw-px-2 tw-py-3 tw-mb-4 tw-normal-case",color:n?"accent":"primary"},{default:e(()=>[d(" Abgabe zurückziehen ")]),_:2},1032,["color"])]),_:1})):F("",!0)])]),_:1}),t(c,{cols:"12"},{default:e(()=>[o("p",null,[Ie,d(": "+y(`${p.value.kita.formatted_name}_${p.value.custom_unique_id}`),1)])]),_:1}),t(c,{cols:"12"},{default:e(()=>[t(ge,{ratings:p.value.data,domains:ne.value,disabled:!0},null,8,["ratings","domains"])]),_:1})]),_:1})]),_:1})]),_:1}),t(K,null,{default:e(()=>[t(H),t(_,null,{default:e(({isHovering:n,props:s})=>[t(r,m({onClick:h},s,{color:n?"accent":"primary"}),{default:e(()=>[d("Abbrechen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["errors"])],64)}}};export{je as default};
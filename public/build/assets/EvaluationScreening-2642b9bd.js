import{d as k}from"./index-5d364f83.js";import{K as x,k as c,h as y,r as i,n as B,o as d,f as D,a as r,u as p,w as e,F as N,Z as S,b as t,q as E,c as I,t as V,i as A,p as C,d as F}from"./app-6c3fc213.js";import{_ as T}from"./AuthenticatedLayout-9ef0fe14.js";import"./ApplicationLogo-5280591d.js";const j=t("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Screening-kategorie für prüfung auswählen",-1),q={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},z=["data-id","data-order"],K={align:"center"},O=t("span",null,"Einrichtung bearbeiten",-1),P=t("div",{class:"tw-py-6"},[t("h3",{class:"tw-mb-4"},"Die Tabelle ist leer.")],-1),J={__name:"EvaluationScreening",props:{domains:Array,errors:Object},setup(_){const l=_;k.Inertia.on("success",a=>{a.detail.page.props,a.detail.page.component}),x().props.auth.user;const m=c(l.errors||{}),u=c(!1),f=[{title:"Name",key:"uuid",width:"95%",sortable:!1},{title:"Aktion",key:"actions",width:"5%",sortable:!1,align:"center"}],h=y(()=>l.domains.map(a=>{const s={...a};for(const o in s)(s[o]===null||s[o]===void 0)&&(s[o]="-");return s}));return(a,s)=>{const o=i("v-icon"),g=i("v-tooltip"),b=i("v-data-table-server"),w=B("sortable-data-table");return d(),D(N,null,[r(p(S),{title:"Screening-kategorie für prüfung auswählen"}),r(T,{errors:m.value},{header:e(()=>[j]),default:e(()=>[t("div",q,[E((d(),I(b,{"items-per-page":-1,headers:f,items:h.value,loading:u.value,class:"data-table-container data-table-container-hide-footer elevation-1","item-value":"name"},{item:e(({item:n})=>[t("tr",{"data-id":n.selectable.id,"data-order":n.selectable.order},[t("td",null,V(n.selectable.name),1),t("td",K,[r(g,{location:"top"},{activator:e(({props:v})=>[r(p(A),{href:a.route("screening.show",{id:n.selectable.id})},{default:e(()=>[r(o,C(v,{size:"small",class:"tw-me-2"}),{default:e(()=>[F("mdi-arrow-right-bold")]),_:2},1040)]),_:2},1032,["href"])]),default:e(()=>[O]),_:2},1024)])],8,z)]),"no-data":e(()=>[P]),_:1},8,["items","loading"])),[[w]])])]),_:1},8,["errors"])],64)}}};export{J as default};
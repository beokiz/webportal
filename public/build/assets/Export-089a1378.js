import{d as H}from"./index-b01fbbcc.js";import{K as P,k as r,h as S,m as $,T as I,r as d,o as B,f as J,a as t,u as N,w as o,F as L,Z as T,b as f,p as y,c as R,g as K,d as Y,N as O}from"./app-3f8453d9.js";import{_ as Z}from"./AuthenticatedLayout-c678c6b4.js";import{a as q}from"./common-6599ea66.js";import{v as G}from"./v4-4a60fe23.js";import"./ApplicationLogo-8ae4bc48.js";const Q=f("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Screenings exportieren",-1),W={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},X={class:"tw-bg-white tw-flex tw-justify-between tw-px-6 tw-py-6"},ee={class:"tw-w-full"},le={class:"tw-ml-6"},ue={__name:"Export",props:{domains:Object,errors:Object},setup(C){const k=C;H.Inertia.on("success",a=>{a.detail.page.props,a.detail.page.component}),P().props.auth.user;const _=r(k.errors||{}),u=r(!1),D=r(null),A=r(null),c=r(),v=r(),g=r("2.5"),w=r(null),V=r(null),x=r(!1),h=r(!1),z=S(()=>{let a=[{id:null,name:"Alle"}];return k.domains.map(e=>(a.push({id:e.id,name:e.name}),e)),a});$(c,a=>{D.value=F(a)}),$(v,a=>{A.value=F(a)});const F=a=>{const e=new Date(a),s=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],i=e.getDate(),m=s[e.getMonth()],n=e.getFullYear();return`${e.toDateString().slice(0,3)}, ${i}. ${m} ${n}`};I({});const E=async()=>{O.start(),u.value=!0;let a=new Date(c.value),e=new Date(v.value);a.setHours(a.getHours()+12),e.setHours(e.getHours()+12);try{const s=await axios.post(route("export.make"),{finished_after:a,finished_before:e,age:g.value,zip_code:w.value,domains:V.value},{responseType:"blob"}),i=s.headers["content-disposition"],m=i.indexOf("filename=");let n;m!==-1?n=i.slice(m+9).replace(/['"]/g,""):n=G();const b=new Blob([s.data],{type:s.headers["content-type"]}),p=document.createElement("a");p.href=window.URL.createObjectURL(b),p.download=n,p.click()}catch(s){console.error(s)}O.done(),u.value=!1};return(a,e)=>{const s=d("v-text-field"),i=d("v-date-picker"),m=d("v-menu"),n=d("v-col"),b=d("v-row"),p=d("v-select"),U=d("v-btn"),M=d("v-hover");return B(),J(L,null,[t(N(T),{title:"Screenings exportieren"}),t(Z,{errors:_.value},{header:o(()=>[Q]),default:o(()=>[f("div",W,[f("div",X,[f("div",ee,[t(b,null,{default:o(()=>[t(n,{cols:"12",sm:"6"},{default:o(()=>[t(m,{modelValue:x.value,"onUpdate:modelValue":e[2]||(e[2]=l=>x.value=l),"return-value":c.value,"close-on-content-click":!1},{activator:o(({props:l})=>[t(s,y({label:"Abgegeben ab",class:"tw-cursor-pointer","model-value":D.value,"prepend-icon":"mdi-calendar",readonly:""},l,{disabled:u.value}),null,16,["model-value","disabled"])]),default:o(()=>[t(i,{"onUpdate:modelValue":[e[0]||(e[0]=l=>x.value=!1),e[1]||(e[1]=l=>c.value=l)],modelValue:c.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1}),t(n,{cols:"12",sm:"6"},{default:o(()=>[t(m,{modelValue:h.value,"onUpdate:modelValue":e[5]||(e[5]=l=>h.value=l),"return-value":v.value,"close-on-content-click":!1},{activator:o(({props:l})=>[t(s,y({label:"Abgegeben bis",class:"tw-cursor-pointer","model-value":A.value,"prepend-icon":"mdi-calendar",readonly:""},l,{disabled:u.value}),null,16,["model-value","disabled"])]),default:o(()=>[t(i,{"onUpdate:modelValue":[e[3]||(e[3]=l=>h.value=!1),e[4]||(e[4]=l=>v.value=l)],modelValue:v.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),t(b,null,{default:o(()=>[a.$page.props.auth.user.is_monitor_oe||a.$page.props.auth.user.is_super_admin?(B(),R(n,{key:0,cols:"12",sm:"4"},{default:o(()=>[t(p,{modelValue:V.value,"onUpdate:modelValue":e[6]||(e[6]=l=>V.value=l),items:z.value,"error-messages":_.value.domain,"item-title":"name","item-value":"id",label:"Domäne",disabled:u.value},null,8,["modelValue","items","error-messages","disabled"])]),_:1})):K("",!0),t(n,{cols:"12",sm:"4"},{default:o(()=>[t(p,{modelValue:g.value,"onUpdate:modelValue":e[7]||(e[7]=l=>g.value=l),items:N(q),"error-messages":_.value.age,"item-title":"age_name","item-value":"age_number",label:"Altersgruppe",disabled:u.value},null,8,["modelValue","items","error-messages","disabled"])]),_:1}),t(n,{cols:"12",sm:"4"},{default:o(()=>[t(s,{modelValue:w.value,"onUpdate:modelValue":e[8]||(e[8]=l=>w.value=l),type:"number","error-messages":_.value.zipCode,label:"Postleitzahl",disabled:u.value},null,8,["modelValue","error-messages","disabled"])]),_:1})]),_:1})]),f("div",le,[t(M,null,{default:o(({isHovering:l,props:j})=>[t(U,y({class:"tw-mt-2"},j,{color:l?"accent":"primary",onClick:E,dark:"",disabled:u.value}),{default:o(()=>[Y("Exportieren")]),_:2},1040,["color","disabled"])]),_:1})])])])]),_:1},8,["errors"])],64)}}};export{ue as default};
import{d as T}from"./index-703871be.js";import{K as C,k as v,T as E,r as u,f as F,a as e,u as o,w as a,F as S,o as B,Z as N,b as V,p as g,d as f,i as P}from"./app-4d8acc34.js";import{a as Z}from"./common-1f9d3448.js";import{_ as j}from"./AuthenticatedLayout-ffb4b768.js";import"./ApplicationLogo-b9f180b5.js";const D=V("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Meilenstein verwalten",-1),G={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},H={__name:"ManageMilestone",props:{milestone:Object,errors:Object},setup(h){const b=h;T.Inertia.on("success",m=>{let s=m.detail.page.props;m.detail.page.component==="Domains/Partials/ManageMilestone"&&s&&(r.value=s.milestone)}),C().props.auth.user;const r=v(b.milestone),n=v(b.errors||{});v(!1);const y=()=>{l.reset(),l.clearErrors(),n.value={}},z=()=>{l.reset(),l.clearErrors(),l.subdomain=null,l.abbreviation=null,l.title=null,l.text=null,l.emphasis=null,l.emphasis_daz=null,l.age=null},l=E({id:r.value.id,subdomain:r.value.subdomain_id,abbreviation:r.value.abbreviation,title:r.value.title,text:r.value.text,emphasis:r.value.emphasis,emphasis_daz:r.value.emphasis_daz,age:r.value.age}),k=async()=>{l.processing=!0,l.put(route("milestones.update",{milestone:l.id}),{preserveState:!1,onSuccess:m=>{y()},onError:m=>{n.value=m},onFinish:()=>{l.processing=!1}})};return(m,s)=>{const d=u("v-text-field"),i=u("v-col"),M=u("v-select"),c=u("v-row"),U=u("v-textarea"),x=u("v-container"),w=u("v-btn"),_=u("v-hover"),q=u("v-btn-primary");return B(),F(S,null,[e(o(N),{title:"Meilenstein verwalten"}),e(j,{errors:n.value},{header:a(()=>[D]),default:a(()=>[V("div",G,[e(x,null,{default:a(()=>[e(c,null,{default:a(()=>[e(i,{cols:"12",sm:"3"},{default:a(()=>[e(d,{modelValue:o(l).abbreviation,"onUpdate:modelValue":s[0]||(s[0]=t=>o(l).abbreviation=t),"error-messages":n.value.abbreviation,label:"Kürzel*",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"3"},{default:a(()=>[e(M,{modelValue:o(l).age,"onUpdate:modelValue":s[1]||(s[1]=t=>o(l).age=t),items:o(Z),"error-messages":n.value.age,"item-title":"age_name","item-value":"age_number",label:"Altersgruppe"},null,8,["modelValue","items","error-messages"])]),_:1}),e(i,{cols:"12",sm:"3"},{default:a(()=>[e(d,{modelValue:o(l).emphasis,"onUpdate:modelValue":s[2]||(s[2]=t=>o(l).emphasis=t),"error-messages":n.value.emphasis,type:"number",label:"Gewichtung*",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"3"},{default:a(()=>[e(d,{modelValue:o(l).emphasis_daz,"onUpdate:modelValue":s[3]||(s[3]=t=>o(l).emphasis_daz=t),"error-messages":n.value.emphasis_daz,type:"number",label:"Gewichtung mit Daz*",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(c,null,{default:a(()=>[e(i,{cols:"12"},{default:a(()=>[e(d,{modelValue:o(l).title,"onUpdate:modelValue":s[4]||(s[4]=t=>o(l).title=t),"error-messages":n.value.title,label:"Titel*",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(c,null,{default:a(()=>[e(i,{cols:"12"},{default:a(()=>[e(U,{modelValue:o(l).text,"onUpdate:modelValue":s[5]||(s[5]=t=>o(l).text=t),"error-messages":n.value.text,label:"Subtext*",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1})]),_:1}),e(x,null,{default:a(()=>[e(c,null,{default:a(()=>[e(i,{cols:"12",sm:"6"},{default:a(()=>[e(_,null,{default:a(({isHovering:t,props:p})=>[e(w,g({onClick:z},p,{color:t?"primary":"accent"}),{default:a(()=>[f("Zurücksetzen")]),_:2},1040,["color"])]),_:1})]),_:1}),e(i,{cols:"12",sm:"6",align:"right"},{default:a(()=>[e(_,null,{default:a(({isHovering:t,props:p})=>[e(o(P),{href:m.route("subdomains.show",{id:r.value.subdomain_id})},{default:a(()=>[e(w,g({class:"mr-2",variant:"text"},p,{color:t?"accent":"primary"}),{default:a(()=>[f("Zurück")]),_:2},1040,["color"])]),_:2},1032,["href"])]),_:1}),e(_,null,{default:a(({isHovering:t,props:p})=>[e(q,g({onClick:k},p,{color:t?"accent":"primary"}),{default:a(()=>[f("Speichern ")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1})])]),_:1},8,["errors"])],64)}}};export{H as default};
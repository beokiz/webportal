import{d as F}from"./index-5db9e5c8.js";import{K as E,k as f,h as N,T as S,r as u,o as T,f as j,a as e,u as s,w as a,F as z,Z as C,b as v,i as Z,p as V,d as x}from"./app-5d8db708.js";import{_ as A}from"./AuthenticatedLayout-592d12f5.js";import"./ApplicationLogo-478642f3.js";const M=v("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Benutzer verwalten",-1),O={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},R={class:"tw-flex tw-justify-between"},G={__name:"ManageUser",props:{user:Object,errors:Object,roles:Array,from:String},setup(w){const _=w;F.Inertia.on("success",t=>{let o=t.detail.page.props;t.detail.page.component==="Users/Partials/ManageUser"&&o&&(m.value=o.user)}),E().props.auth.user;const m=f(_.user),n=f(_.errors||{});f(!1);const y=N(()=>{if(_.from){const t=_.from.split(";");if(t.length===2)return route(t[0],{kita:t[1]})}return route("users.index")}),h=()=>{l.reset(),l.clearErrors(),n.value={}},l=S({id:m.value.id,first_name:m.value.first_name,last_name:m.value.last_name,email:m.value.email,role:m.value.primary_role_id,two_factor_auth_enabled:m.value.two_factor_auth_enabled}),U=async()=>{l.processing=!0,l.put(route("users.update",{user:l.id}),{preserveState:!1,onSuccess:t=>{h()},onError:t=>{n.value=t},onFinish:()=>{l.processing=!1}})};return(t,o)=>{const i=u("v-text-field"),d=u("v-col"),p=u("v-row"),k=u("v-select"),q=u("v-checkbox"),g=u("v-container"),B=u("v-btn"),b=u("v-hover"),P=u("v-btn-primary");return T(),j(z,null,[e(s(C),{title:"Benutzer verwalten"}),e(A,{errors:n.value},{header:a(()=>[M]),default:a(()=>[v("div",O,[e(g,null,{default:a(()=>[e(p,null,{default:a(()=>[e(d,{cols:"12",sm:"4"},{default:a(()=>[e(i,{modelValue:s(l).first_name,"onUpdate:modelValue":o[0]||(o[0]=r=>s(l).first_name=r),"error-messages":n.value.first_name,label:"Vorname",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(d,{cols:"12",sm:"4"},{default:a(()=>[e(i,{modelValue:s(l).last_name,"onUpdate:modelValue":o[1]||(o[1]=r=>s(l).last_name=r),"error-messages":n.value.last_name,label:"Nachname",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(d,{cols:"12",sm:"4"},{default:a(()=>[e(i,{modelValue:s(l).email,"onUpdate:modelValue":o[2]||(o[2]=r=>s(l).email=r),"error-messages":n.value.email,label:"Email",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(p,null,{default:a(()=>[e(d,{cols:"12",sm:"4"},{default:a(()=>[e(i,{type:"password",autocomplete:"new-password",modelValue:s(l).password,"onUpdate:modelValue":o[3]||(o[3]=r=>s(l).password=r),"error-messages":n.value.password,label:"Passwort",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(d,{cols:"12",sm:"4"},{default:a(()=>[e(i,{type:"password",modelValue:s(l).password_confirmation,"onUpdate:modelValue":o[4]||(o[4]=r=>s(l).password_confirmation=r),"error-messages":n.value.password_confirmation,label:"Passwort Bestätigung",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(d,{cols:"12",sm:"4"},{default:a(()=>[e(k,{disabled:t.$page.props.auth.user.id===m.value.id,modelValue:s(l).role,"onUpdate:modelValue":o[5]||(o[5]=r=>s(l).role=r),items:w.roles,"error-messages":n.value.role,"item-title":"human_name","item-value":"id",label:"Rolle",required:""},null,8,["disabled","modelValue","items","error-messages"])]),_:1})]),_:1}),e(p,null,{default:a(()=>[e(d,{cols:"12",sm:"4"},{default:a(()=>[e(q,{modelValue:s(l).two_factor_auth_enabled,"onUpdate:modelValue":o[6]||(o[6]=r=>s(l).two_factor_auth_enabled=r),label:"Zwei-Faktor-Authentifizierung",value:!0},null,8,["modelValue"])]),_:1})]),_:1})]),_:1}),e(g,null,{default:a(()=>[e(p,null,{default:a(()=>[e(d,{cols:"12",md:"3",sm:"4"},{default:a(()=>[v("div",R,[e(b,null,{default:a(({isHovering:r,props:c})=>[e(s(Z),{href:y.value},{default:a(()=>[e(B,V(c,{color:r?"primary":"accent"}),{default:a(()=>[x("Zurück")]),_:2},1040,["color"])]),_:2},1032,["href"])]),_:1}),e(b,null,{default:a(({isHovering:r,props:c})=>[e(P,V({onClick:U},c,{color:r?"accent":"primary"}),{default:a(()=>[x("Speichern")]),_:2},1040,["color"])]),_:1})])]),_:1})]),_:1})]),_:1})])]),_:1},8,["errors"])],64)}}};export{G as default};
import{d as B}from"./index-20068c0c.js";import{K as C,j as n,k as R,l as U,T as z,r as o,o as L,f as Z,a as e,u as c,w as t,F as q,Z as I,b as T,t as J,m as _,i as K,d as F}from"./app-5d4e6535.js";import{_ as A}from"./AuthenticatedLayout-2503e4bb.js";import{c as p}from"./common-ea192878.js";import"./ApplicationLogo-c6c6b36d.js";const G={class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},H={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},Q=T("h3",null,"Eigenschaften",-1),re={__name:"ManageSurveyTimePeriod",props:{surveyTimePeriod:Object,errors:Object,from:String},setup(f){const u=f;B.Inertia.on("success",l=>{let r=l.detail.page.props;l.detail.page.component==="SurveyTimePeriods/Partials/ManageSurveyTimePeriod"&&r&&(y.value=r.surveyTimePeriod)}),C().props.auth.user;const y=n(u.surveyTimePeriod),i=n(u.errors||{});n(!1);const g=n(!1),w=n(!1),b=n(p(u.surveyTimePeriod.survey_start_date)),x=n(p(u.surveyTimePeriod.survey_end_date)),m=n(new Date(u.surveyTimePeriod.survey_start_date).toString()),v=n(new Date(u.surveyTimePeriod.survey_end_date).toString()),M=R(()=>{if(u.from){const l=u.from.split(";");if(l.length===3){const r=l[0],s={};return s[l[1]]=l[2],route(r,s)}}return route("settings.index")});U(m,l=>{b.value=p(l)}),U(v,l=>{x.value=p(l)});const d=z({id:y.value.id,year:y.value.year,survey_start_date:null,survey_end_date:null}),N=async()=>{d.processing=!0,d.survey_start_date=new Date(m.value).toLocaleString(),d.survey_end_date=new Date(v.value).toLocaleString();let l={preserveState:!1,onSuccess:r=>{},onError:r=>{i.value=r},onFinish:()=>{d.processing=!1}};d.put(route("survey_time_periods.update",{id:d.id}),l)};return(l,r)=>{const s=o("v-col"),V=o("v-row"),k=o("v-container"),P=o("v-text-field"),h=o("v-date-picker"),E=o("v-menu"),D=o("v-locale-provider"),$=o("v-btn"),O=o("v-hover"),j=o("v-btn-primary");return L(),Z(q,null,[e(c(I),{title:`Verwalte Rückmeldezeitraum ${f.surveyTimePeriod.year}`},null,8,["title"]),e(A,{errors:i.value},{header:t(()=>[T("h2",G,"Verwalte Rückmeldezeitraum "+J(f.surveyTimePeriod.year),1)]),default:t(()=>[T("div",H,[e(k,null,{default:t(()=>[e(V,null,{default:t(()=>[e(s,{cols:"12"},{default:t(()=>[Q]),_:1})]),_:1})]),_:1}),e(k,null,{default:t(()=>[e(V,null,{default:t(()=>[e(s,{cols:"12",sm:"4"},{default:t(()=>[e(P,{modelValue:c(d).year,"onUpdate:modelValue":r[0]||(r[0]=a=>c(d).year=a),"error-messages":i.value.year,label:"Jahr*",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(s,{cols:"12",sm:"4"},{default:t(()=>[e(D,{locale:"de"},{default:t(()=>[e(E,{modelValue:g.value,"onUpdate:modelValue":r[3]||(r[3]=a=>g.value=a),"return-value":m.value,"close-on-content-click":!1},{activator:t(({props:a})=>[e(P,_({label:"Erhebungsbeginn*",class:"tw-cursor-pointer","model-value":b.value,"prepend-icon":"mdi-calendar",readonly:""},a,{"error-messages":i.value.survey_start_date}),null,16,["model-value","error-messages"])]),default:t(()=>[e(h,{"onUpdate:modelValue":[r[1]||(r[1]=a=>g.value=!1),r[2]||(r[2]=a=>m.value=a)],modelValue:m.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1}),e(s,{cols:"12",sm:"4"},{default:t(()=>[e(D,{locale:"de"},{default:t(()=>[e(E,{modelValue:w.value,"onUpdate:modelValue":r[6]||(r[6]=a=>w.value=a),"return-value":v.value,"close-on-content-click":!1},{activator:t(({props:a})=>[e(P,_({label:"Erhebungsende*",class:"tw-cursor-pointer","model-value":x.value,"prepend-icon":"mdi-calendar",readonly:""},a,{"error-messages":i.value.survey_end_date}),null,16,["model-value","error-messages"])]),default:t(()=>[e(h,{"onUpdate:modelValue":[r[4]||(r[4]=a=>w.value=!1),r[5]||(r[5]=a=>v.value=a)],modelValue:v.value},null,8,["modelValue"])]),_:1},8,["modelValue","return-value"])]),_:1})]),_:1})]),_:1}),e(V,null,{default:t(()=>[e(s,{cols:"12",sm:"12",align:"right"},{default:t(()=>[e(O,null,{default:t(({isHovering:a,props:S})=>[e(c(K),{href:M.value},{default:t(()=>[e($,_({class:"mr-2",variant:"text"},S,{color:a?"accent":"primary"}),{default:t(()=>[F("Zurück")]),_:2},1040,["color"])]),_:2},1032,["href"])]),_:1}),e(O,null,{default:t(({isHovering:a,props:S})=>[e(j,_({onClick:N},S,{color:a?"accent":"primary"}),{default:t(()=>[F("Speichern ")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1})])]),_:1},8,["errors"])],64)}}};export{re as default};
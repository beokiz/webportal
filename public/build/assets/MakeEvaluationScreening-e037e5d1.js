import{d as H}from"./index-703871be.js";import{K as J,k as u,s as L,T as Q,m as W,r as n,f as C,a as e,u as l,w as a,F as X,o as d,Z as Y,b as p,c as h,g as k,p as y,d as D,t as ee,v as ae}from"./app-4d8acc34.js";import{p as V,a as te}from"./common-1f9d3448.js";import{_ as oe}from"./AuthenticatedLayout-ffb4b768.js";import{_ as S}from"./EvaluationDomainsList-eccf3aa7.js";import"./ApplicationLogo-b9f180b5.js";const ne=p("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Screening-prüfung",-1),se={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},le=p("h3",null,"Eigenschaften",-1),re={key:0,class:"tw-flex justify-center items-center tw-bg-white"},ce={class:"tw-text-center"},ie={class:"tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8"},ve={__name:"MakeEvaluationScreening",props:{domains:Array,dazDependent:{type:Boolean,default:!1},errors:Object},setup(g){const v=g;H.Inertia.on("success",o=>{o.detail.page.props,o.detail.page.component}),J().props.auth.user;const r=u(v.errors||{}),b=u(!1),B=u([]),f=u(!1),E=u(null),R=u(null);L(()=>{U()});const A=o=>{t.ratings=o},N=o=>{B.value=ae(o)?o.value:o},U=()=>{t.ratings=V(v.domains)},F=()=>{r.value={},f.value=!1},I=()=>{t.reset(),t.clearErrors(),r.value={},t.ratings=V(v.domains)},t=Q({age:null,is_daz:!1,ratings:[]});W(()=>t.age,o=>{t.ratings=V(v.domains)});const j=async()=>{t.processing=!0,b.value=!0,t.post(route("screening.make"),{onSuccess:o=>{t.clearErrors(),r.value={},f.value=!0,E.value=o.props.data.evaluation,R.value=o.props.data.domain.name},onError:o=>{r.value=o},onFinish:()=>{t.processing=!1,b.value=!1}})};return(o,i)=>{const c=n("v-col"),m=n("v-row"),w=n("v-container"),K=n("v-select"),M=n("v-checkbox"),T=n("v-progress-circular"),Z=n("v-btn"),x=n("v-hover"),z=n("v-btn-primary"),$=n("v-card-text"),O=n("v-spacer"),P=n("v-card-actions"),q=n("v-card"),G=n("v-dialog");return d(),C(X,null,[e(l(Y),{title:"Screening-prüfung"}),e(oe,{errors:r.value},{header:a(()=>[ne]),default:a(()=>[p("div",se,[e(w,null,{default:a(()=>[e(m,null,{default:a(()=>[e(c,{cols:"12"},{default:a(()=>[le]),_:1})]),_:1})]),_:1}),e(w,null,{default:a(()=>[e(m,null,{default:a(()=>[e(c,{cols:"12",sm:"3"},{default:a(()=>[e(K,{modelValue:l(t).age,"onUpdate:modelValue":i[0]||(i[0]=s=>l(t).age=s),items:l(te),"error-messages":r.value.age,"item-title":"age_name","item-value":"age_number",label:"Altersgruppe"},null,8,["modelValue","items","error-messages"])]),_:1}),g.dazDependent?(d(),h(c,{key:0,cols:"12",sm:"3"},{default:a(()=>[e(M,{modelValue:l(t).is_daz,"onUpdate:modelValue":i[1]||(i[1]=s=>l(t).is_daz=s),label:"Deutsch ist nicht Erstsprache des Kindes"},null,8,["modelValue"])]),_:1})):k("",!0)]),_:1}),e(m,{class:"manage-evaluation-domains"},{default:a(()=>[l(t).age?(d(),h(S,{key:0,ratings:l(t).ratings,age:l(t).age,domains:g.domains,errors:r.value,onUpdateRatingData:A,onUpdateDomainsData:N},null,8,["ratings","age","domains","errors"])):k("",!0)]),_:1})]),_:1}),e(w,null,{default:a(()=>[b.value?(d(),C("div",re,[e(T,{indeterminate:"",size:40})])):(d(),h(m,{key:1},{default:a(()=>[e(c,{cols:"12",sm:"6"},{default:a(()=>[e(x,null,{default:a(({isHovering:s,props:_})=>[e(Z,y({onClick:I},_,{color:s?"primary":"accent"}),{default:a(()=>[D(" Zurücksetzen ")]),_:2},1040,["color"])]),_:1})]),_:1}),e(c,{cols:"12",sm:"6",align:"right"},{default:a(()=>[l(t).age&&B.value.length>0?(d(),h(x,{key:0},{default:a(({isHovering:s,props:_})=>[e(z,y({onClick:j},_,{color:s?"accent":"primary"}),{default:a(()=>[D(" Ampel-Bewertung ")]),_:2},1040,["color"])]),_:1})):k("",!0)]),_:1})]),_:1}))]),_:1})]),e(G,{modelValue:f.value,"onUpdate:modelValue":i[2]||(i[2]=s=>f.value=s),width:"95vw"},{default:a(()=>[e(q,{height:"95vh"},{default:a(()=>[e($,null,{default:a(()=>[e(w,null,{default:a(()=>[e(m,{class:"result-evaluation-domains"},{default:a(()=>[e(c,{cols:"8",offset:"2"},{default:a(()=>[p("div",ce,[p("h1",ie," Ampelergebnis im Bereich "+ee(R.value),1)])]),_:1}),e(c,{cols:"12"},{default:a(()=>[e(S,{ratings:E.value,age:l(t).age,domains:g.domains,disabled:!0},null,8,["ratings","age","domains"])]),_:1})]),_:1})]),_:1})]),_:1}),e(P,null,{default:a(()=>[e(O),e(x,null,{default:a(({isHovering:s,props:_})=>[e(z,y({onClick:F},_,{color:s?"accent":"primary"}),{default:a(()=>[D("Abbrechen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["errors"])],64)}}};export{ve as default};
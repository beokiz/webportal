import{d as a}from"./index-d0126029.js";import{K as p,o as n,f as i,a as r,u as m,w as s,F as l,Z as u,b as e}from"./app-584065c0.js";import{_ as c}from"./AuthenticatedLayout-cac665a1.js";import"./ApplicationLogo-fcfd82b2.js";const d=e("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Impressum und Support",-1),_={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},w=["innerHTML"],b={__name:"ImprintAndSupport",props:{imprintSupportHtml:String},setup(o){return a.Inertia.on("success",t=>{t.detail.page.props,t.detail.page.component}),p().props.auth.user,(t,f)=>(n(),i(l,null,[r(m(u),{title:"Impressum und Support"}),r(c,{errors:t.errors},{header:s(()=>[d]),default:s(()=>[e("div",_,[e("div",{innerHTML:o.imprintSupportHtml},null,8,w)])]),_:1},8,["errors"])],64))}};export{b as default};
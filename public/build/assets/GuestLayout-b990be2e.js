import{A as d}from"./ApplicationLogo-ed6b7a78.js";import{o as a,f as o,b as w,g as n,u as t,a as l,w as m,i as u,p as f,q as h}from"./app-3f6ccddf.js";const g={class:"tw-min-h-screen tw-flex tw-flex-col sm:tw-justify-center tw-items-center tw-pt-6 sm:tw-pt-0 tw-bg-gray-100"},p={key:0,class:"tw-w-full sm:tw-max-w-[700px] tw-mb-6 tw-px-6 tw-py-4 tw-bg-white tw-shadow-md tw-overflow-hidden sm:tw-rounded-lg"},x=["innerHTML"],_={key:1},H={__name:"GuestLayout",props:{additionalHtml:String},setup(r){const e=route().current("auth.register"),s=route().current("verification.notice"),i=route().current("verification.verified_notice");return(c,v)=>(a(),o("div",g,[r.additionalHtml?(a(),o("div",p,[w("div",{class:"guest-layout-custom-html",innerHTML:r.additionalHtml},null,8,x)])):n("",!0),!t(e)&&!t(s)&&!t(i)?(a(),o("div",_,[l(t(u),{href:"/"},{default:m(()=>[l(d,{class:"tw-h-20 tw-fill-current tw-text-gray-500"})]),_:1})])):n("",!0),w("div",{class:h(["tw-w-full tw-mt-6 tw-px-6 tw-py-4 tw-bg-white tw-shadow-md tw-overflow-hidden sm:tw-rounded-lg",{"sm:tw-max-w-md":!t(e)&&!t(s)&&!t(i),"sm:tw-max-w-7xl":t(e)||t(s),"sm:tw-max-w-3xl":t(i)}])},[f(c.$slots,"default")],2)]))}};export{H as _};
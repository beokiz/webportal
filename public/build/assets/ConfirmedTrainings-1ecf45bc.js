import{r,o as a,c as m,w as t,a as e,u as l,Z as h,i as w,f as o,b as c,F as f,g,t as p}from"./app-584065c0.js";import{_ as b}from"./GuestLayout-e20231e0.js";import{A as v}from"./ApplicationLogo-fcfd82b2.js";import{f as d}from"./common-ea192878.js";const x=c("h2",{class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4"}," Vielen Dank! ",-1),y=c("p",{class:"tw-mb-4"}," Wir haben Ihre Zusage für die Schulung an folgenden Tagen erfolgreich erhalten: ",-1),k={class:"tw-list-disc tw-ml-5"},D={key:1,class:"tw-mb-4"},$={__name:"ConfirmedTrainings",props:{trainingItems:Array},setup(n){return(B,E)=>{const u=r("v-col"),i=r("v-row"),_=r("v-container");return a(),m(b,null,{default:t(()=>[e(l(h),{title:"E-Mail bestätigt"}),e(_,null,{default:t(()=>[e(i,null,{default:t(()=>[e(u,{cols:"12",class:"tw-flex tw-items-center tw-justify-center tw-mb-8"},{default:t(()=>[e(l(w),{href:"/"},{default:t(()=>[e(v,{class:"tw-h-20 tw-fill-current tw-text-gray-500"})]),_:1})]),_:1})]),_:1})]),_:1}),e(_,null,{default:t(()=>[e(i,null,{default:t(()=>[e(u,{cols:"12",class:"tw-mb-8"},{default:t(()=>[x,n.trainingItems&&n.trainingItems.length>0?(a(),o(f,{key:0},[y,c("ul",null,[(a(!0),o(f,null,g(n.trainingItems,s=>(a(),o("li",k,p(`${l(d)(s==null?void 0:s.first_date,"de-DE")} und ${l(d)(s==null?void 0:s.second_date,"de-DE")}`),1))),256))])],64)):(a(),o("p",D," Wir haben Ihre Schulungszusage erfolgreich erhalten. "))]),_:1})]),_:1})]),_:1})]),_:1})}}};export{$ as default};
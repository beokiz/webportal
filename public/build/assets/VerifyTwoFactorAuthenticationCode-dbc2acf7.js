import{T as w,r as c,c as p,w as n,o as d,a as o,u as t,Z as h,b as s,d as r,i as b,f as v,t as x,g as V,e as g}from"./app-01a54515.js";import{_ as y}from"./GuestLayout-79e1c418.js";import"./ApplicationLogo-02bc5262.js";const S={class:"tw-mb-4 tw-text-sm tw-text-gray-600"},k={key:0,class:"tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600"},C=["onSubmit"],B={class:"tw-flex tw-items-center tw-justify-end mt-4"},A={__name:"VerifyTwoFactorAuthenticationCode",props:{request:Object,status:String},setup(a){const e=w({two_factor_code:a.request.two_factor_code}),u=()=>{e.post(route("2fa.verify"),{onFinish:()=>e.reset("two_factor_code")})};return(m,i)=>{const l=c("v-text-field"),f=c("v-btn-primary");return d(),p(y,null,{default:n(()=>[o(t(h),{title:"Zwei-Faktor-Authentifizierung"}),s("div",S,[r(" Sie haben eine E-Mail erhalten, die einen 2FA-Verifizierungscode enthält. Wenn Sie sie nicht erhalten haben, drücken Sie "),o(t(b),{href:m.route("2fa.resend"),method:"post",as:"button"},{default:n(()=>[r("hier")]),_:1},8,["href"]),r(". ")]),a.status?(d(),v("div",k,x(a.status),1)):V("",!0),s("form",{onSubmit:g(u,["prevent"])},[s("div",null,[o(l,{modelValue:t(e).two_factor_code,"onUpdate:modelValue":i[0]||(i[0]=_=>t(e).two_factor_code=_),autofocus:"","error-messages":t(e).errors.two_factor_code,label:"Code",required:""},null,8,["modelValue","error-messages"])]),s("div",B,[o(f,{type:"submit"},{default:n(()=>[r("Verifizieren")]),_:1})])],40,C)]),_:1})}}};export{A as default};
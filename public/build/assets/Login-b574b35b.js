import{T as h,r as d,o as l,c as i,w as m,a,u as e,Z as v,f as k,t as x,g as u,b as o,i as p,d as c,e as y}from"./app-2811682d.js";import{_ as V}from"./GuestLayout-158a21b6.js";import"./ApplicationLogo-3ecea417.js";const B={key:0,class:"tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600"},N=["onSubmit"],P={class:"mt-4"},R={class:"tw-block tw-mt-4"},S={class:"tw-flex tw-items-center tw-justify-end mt-4"},U={__name:"Login",props:{canResetPassword:Boolean,canRegister:Boolean,status:String},setup(n){const t=h({email:"",password:"",remember:!1}),b=()=>{t.post(route("auth.login"),{onFinish:()=>t.reset("password")})};return(w,s)=>{const f=d("v-text-field"),g=d("v-checkbox"),_=d("v-btn-primary");return l(),i(V,null,{default:m(()=>[a(e(v),{title:"Anmeldung"}),n.status?(l(),k("div",B,x(n.status),1)):u("",!0),o("form",{onSubmit:y(b,["prevent"])},[o("div",null,[a(f,{autocomplete:"username",modelValue:e(t).email,"onUpdate:modelValue":s[0]||(s[0]=r=>e(t).email=r),"error-messages":e(t).errors.email,label:"Email",required:""},null,8,["modelValue","error-messages"])]),o("div",P,[a(f,{type:"password",autocomplete:"current-password",modelValue:e(t).password,"onUpdate:modelValue":s[1]||(s[1]=r=>e(t).password=r),"error-messages":e(t).errors.password,label:"Passwort",required:""},null,8,["modelValue","error-messages"])]),o("div",R,[a(g,{label:"Angemeldet bleiben",checked:e(t).remember,"onUpdate:checked":s[2]||(s[2]=r=>e(t).remember=r)},null,8,["checked"])]),o("div",S,[o("div",null,[n.canResetPassword?(l(),i(e(p),{key:0,href:w.route("password.request"),class:"tw-block tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none"},{default:m(()=>[c(" Passwort vergessen? ")]),_:1},8,["href"])):u("",!0),n.canRegister?(l(),i(e(p),{key:1,href:w.route("auth.register"),class:"tw-block tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none"},{default:m(()=>[c(" Noch kein Konto? ")]),_:1},8,["href"])):u("",!0)]),a(_,{type:"submit",class:"tw-ml-4"},{default:m(()=>[c("Anmelden")]),_:1})])],40,N)]),_:1})}}};export{U as default};
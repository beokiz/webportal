import{d as W}from"./index-351153f0.js";import{o as p,f as h,s as $,b as o,t as k,v as B,F as D,q as X,x as G,K as J,k as R,y as Q,l as Y,T as ee,r as c,a as t,u as v,w as n,Z as te,p as I,d as U}from"./app-a335d2ee.js";import{a as ne}from"./common-ba477221.js";import{_ as ae}from"./AuthenticatedLayout-e27e28a1.js";import"./ApplicationLogo-89424e05.js";let A;const le=new Uint8Array(16);function se(){if(!A&&(A=typeof crypto<"u"&&crypto.getRandomValues&&crypto.getRandomValues.bind(crypto),!A))throw new Error("crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported");return A(le)}const i=[];for(let e=0;e<256;++e)i.push((e+256).toString(16).slice(1));function oe(e,a=0){return i[e[a+0]]+i[e[a+1]]+i[e[a+2]]+i[e[a+3]]+"-"+i[e[a+4]]+i[e[a+5]]+"-"+i[e[a+6]]+i[e[a+7]]+"-"+i[e[a+8]]+i[e[a+9]]+"-"+i[e[a+10]]+i[e[a+11]]+i[e[a+12]]+i[e[a+13]]+i[e[a+14]]+i[e[a+15]]}const re=typeof crypto<"u"&&crypto.randomUUID&&crypto.randomUUID.bind(crypto),j={randomUUID:re};function ie(e,a,b){if(j.randomUUID&&!a&&!e)return j.randomUUID();e=e||{};const _=e.random||(e.rng||se)();if(_[6]=_[6]&15|64,_[8]=_[8]&63|128,a){b=b||0;for(let u=0;u<16;++u)a[b+u]=_[u];return a}return oe(_)}const de={class:"subdomains-list-head"},ue={class:"radio-wrap radio-head"},ce={class:"radio-wrap radio-content"},me=["onUpdate:modelValue","name","value","disabled"],P={__name:"EvaluationDomainsList",props:{domains:Array,ratings:Array,errors:{type:Object,default:{}},disabled:{type:Boolean,default:!1}},setup(e,{emit:a}){const b=_=>{a("updateRatingData",_)};return(_,u)=>(p(!0),h(D,null,$(e.domains,(y,f)=>(p(),h("div",{class:"domains-list-container",key:y.id},[o("h3",{class:B({green:e.ratings[f].color==="green",yellow:e.ratings[f].color==="yellow",red:e.ratings[f].color==="red"})},k(y.name),3),(p(!0),h(D,null,$(y.subdomains,V=>(p(),h("div",{class:"subdomains-list-container",key:V.id},[o("div",de,[o("h4",null,k(V.name),1),(p(),h(D,null,$(["Noch Nicht","Ansatzweise","Weitgehend","Zuverlassig"],l=>o("div",ue,[o("span",null,k(l),1)])),64))]),(p(!0),h(D,null,$(V.milestones,l=>(p(),h("div",{class:"milestone-list-container",key:l.id},[o("h5",{class:B({error:e.errors[`ratings.${l.domain_index}.milestones.${l.index}.value`]})},k(l.abbreviation),3),o("div",{class:B(["milestone-list-text",{error:e.errors[`ratings.${l.domain_index}.milestones.${l.index}.value`]}])},[o("span",null,k(l.title),1),o("p",null,k(l.text),1)],2),o("fieldset",{class:B({error:!e.disabled&&e.errors[`ratings.${l.domain_index}.milestones.${l.index}.value`]})},[(p(),h(D,null,$([1,2,3,4],z=>o("div",ce,[X(o("input",{type:"radio","onUpdate:modelValue":s=>e.ratings[l.domain_index].milestones[l.index].value=s,name:l.id+"check-radio",value:z,disabled:e.disabled,onInput:u[0]||(u[0]=s=>b(e.ratings))},null,40,me),[[G,e.ratings[l.domain_index].milestones[l.index].value]])])),64))],2)]))),128))]))),128))]))),128))}},ge=o("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Auswertung erstellen",-1),_e={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},ve=o("h3",null,"Eigenschaften",-1),pe={class:"tw-text-center"},he=o("h1",{class:"tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8"}," Screening wurde eingereicht ",-1),fe=o("p",{class:"tw-mb-8"}," Folgendes Screening wurde eingereicht und kann nur bis 15 Minuten nach Einreichung bearbeitet werden. Danach verschwindet es aus Ihrer Übersicht. Sollten Sie es zurückziehen oder bearbeiten wollen, so klicken Sie auf das ‘X oben rechts und dann auf den entsprechenden Button in der Detailansicht des Screenings. Nachfolgend erhalten Sie eine Übersicht des eingereichten Screenings, welches Sie über den Download-Button als PDF herunterladen können. ",-1),we=o("span",{class:"tw-font-black"},"Bezeichner des Screenings",-1),Ue={__name:"CreateEvaluation",props:{errors:Object,kitas:Array,domains:Array},setup(e){const a=e;W.Inertia.on("success",d=>{d.detail.page.props,d.detail.page.component});const b=J().props.auth.user??{},_=R(null),u=R(a.errors||{});R(!1);const y=R(!1),f=R(null);Q(()=>{l()}),Y(()=>{V()});const V=()=>{_.value=ie(),s.uuid=_.value},l=()=>{let d=[];a.domains.forEach(function(m,g){d[g]={domain:m.id,milestones:[]},m.subdomains.forEach(function(x,S){x.milestones.forEach(function(C,F){d[g].milestones.push({id:C.id,value:null})})})}),s.ratings=d},z=d=>{s.ratings=d},s=ee({age:null,uuid:null,is_daz:!1,user_id:b.id,kita_id:null,ratings:[]}),T=async()=>{s.processing=!0,s.post(route("evaluations.store"),{onSuccess:d=>{s.reset(),s.clearErrors(),V(),u.value={},l(),y.value=!0,f.value=d.props.data},onError:d=>{u.value=d},onFinish:()=>{s.processing=!1}})};return(d,m)=>{const g=c("v-col"),x=c("v-row"),S=c("v-container"),C=c("v-text-field"),F=c("v-select"),q=c("v-checkbox"),N=c("v-btn"),E=c("v-hover"),M=c("v-btn-primary"),K=c("v-card-text"),L=c("v-spacer"),O=c("v-card-actions"),Z=c("v-card"),H=c("v-dialog");return p(),h(D,null,[t(v(te),{title:"Auswertung erstellen"}),t(ae,{errors:u.value},{header:n(()=>[ge]),default:n(()=>[o("div",_e,[t(S,null,{default:n(()=>[t(x,null,{default:n(()=>[t(g,{cols:"12"},{default:n(()=>[ve]),_:1})]),_:1})]),_:1}),t(S,null,{default:n(()=>[t(x,null,{default:n(()=>[t(g,{cols:"12",sm:"4"},{default:n(()=>[t(C,{modelValue:v(s).uuid,"onUpdate:modelValue":m[0]||(m[0]=r=>v(s).uuid=r),"error-messages":u.value.uuid,readonly:"",label:"Bezeichner der Einschatzung",required:""},null,8,["modelValue","error-messages"])]),_:1}),t(g,{cols:"12",sm:"3"},{default:n(()=>[t(F,{modelValue:v(s).age,"onUpdate:modelValue":m[1]||(m[1]=r=>v(s).age=r),items:v(ne),"error-messages":u.value.age,"item-title":"age_name","item-value":"age_number",label:"Altersgruppe"},null,8,["modelValue","items","error-messages"])]),_:1}),t(g,{cols:"12",sm:"3"},{default:n(()=>[t(F,{modelValue:v(s).kita_id,"onUpdate:modelValue":m[2]||(m[2]=r=>v(s).kita_id=r),items:e.kitas,"error-messages":u.value.kita_id,"item-title":"name","item-value":"id",label:"Kita"},null,8,["modelValue","items","error-messages"])]),_:1}),t(g,{cols:"12",sm:"2"},{default:n(()=>[t(q,{modelValue:v(s).is_daz,"onUpdate:modelValue":m[3]||(m[3]=r=>v(s).is_daz=r),label:"Ist Daz"},null,8,["modelValue"])]),_:1})]),_:1}),t(x,null,{default:n(()=>[t(P,{onUpdateRatingData:z,ratings:v(s).ratings,domains:e.domains,errors:u.value},null,8,["ratings","domains","errors"])]),_:1})]),_:1}),t(S,null,{default:n(()=>[t(x,null,{default:n(()=>[t(g,{cols:"12",sm:"6"},{default:n(()=>[t(E,null,{default:n(({isHovering:r,props:w})=>[t(N,I({onClick:d.clear},w,{color:r?"primary":"accent"}),{default:n(()=>[U("Back")]),_:2},1040,["onClick","color"])]),_:1})]),_:1}),t(g,{cols:"12",sm:"6",align:"right"},{default:n(()=>[t(E,null,{default:n(({isHovering:r,props:w})=>[t(N,I({class:"mr-2",variant:"text"},w,{color:r?"accent":"primary"}),{default:n(()=>[U("Save")]),_:2},1040,["color"])]),_:1}),t(E,null,{default:n(({isHovering:r,props:w})=>[t(M,I({onClick:T},w,{color:r?"accent":"primary"}),{default:n(()=>[U("Big save ")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1})]),t(H,{modelValue:y.value,"onUpdate:modelValue":m[4]||(m[4]=r=>y.value=r),width:"95vw"},{default:n(()=>[t(Z,{height:"95vh"},{default:n(()=>[t(K,null,{default:n(()=>[t(S,null,{default:n(()=>[t(x,null,{default:n(()=>[t(g,{cols:"8",offset:"2"},{default:n(()=>[o("div",pe,[he,fe,t(E,null,{default:n(({isHovering:r,props:w})=>[t(N,{href:d.route("evaluations.pdf",{id:f.value.item.id}),class:"tw-px-2 tw-py-3 tw-mb-4 tw-normal-case",color:r?"primary":"accent"},{default:n(()=>[U(" Screening als PDF downloaden ")]),_:2},1032,["href","color"])]),_:1})])]),_:1}),t(g,{cols:"12"},{default:n(()=>[o("p",null,[we,U(": "+k(f.value.item.uuid),1)])]),_:1}),t(g,{cols:"12"},{default:n(()=>[t(P,{onUpdateRatingData:z,ratings:f.value.item.data,domains:e.domains,disabled:!0},null,8,["ratings","domains"])]),_:1})]),_:1})]),_:1})]),_:1}),t(O,null,{default:n(()=>[t(L),t(E,null,{default:n(({isHovering:r,props:w})=>[t(M,I({onClick:d.close},w,{color:r?"accent":"primary"}),{default:n(()=>[U("Abbrechen")]),_:2},1040,["onClick","color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["errors"])],64)}}};export{Ue as default};
import{d as W}from"./index-7f365590.js";import{K as G,k as w,T as Q,h as U,m as M,r as s,f as z,a as e,u as _,w as a,F as X,o as h,Z as ee,b as p,t as g,c as K,g as v,i as ae,p as A,d as x}from"./app-a86e01ca.js";import{_ as le}from"./AuthenticatedLayout-853530bf.js";import{W as E}from"./WarningEvaluationTooltip-6ee16b02.js";import"./ApplicationLogo-db7c023a.js";const te={class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},re={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},ne=p("h3",null,"Rückmeldung betrifft",-1),_e=p("h3",null,"Angaben zur Rückmeldung",-1),oe=p("h5",null,"Kinder bis 2,5 Jahre",-1),ie=p("h5",null,"Kinder bis 4,5 Jahre",-1),se=p("h4",null,"Wir haben eine Abweichung zwischen der Anzahl der von Ihnen gemeldeten Kinder und der Anzahl über die BeoKiz-Ampel erfassten Kindern festgestellt:",-1),ue={class:"tw-flex tw-items-center tw-mt-2"},de={style:{"list-style-type":"disc","margin-left":"25px"}},me={key:0},ce={key:1},he={key:2},ge={key:3},be={__name:"ManageYearlyEvaluation",props:{yearlyEvaluation:Object,errors:Object,kitas:Array,surveyTimePeriods:Array},setup(V){const f=V;W.Inertia.on("success",o=>{let t=o.detail.page.props;o.detail.page.component==="YearlyEvaluations/Partials/ManageYearlyEvaluation"&&t&&(u.value=t.yearlyEvaluation)}),G().props.auth.user;const u=w(f.yearlyEvaluation),d=w(f.errors||{});w(!1);const b=w(!1),B=w(!1),D=w(!1),l=Q({id:u.value.id,year:u.value.year,kita_id:u.value.kita_id,children_2_born_per_year:u.value.children_2_born_per_year,children_2_with_german_lang:u.value.children_2_with_german_lang,evaluations_with_daz_2_total_per_year:u.value.evaluations_with_daz_2_total_per_year,children_2_with_foreign_lang:u.value.children_2_with_foreign_lang,evaluations_without_daz_2_total_per_year:u.value.evaluations_without_daz_2_total_per_year,children_4_born_per_year:u.value.children_4_born_per_year,children_4_with_german_lang:u.value.children_4_with_german_lang,evaluations_with_daz_4_total_per_year:u.value.evaluations_with_daz_4_total_per_year,children_4_with_foreign_lang:u.value.children_4_with_foreign_lang,evaluations_without_daz_4_total_per_year:u.value.evaluations_without_daz_4_total_per_year});U(()=>f.yearlyEvaluation.map(o=>{const t={...o};for(const n in t)(t[n]===null||t[n]===void 0)&&(t[n]="-");return t}));const P=U(()=>Y("2.5")),O=U(()=>Y("4.5"));M(b,o=>{B.value=!0}),M(()=>l.kita_id,o=>{let t=f.kitas.find(n=>o===parseInt(n.id));t&&(l.evaluations_with_daz_2_total_per_year=t.evaluations_with_daz2_total_per_year_count,l.evaluations_with_daz_4_total_per_year=t.evaluations_with_daz4_total_per_year_count,l.evaluations_without_daz_2_total_per_year=t.evaluations_without_daz2_total_per_year_count,l.evaluations_without_daz_4_total_per_year=t.evaluations_without_daz4_total_per_year_count)});const q=()=>{b.value=!1},T=async()=>{(c("2_german")||c("2_foreign")||c("4_german")||c("4_foreign"))&&!B.value?b.value=!0:await R()},c=o=>{if(o==="2_german")return parseInt(l.evaluations_with_daz_2_total_per_year)!==parseInt(l.children_2_with_german_lang);if(o==="2_foreign")return parseInt(l.evaluations_without_daz_2_total_per_year)!==parseInt(l.children_2_with_foreign_lang);if(o==="4_german")return parseInt(l.evaluations_with_daz_4_total_per_year)!==parseInt(l.children_4_with_german_lang);if(o==="4_foreign")return parseInt(l.evaluations_without_daz_4_total_per_year)!==parseInt(l.children_4_with_foreign_lang)},R=async()=>{l.processing=!0;let o={preserveState:!0,onSuccess:t=>{},onError:t=>{d.value=t},onFinish:()=>{q(),l.processing=!1}};l.put(route("yearly_evaluations.update",{id:l.id}),o)},Y=o=>{if(o==="2.5"||o==="4.5"){let t=f.surveyTimePeriods.find(n=>n.year===parseInt(l.year)&&n.age==="4.5");if(t){let n=new Date(t.survey_start_date),i=new Date(t.survey_end_date),S=n.getFullYear()+"-"+("0"+(n.getMonth()+1)).slice(-2)+"-"+("0"+n.getDate()).slice(-2),m=i.getFullYear()+"-"+("0"+(i.getMonth()+1)).slice(-2)+"-"+("0"+i.getDate()).slice(-2);return`Gesamtzahl der im Zeitraum ${S} - ${m} geborenen Kinder`}}return"Gesamtzahl der Kinder"};return(o,t)=>{const n=s("v-col"),i=s("v-text-field"),S=s("v-select"),m=s("v-row"),I=s("v-card"),F=s("v-btn"),k=s("v-hover"),J=s("v-btn-primary"),C=s("v-container"),H=s("v-icon"),N=s("v-checkbox"),Z=s("v-card-text"),$=s("v-spacer"),j=s("v-card-actions"),L=s("v-dialog");return h(),z(X,null,[e(_(ee),{title:`Verwalte Jährliche Rückmeldung ${V.yearlyEvaluation.year}`},null,8,["title"]),e(le,{errors:d.value},{header:a(()=>[p("h2",te,"Jährliche Rückmeldung "+g(V.yearlyEvaluation.year),1)]),default:a(()=>[p("div",re,[e(C,null,{default:a(()=>[e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"12"},{default:a(()=>[ne]),_:1}),e(n,{cols:"12",sm:"4"},{default:a(()=>[e(i,{modelValue:_(l).year,"onUpdate:modelValue":t[0]||(t[0]=r=>_(l).year=r),"error-messages":d.value.year,label:"Jahr der Rückmeldung*",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"4"},{default:a(()=>[e(S,{modelValue:_(l).kita_id,"onUpdate:modelValue":t[1]||(t[1]=r=>_(l).kita_id=r),items:V.kitas,"error-messages":d.value.kita_id,"item-title":"name","item-value":"id",label:"Kita*"},null,8,["modelValue","items","error-messages"])]),_:1})]),_:1}),e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"12"},{default:a(()=>[_e]),_:1}),e(n,{cols:"12",sm:"6"},{default:a(()=>[e(I,{class:"tw-p-6"},{default:a(()=>[oe,e(i,{modelValue:_(l).children_2_born_per_year,"onUpdate:modelValue":t[2]||(t[2]=r=>_(l).children_2_born_per_year=r),"error-messages":d.value.children_2_born_per_year,label:P.value,type:"number",required:""},null,8,["modelValue","error-messages","label"]),e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"7"},{default:a(()=>[e(i,{modelValue:_(l).children_2_with_german_lang,"onUpdate:modelValue":t[3]||(t[3]=r=>_(l).children_2_with_german_lang=r),"error-messages":d.value.children_2_with_german_lang,label:"Kinder mit deutscher Herkunftssprache*",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"5"},{default:a(()=>[e(m,null,{default:a(()=>[c("2_german")?(h(),K(E,{key:0})):v("",!0),e(n,{cols:"12",sm:"10"},{default:a(()=>[e(i,{modelValue:_(l).evaluations_with_daz_2_total_per_year,"onUpdate:modelValue":t[4]||(t[4]=r=>_(l).evaluations_with_daz_2_total_per_year=r),"error-messages":d.value.evaluations_with_daz_2_total_per_year,label:"Bisher eingereichte Einschätzungen",type:"number",disabled:"",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1})]),_:1})]),_:1}),e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"7"},{default:a(()=>[e(i,{modelValue:_(l).children_2_with_foreign_lang,"onUpdate:modelValue":t[5]||(t[5]=r=>_(l).children_2_with_foreign_lang=r),"error-messages":d.value.children_2_with_foreign_lang,label:"Kinder mit nicht deutscher Herkunftssprache*",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"5"},{default:a(()=>[e(m,null,{default:a(()=>[c("2_foreign")?(h(),K(E,{key:0})):v("",!0),e(n,{cols:"12",sm:"10"},{default:a(()=>[e(i,{modelValue:_(l).evaluations_without_daz_2_total_per_year,"onUpdate:modelValue":t[6]||(t[6]=r=>_(l).evaluations_without_daz_2_total_per_year=r),"error-messages":d.value.evaluations_without_daz_2_total_per_year,label:"Bisher eingereichte Einschätzungen",type:"number",disabled:"",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),e(n,{cols:"12",sm:"6"},{default:a(()=>[e(I,{class:"tw-p-6"},{default:a(()=>[ie,e(i,{modelValue:_(l).children_4_born_per_year,"onUpdate:modelValue":t[7]||(t[7]=r=>_(l).children_4_born_per_year=r),"error-messages":d.value.children_4_born_per_year,label:O.value,type:"number",required:""},null,8,["modelValue","error-messages","label"]),e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"7"},{default:a(()=>[e(i,{modelValue:_(l).children_4_with_german_lang,"onUpdate:modelValue":t[8]||(t[8]=r=>_(l).children_4_with_german_lang=r),"error-messages":d.value.children_4_with_german_lang,label:"Kinder mit deutscher Herkunftssprache*",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"5"},{default:a(()=>[e(m,null,{default:a(()=>[c("4_german")?(h(),K(E,{key:0})):v("",!0),e(n,{cols:"12",sm:"10"},{default:a(()=>[e(i,{modelValue:_(l).evaluations_with_daz_4_total_per_year,"onUpdate:modelValue":t[9]||(t[9]=r=>_(l).evaluations_with_daz_4_total_per_year=r),"error-messages":d.value.evaluations_with_daz_4_total_per_year,label:"Bisher eingereichte Einschätzungen",type:"number",disabled:"",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1})]),_:1})]),_:1}),e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"7"},{default:a(()=>[e(i,{modelValue:_(l).children_4_with_foreign_lang,"onUpdate:modelValue":t[10]||(t[10]=r=>_(l).children_4_with_foreign_lang=r),"error-messages":d.value.children_4_with_foreign_lang,label:"Kinder mit nicht deutscher Herkunftssprache*",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(n,{cols:"12",sm:"5"},{default:a(()=>[e(m,null,{default:a(()=>[c("4_foreign")?(h(),K(E,{key:0})):v("",!0),e(n,{cols:"12",sm:"10"},{default:a(()=>[e(i,{modelValue:_(l).evaluations_without_daz_4_total_per_year,"onUpdate:modelValue":t[11]||(t[11]=r=>_(l).evaluations_without_daz_4_total_per_year=r),"error-messages":d.value.evaluations_without_daz_4_total_per_year,label:"Bisher eingereichte Einschätzungen",type:"number",disabled:"",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),e(m,null,{default:a(()=>[e(n,{cols:"12",sm:"12",align:"right"},{default:a(()=>[e(k,null,{default:a(({isHovering:r,props:y})=>[e(_(ae),{href:o.route("yearly_evaluations.index")},{default:a(()=>[e(F,A({class:"mr-2",variant:"text"},y,{color:r?"accent":"primary"}),{default:a(()=>[x("Zurück")]),_:2},1040,["color"])]),_:2},1032,["href"])]),_:1}),e(k,null,{default:a(({isHovering:r,props:y})=>[e(J,A({onClick:T},y,{color:r?"accent":"primary"}),{default:a(()=>[x("Speichern ")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1}),e(L,{modelValue:b.value,"onUpdate:modelValue":t[13]||(t[13]=r=>b.value=r),width:"80vw"},{default:a(()=>[e(I,{height:"50vh"},{default:a(()=>[e(Z,null,{default:a(()=>[e(C,null,{default:a(()=>[e(m,null,{default:a(()=>[e(n,{cols:"12"},{default:a(()=>[se,p("div",ue,[e(H,A(f,{icon:"mdi-alert",color:"orange"}),null,16),p("ul",de,[c("2_german")?(h(),z("li",me," Die Anzahl der Kinder im Alter bis 2,5 Jahre welche Deutsch als Fremdsprache haben Sie mit "+g(_(l).children_2_with_german_lang)+" angegeben, die Anzahl der über die BeoKiz-Ampel erfassten Kindern beträgt "+g(_(l).evaluations_with_daz_2_total_per_year),1)):v("",!0),c("2_foreign")?(h(),z("li",ce," Die Anzahl der Kinder im Alter bis 2,5 Jahre welche Deutsch als Muttersprache haben Sie mit "+g(_(l).children_2_with_foreign_lang)+" angegeben, die Anzahl der über die BeoKiz-Ampel erfassten Kindern beträgt "+g(_(l).evaluations_without_daz_2_total_per_year),1)):v("",!0),c("4_german")?(h(),z("li",he," Die Anzahl der Kinder im Alter bis 4,5 Jahre welche Deutsch als Fremdsprache haben Sie mit "+g(_(l).children_4_with_german_lang)+" angegeben, die Anzahl der über die BeoKiz-Ampel erfassten Kindern beträgt "+g(_(l).evaluations_with_daz_2_total_per_year),1)):v("",!0),c("4_foreign")?(h(),z("li",ge," Die Anzahl der Kinder im Alter bis 4,5 Jahre welche Deutsch als Muttersprache haben Sie mit "+g(_(l).children_4_with_foreign_lang)+" angegeben, die Anzahl der über die BeoKiz-Ampel erfassten Kindern beträgt "+g(_(l).evaluations_without_daz_4_total_per_year),1)):v("",!0)])]),e(N,{modelValue:D.value,"onUpdate:modelValue":t[12]||(t[12]=r=>D.value=r),label:"Ich habe verstanden, dass es Unstimmigkeiten bei dem Abgelich der gemelden Zahlen gibt, Statusmeldung dennoch absenden."},null,8,["modelValue"])]),_:1})]),_:1})]),_:1})]),_:1}),e(j,null,{default:a(()=>[e($),e(k,null,{default:a(({isHovering:r,props:y})=>[e(F,{onClick:q,color:r?"accent":"primary"},{default:a(()=>[x("Abbrechen")]),_:2},1032,["color"])]),_:1}),e(k,null,{default:a(({isHovering:r,props:y})=>[e(J,A({disabled:!D.value,onClick:T},y,{color:r?"accent":"primary"}),{default:a(()=>[x("Speichern")]),_:2},1040,["disabled","color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])])]),_:1},8,["errors"])],64)}}};export{be as default};
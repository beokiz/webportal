import{r as _,o as d,f as T,a as e,w as t,d as p,b as c,h as v,j as w,T as me,k as y,l as U,c as h,n as _e,u as s,Z as fe,i as j,t as ge,F as M,g as P,m as E}from"./app-680a6f50.js";import{_ as he}from"./GuestLayout-5c605735.js";import{A as pe}from"./ApplicationLogo-f89ab80c.js";import{f as S}from"./common-ea192878.js";const ve={key:0,class:"tw-flex tw-items-center tw-justify-space-between tw-text-xs tw-bg-gray-100 tw-rounded-[10px] tw-p-4 tw-w-full sm:tw-w-3/4"},be=["innerHTML"],$={__name:"InfoMessage",props:{text:String},setup(F){return(D,m)=>{const a=_("v-icon");return F.text?(d(),T("div",ve,[e(a,{size:"36",color:"#1E3050",class:"tw-mr-2"},{default:t(()=>[p("mdi-information-slab-circle")]),_:1}),c("p",{innerHTML:F.text},null,8,be)])):v("",!0)}}},ke=c("h2",{class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4"}," Anmeldung zur BeoKiz-Schulung ",-1),we=c("p",{class:"tw-mb-4"}," Sehr geehrte BeoKiz-Interessenten, ",-1),ye=c("p",{class:"tw-mb-4"}," die BeoKiz-Schulungen finden als Team-Schulung statt. Das heißt, alle pädagogischen Mitarbeitenden (Azubis, Quereinsteiger und Kitaleitungen) nehmen gemeinsam teil. In dem folgenden Formular können Sie Ihre Daten hinterlegen und Termine für die Schulungen Ihrer pädagogischen Fachkräfte auswählen bzw. Terminvorschläge für Ihre Schulungen an uns übermitteln. ",-1),Ve=c("p",{class:"tw-mb-4"}," Aus organisatorischen Gründen ist es zunächst relevant, wie viele pädagogische Fachkräfte, inklusive Kitaleitung, an Ihrer Einrichtung beschäftigt sind. Bitte wählen Sie aus, wie viele pädagogische Fachkräfte zu Ihrem Team zählen: ",-1),Se={key:0,class:"tw-mb-4"},De=c("b",null,"11",-1),ze={key:1,class:"tw-mb-4"},Te=c("b",null,"bis zu 10",-1),xe=c("p",{class:"tw-mb-4"}," Die Schulungen werden von zertifizierten und vom Land Berlin anerkannten BeoKiz-Multiplikator:innen durchgeführt. Einige Träger haben bereits ihre eigenen Fachberatungen als BeoKiz-Mulitiplikator:innen ausbilden lassen. Bitte geben Sie aus diesem Grund an, welchem Träger ihre Einrichtung angehört. ",-1),Ee={key:0,class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4"},Fe={class:"registration-training-heading tw-font-semibold tw-text-lg tw-text-gray-800 tw-leading-tight tw-mb-4"},Be=c("h2",{class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight"}," Ihre Einrichtung ",-1),Ue=c("h2",{class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight"}," Ansprechpartner in Ihrer Einrichtung für die BeoKiz-Schulung ",-1),Ie=c("h2",{class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight"}," Sonstiges ",-1),Ke=c("span",null,[p(" Ich erkläre mich mit der Verarbeitung der eingegebenen Daten sowie der "),c("a",{href:"https://kitearo.de/Datenschutzerklaerung/",target:"_blank",class:"tw-font-bold"},"Datenschutzerklärung"),p(" einverstanden. * ")],-1),Ae=c("h2",{class:"tw-font-semibold tw-text-base tw-text-gray-800 tw-leading-tight tw-mb-4"}," Anmeldung zur BeoKiz-Schulung ",-1),Oe=c("p",{class:"tw-mb-4"}," Vielen Dank für Ihre Anmeldung! ",-1),Me=c("p",{class:"tw-mb-4"}," Ihre Registrierung ist fast abgeschlossen. Um die Anmeldung abzuschließen, bestätigen Sie bitte Ihre E-Mail-Adresse. Eine E-Mail wurde an die von Ihnen angegebene Adresse gesendet. Bitte klicken Sie auf den Bestätigungslink in dieser E-Mail. ",-1),$e=c("p",{class:"tw-mb-4"}," Falls Sie die E-Mail nicht erhalten haben, überprüfen Sie bitte Ihren Spam-Ordner oder versuchen Sie es erneut. ",-1),qe=c("p",{class:"tw-mb-4"}," Wir freuen uns darauf, Sie bald bei der BeoKiz-Schulung willkommen zu heißen! ",-1),Ne=c("p",{class:"tw-mb-4"},[p(" Mit freundlichen Grüßen, "),c("br"),p("Ihr BeoKiz-Team ")],-1),J=3,Ge={__name:"Register",props:{availableTrainings:Array,operators:Array,errors:Object},setup(F){const D=F,m=w(D.errors||{}),a=me({kita:{number:null,name:null,district:null,street:null,house_number:null,zip_code:null,city:null,operator_id:null,other_operator:null,additional_info:null,participant_count:null,type:null,trainings:[],training_id:null},user:{first_name:null,last_name:null,email:null,phone_number:null},privacy_policy:!1}),Q=async()=>{a.processing=!0,z.value?(a.kita.training_id=null,a.kita.trainings=f.value):a.kita.trainings=[],a.post(route("auth.register_submit"),{preserveScroll:!0,preserveState:!0,onSuccess:r=>{x.value=!0},onError:r=>{m.value=r},onFinish:()=>{a.processing=!1}})},I=w("2025-01-01"),K=w("2027-07-31"),A=w(new Date(I.value)),X=w(new Date(K.value)),B=w(!1);y(()=>D.operators.filter(r=>(r==null?void 0:r.self_training)===!1||(r==null?void 0:r.self_training)===void 0)),y(()=>D.operators.filter(r=>(r==null?void 0:r.self_training)===!0||(r==null?void 0:r.self_training)===void 0));const Y=y(()=>D.operators.filter(r=>(r==null?void 0:r.self_training)===!0||(r==null?void 0:r.self_training)===void 0)),q=y(()=>D.availableTrainings.filter(r=>parseInt(r==null?void 0:r.available_participant_count)>=parseInt(a.kita.participant_count))),z=y(()=>a.kita.type==="large"),ee=y(()=>f.value.every(r=>!r.first_day_error&&!r.second_day_error&&r.first_date!==null&&r.second_date!==null)),te=y(()=>!!a.kita.training_id),ae=y(()=>{var r,l;return z.value?(r=a==null?void 0:a.kita)==null?void 0:r.participant_count:((l=a==null?void 0:a.kita)==null?void 0:l.participant_count)&&B.value===!1}),ne=y(()=>{var r;return(r=a==null?void 0:a.kita)!=null&&r.operator_id?!0:z.value?f.value.length>0&&ee.value:te.value}),x=w(!1);U(()=>a.kita.participant_count,r=>{r&&r<=10?(B.value=q.value.length<=0,r<=0&&(a.kita.participant_count=1),N("small")):(B.value=!1,N("large"))}),U(()=>a.kita.operator_id,r=>{a.kita.other_operator=null,a.kita.training_id=null,f.value=[{first_date:null,second_date:null,isFirstDateFieldOpened:!1,isSecondDateFieldOpened:!1,first_day_error:"",second_day_error:""}]}),U(()=>a.kita.other_operator,r=>{r||(f.value=[{first_date:null,second_date:null,isFirstDateFieldOpened:!1,isSecondDateFieldOpened:!1,first_day_error:"",second_day_error:""}])});const N=r=>{a.kita.type=r,a.kita.operator_id=null,a.kita.other_operator=null,a.kita.training_id=null,a.kita.trainings=[]},f=w([{first_date:null,second_date:null,isFirstDateFieldOpened:!1,isSecondDateFieldOpened:!1,first_day_error:"",second_day_error:""}]),le=()=>{f.value.length<J&&f.value.push({first_date:null,second_date:null,isFirstDateFieldOpened:!1,isSecondDateFieldOpened:!1,first_day_error:"",second_day_error:""})},re=r=>{f.value.length>1&&f.value.splice(r,1)},C=w([]);U(f,r=>{r.forEach((l,i)=>{const o=C.value[i]||{};(l.first_date!==o.first_date||l.second_date!==o.second_date)&&se(i)}),C.value=r.map(l=>({first_date:l.first_date?S(l.first_date,"de-DE"):null,second_date:l.second_date?S(l.second_date,"de-DE"):null}))},{deep:!0});const se=r=>{const l=f.value[r],i=new Date;i.setHours(0,0,0,0);let o="",b="";if(l.first_date){const u=new Date(l.first_date);l.first_date&&l.first_date instanceof Date&&l.first_date.getHours()!==12&&(f.value[r].first_date=new Date(u.setHours(12,0,0,0))),u<i&&(o="Erster Schulungstag darf nicht in der Vergangenheit liegen.")}if(l.second_date){const u=new Date(l.second_date);l.second_date&&l.second_date instanceof Date&&l.second_date.getHours()!==12&&(f.value[r].second_date=new Date(u.setHours(12,0,0,0))),u<i&&(b="Zweiter Schulungstag darf nicht in der Vergangenheit liegen.")}if(l.first_date&&l.second_date){const u=new Date(l.first_date),k=new Date(l.second_date);k<u?(o="Der zweite Schulungstag darf nicht am gleichen Tag oder vor dem ersten Schulungstag liegen.",b="Der zweite Schulungstag darf nicht am gleichen Tag oder vor dem ersten Schulungstag liegen."):Math.abs((k-u)/864e5)>7&&(o="Der erste und der zweite Schulungstag müssen innerhalb von 7 Tagen liegen.",b="Der zweite und der erste Schulungstag müssen innerhalb von 7 Tagen liegen.")}_e(()=>{l.first_day_error=o,l.second_day_error=b})};return(r,l)=>{const i=_("v-col"),o=_("v-row"),b=_("v-container"),u=_("v-text-field"),k=_("v-fade-transition"),H=_("v-select"),L=_("v-date-picker"),W=_("v-menu"),G=_("v-locale-provider"),R=_("v-icon"),O=_("v-btn"),ie=_("v-radio"),ue=_("v-radio-group"),oe=_("v-textarea"),de=_("v-checkbox"),Z=_("v-hover"),ce=_("v-btn-primary");return d(),h(he,null,{default:t(()=>[e(s(fe),{title:"Registrieren"}),e(b,null,{default:t(()=>[e(o,null,{default:t(()=>[e(i,{cols:"12",class:"tw-flex tw-items-center tw-justify-center tw-mb-8"},{default:t(()=>[e(s(j),{href:"/"},{default:t(()=>[e(pe,{class:"tw-h-20 tw-fill-current tw-text-gray-500"})]),_:1})]),_:1})]),_:1})]),_:1}),e(k,null,{default:t(()=>[x.value?v("",!0):(d(),h(b,{key:0},{default:t(()=>[e(o,null,{default:t(()=>[e(i,{cols:"12",class:"tw-mb-8"},{default:t(()=>[ke,we,ye,Ve]),_:1})]),_:1}),e(o,null,{default:t(()=>[e(i,{cols:"12",sm:"5",class:"tw-mb-8"},{default:t(()=>[e(u,{modelValue:s(a).kita.participant_count,"onUpdate:modelValue":l[0]||(l[0]=n=>s(a).kita.participant_count=n),type:"number",min:1,"error-messages":m.value["kita.participant_count"],label:"Größe unseres pädagogischen Teams",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"7",class:"tw-mb-8"},{default:t(()=>[e(k,null,{default:t(()=>[B.value?(d(),h($,{key:0,text:`Leider gibt es aktuell keine verfügbarern Tremin für ein Team mit der Größe von ${s(a).kita.participant_count} Mitarbeitenden. <br/> Voraussichtlich ab nächsten August, werden neune Termin bekannt gegeben!`},null,8,["text"])):v("",!0)]),_:1})]),_:1})]),_:1})]),_:1}))]),_:1}),e(k,null,{default:t(()=>[!x.value&&ae.value?(d(),h(b,{key:0},{default:t(()=>[e(o,{class:"tw-mb-8"},{default:t(()=>[e(i,{cols:"12"},{default:t(()=>[z.value?(d(),T("p",Se,[p(" Die BeoKiz-Schulungen finden für Einrichtungen ab "),De,p(" pädagogischen Fachkräften als Schulung in ihrer Einrichtung statt. ")])):(d(),T("p",ze,[p(" Die BeoKiz-Schulungen finden für Einrichtungen mit "),Te,p(" pädagogischen Fachkräften an bereits terminierten Zeiträumen in der KiTeAro Akademie (Stromstrasse 38, 10551 Berlin) statt und werden gemeinsam mit Fachkräften aus anderen Einrichtungen durchgeführt. ")])),xe]),_:1})]),_:1}),e(o,{class:"tw-mb-8"},{default:t(()=>[e(i,{cols:"12",sm:"4"},{default:t(()=>[e(H,{modelValue:s(a).kita.operator_id,"onUpdate:modelValue":l[1]||(l[1]=n=>s(a).kita.operator_id=n),items:Y.value,"error-messages":m.value["kita.operator_id"],"item-title":"name","item-value":"id",label:"Träger"},null,8,["modelValue","items","error-messages"])]),_:1}),e(i,{cols:"12",sm:"4"},{default:t(()=>[s(a).kita.operator_id?v("",!0):(d(),h(u,{key:0,modelValue:s(a).kita.other_operator,"onUpdate:modelValue":l[2]||(l[2]=n=>s(a).kita.other_operator=n),"error-messages":m.value["kita.other_operator"],label:"Sonstiger Träger"},null,8,["modelValue","error-messages"]))]),_:1}),e(i,{cols:"12",sm:"4"}),e(i,{cols:"12",sm:"4"})]),_:1}),e(k,null,{default:t(()=>[s(a).kita.other_operator?(d(),h(o,{key:0},{default:t(()=>[e(i,{cols:"12"},{default:t(()=>[z.value?v("",!0):(d(),T("h2",Ee," Noch verfügbare Schulungszeiträume sind folgende: "))]),_:1}),e(i,{cols:"12",md:"6"},{default:t(()=>[z.value?(d(),T(M,{key:0},[c("h3",Fe,ge(`Terminvorschläge für Schulung (ab ${s(S)(A.value,"de-DE")} bis ${s(S)(X.value,"de-DE")})`),1),(d(!0),T(M,null,P(f.value,(n,V)=>(d(),h(o,{key:V,class:"tw-mb-4"},{default:t(()=>[e(i,{cols:"10",sm:"5"},{default:t(()=>[e(G,{locale:"de"},{default:t(()=>[e(W,{modelValue:n.isFirstDateFieldOpened,"onUpdate:modelValue":g=>n.isFirstDateFieldOpened=g,"return-value":n.first_date,"close-on-content-click":!1},{activator:t(({props:g})=>[e(u,E({label:"Erster Schulungstag*",class:"tw-cursor-pointer","model-value":n.first_date?s(S)(n.first_date,"de-DE"):null,"error-messages":m.value[`kita.trainings.${V}.first_date`]??n.first_day_error,"prepend-icon":"mdi-calendar",readonly:""},g),null,16,["model-value","error-messages"])]),default:t(()=>[e(L,{"onUpdate:modelValue":[g=>{n.isFirstDateFieldOpened=!1},g=>n.first_date=g],modelValue:n.first_date,min:I.value,max:K.value,"display-date":n.first_date?n.first_date:A.value},null,8,["onUpdate:modelValue","modelValue","min","max","display-date"])]),_:2},1032,["modelValue","onUpdate:modelValue","return-value"])]),_:2},1024)]),_:2},1024),e(i,{cols:"10",sm:"5"},{default:t(()=>[e(G,{locale:"de"},{default:t(()=>[e(W,{modelValue:n.isSecondDateFieldOpened,"onUpdate:modelValue":g=>n.isSecondDateFieldOpened=g,"return-value":n.second_date,"close-on-content-click":!1},{activator:t(({props:g})=>[e(u,E({label:"Zweiter Schulungstag*",class:"tw-cursor-pointer","model-value":n.second_date?s(S)(n.second_date,"de-DE"):null,"error-messages":m.value[`kita.trainings.${V}.second_date`]??n.second_day_error,"prepend-icon":"mdi-calendar",readonly:""},g),null,16,["model-value","error-messages"])]),default:t(()=>[e(L,{"onUpdate:modelValue":[g=>{n.isSecondDateFieldOpened=!1},g=>n.second_date=g],modelValue:n.second_date,min:I.value,max:K.value,"display-date":n.second_date?n.second_date:A.value},null,8,["onUpdate:modelValue","modelValue","min","max","display-date"])]),_:2},1032,["modelValue","onUpdate:modelValue","return-value"])]),_:2},1024)]),_:2},1024),e(i,{cols:"2",class:"tw-text-right"},{default:t(()=>[f.value.length>1?(d(),h(O,{key:0,icon:"",onClick:g=>re(V)},{default:t(()=>[e(R,null,{default:t(()=>[p("mdi-delete")]),_:1})]),_:2},1032,["onClick"])):v("",!0)]),_:2},1024)]),_:2},1024))),128)),f.value.length<J?(d(),h(O,{key:0,onClick:le},{default:t(()=>[e(R,E(D,{size:"large",class:"tw-me-2"}),{default:t(()=>[p("mdi-plus-circle-outline")]),_:1},16),p(" Weiteren Terminvorschlag hinzufügen ")]),_:1})):v("",!0)],64)):(d(),h(ue,{key:1,modelValue:s(a).kita.training_id,"onUpdate:modelValue":l[3]||(l[3]=n=>s(a).kita.training_id=n)},{default:t(()=>[(d(!0),T(M,null,P(q.value,n=>(d(),h(ie,{key:n.id,label:`${s(S)(n.first_date,"de-DE")} und ${s(S)(n.second_date,"de-DE")}`,value:n==null?void 0:n.id},null,8,["label","value"]))),128))]),_:1},8,["modelValue"]))]),_:1}),e(i,{cols:"12",md:"6"},{default:t(()=>[e($,{text:z.value?"Wählen Sie bitte nachfolgend zwei direkt aufeinanderfolgende Schulungstermine aus. Wenn für Sie zwei aufeinanderfolgende Termine nicht möglich sind, wählen Sie bitte zwei Tage aus, die weniger als 7 Tage auseinander liegen. Es handelt sich dabei um Terminvorschläge, die Sie einreichen. Eine Bestätigung der Schulungstermine findet erst statt, wenn sich ein BeoKiz-Multiplikator für Ihren Terminvorschlag gefunden hat und dieser Kontakt mit Ihnen aufgenommen hat.":"Bitte wählen Sie ein Schulungszeitraum aus, an welchen Tagen eine Durchführung mit Ihrem gesamten pädagogischen Team möglich ist. Schulungsort ist voraussichtlich in der Nähe des KiTeAro Akademie - Stromstr. 38 - 10551 Berlin."},null,8,["text"])]),_:1})]),_:1})):v("",!0)]),_:1})]),_:1})):v("",!0)]),_:1}),e(k,null,{default:t(()=>[!x.value&&ne.value?(d(),h(b,{key:0},{default:t(()=>[e(o,{class:"tw-mb-8"},{default:t(()=>[e(i,{cols:"12"},{default:t(()=>[Be]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.number,"onUpdate:modelValue":l[4]||(l[4]=n=>s(a).kita.number=n),"error-messages":m.value["kita.number"],label:"Kitanummer / 8-stellige Einrichtungsnummer *",type:"number",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.name,"onUpdate:modelValue":l[5]||(l[5]=n=>s(a).kita.name=n),"error-messages":m.value["kita.name"],label:"Einrichtungsname *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.district,"onUpdate:modelValue":l[6]||(l[6]=n=>s(a).kita.district=n),"error-messages":m.value["kita.district"],label:"Bezirk"},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.street,"onUpdate:modelValue":l[7]||(l[7]=n=>s(a).kita.street=n),"error-messages":m.value["kita.street"],label:"Straße *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.house_number,"onUpdate:modelValue":l[8]||(l[8]=n=>s(a).kita.house_number=n),"error-messages":m.value["kita.house_number"],label:"Hausnummer *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.zip_code,"onUpdate:modelValue":l[9]||(l[9]=n=>s(a).kita.zip_code=n),"error-messages":m.value["kita.zip_code"],label:"Postleitzahl",type:"number *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).kita.city,"onUpdate:modelValue":l[10]||(l[10]=n=>s(a).kita.city=n),"error-messages":m.value["kita.city"],label:"Stadt *",required:""},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(o,{class:"tw-mb-8"},{default:t(()=>[e(i,{cols:"12"},{default:t(()=>[Ue]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).user.first_name,"onUpdate:modelValue":l[11]||(l[11]=n=>s(a).user.first_name=n),"error-messages":m.value["user.first_name"],label:"Vorname *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).user.last_name,"onUpdate:modelValue":l[12]||(l[12]=n=>s(a).user.last_name=n),"error-messages":m.value["user.last_name"],label:"Nachname *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).user.email,"onUpdate:modelValue":l[13]||(l[13]=n=>s(a).user.email=n),"error-messages":m.value["user.email"],label:"Email *",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(u,{modelValue:s(a).user.phone_number,"onUpdate:modelValue":l[14]||(l[14]=n=>s(a).user.phone_number=n),"error-messages":m.value["user.phone_number"],label:"Telefonnummer"},null,8,["modelValue","error-messages"])]),_:1})]),_:1}),e(o,{class:"tw-mb-8"},{default:t(()=>[e(i,{cols:"12"},{default:t(()=>[Ie]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e(oe,{modelValue:s(a).kita.additional_info,"onUpdate:modelValue":l[15]||(l[15]=n=>s(a).kita.additional_info=n),"error-messages":m.value["kita.additional_info"],label:"Anmerkungen",rows:"3"},null,8,["modelValue","error-messages"])]),_:1}),e(i,{cols:"12",sm:"6"},{default:t(()=>[e($,{text:"Für die Durchführung der BeoKiz-Schulung stehen, von der Senatsverwaltung für Bildung, Jugend und Familie anerkannte, Multiplikator:innen zur Verfügung. <br/> Wir freuen uns auf Sie!"})]),_:1})]),_:1}),e(o,null,{default:t(()=>[e(i,{cols:"12"},{default:t(()=>[e(de,{modelValue:s(a).privacy_policy,"onUpdate:modelValue":l[16]||(l[16]=n=>s(a).privacy_policy=n),value:!0},{label:t(()=>[Ke]),_:1},8,["modelValue"])]),_:1})]),_:1})]),_:1})):v("",!0)]),_:1}),e(k,null,{default:t(()=>[x.value?v("",!0):(d(),h(b,{key:0},{default:t(()=>[e(o,null,{default:t(()=>[e(i,{cols:"12",sm:"6"},{default:t(()=>[e(Z,null,{default:t(({isHovering:n,props:V})=>[e(s(j),{href:r.route("auth.login")},{default:t(()=>[e(O,E({class:"mr-2",variant:"text"},V,{color:n?"accent":"primary"}),{default:t(()=>[p("Zurück")]),_:2},1040,["color"])]),_:2},1032,["href"])]),_:1})]),_:1}),e(i,{cols:"12",sm:"6",align:"right"},{default:t(()=>[e(Z,null,{default:t(({isHovering:n,props:V})=>[e(ce,E(V,{color:n?"accent":"primary",disabled:!s(a).privacy_policy,onClick:Q}),{default:t(()=>[p(" Absenden ")]),_:2},1040,["color","disabled"])]),_:1})]),_:1})]),_:1})]),_:1}))]),_:1}),e(k,null,{default:t(()=>[x.value?(d(),h(b,{key:0},{default:t(()=>[e(o,null,{default:t(()=>[e(i,{cols:"12",class:"tw-mb-8"},{default:t(()=>[Ae,Oe,Me,$e,qe,Ne]),_:1})]),_:1})]),_:1})):v("",!0)]),_:1})]),_:1})}}};export{Ge as default};
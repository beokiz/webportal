import{d as le}from"./index-828083c3.js";import{K as se,h as w,j as oe,v as ie,s as re,T as R,k as ue,r as u,o as k,f as K,a as e,u as i,w as a,F as de,Z as ce,b as v,c as F,g as N,m as y,d as p,t as me,x as _e}from"./app-3f6ccddf.js";import{p as T,a as ve}from"./common-b6d82cb9.js";import{_ as pe}from"./AuthenticatedLayout-02d80600.js";import{_ as Y}from"./EvaluationDomainsList-6ada9920.js";import{v as fe}from"./v4-4a60fe23.js";import"./ApplicationLogo-ed6b7a78.js";const ge=v("h2",{class:"tw-font-semibold tw-text-xl tw-text-gray-800 tw-leading-tight"},"Auswertung erstellen",-1),he={class:"tw-table-block tw-max-w-full tw-mx-auto tw-py-6 tw-px-4 sm:tw-px-6 lg:tw-px-8"},we=v("h3",null,"Eigenschaften",-1),be={key:0,class:"tw-flex justify-center items-center tw-bg-white"},ke={class:"tw-text-right"},ye=["href"],xe={class:"tw-text-center"},Ve=v("h1",{class:"tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8"}," Screening wurde eingereicht ",-1),Ee=v("p",{class:"tw-mb-8"}," Folgendes Screening wurde eingereicht und kann nur bis 15 Minuten nach Einreichung bearbeitet werden. Danach verschwindet es aus Ihrer Übersicht. Sollten Sie es zurückziehen oder bearbeiten wollen, klicke Sie auf 'Abgabe zurückziehen. Nachfolgend erhalten Sie eine Übersicht des eingereichten Screenings, welches Sie über den Download-Button als PDF herunterladen können. ",-1),Se=v("span",{class:"tw-font-black"},"Bezeichner des Screenings",-1),qe={__name:"ManageEvaluation",props:{evaluation:Object,kitas:Array,domains:Array,errors:Object,newCustomUniqueId:String},setup(x){const r=x;le.Inertia.on("success",s=>{s.detail.page.props,s.detail.page.component});const $=se().props.auth.user??{},M=w(null),c=w(r.errors||{}),b=w(!1),D=w([]),V=w(!1),f=w(null),E=w([{name:"1 - 12 Monate",id:"upToOneYear"},{name:"12 - 24 Monate",id:"upToTwoYears"},{name:"24 - 36 Monate",id:"upToThreeYears"}]),d=oe(()=>!!r.evaluation);ie(()=>{d.value||j()}),re(()=>{var s;B((s=r.evaluation)==null?void 0:s.age),d.value||q()});const q=()=>{M.value=fe(),t.uuid=M.value},B=s=>{if(s===4.5)E.value.push({name:"Länger als 36 Monate",id:"moreThanThreeYears"});else{const n=E.value.findIndex(o=>o.id==="moreThanThreeYears");n!==-1&&E.value.splice(n,1)}},j=()=>{t.ratings=T(r.domains)},U=()=>{c.value={},V.value=!1},O=()=>{t.reset(),t.clearErrors(),c.value={},t.ratings=T(r.domains),d.value||q()},t=R({id:d.value?r.evaluation.id:null,custom_unique_id:d.value?r.evaluation.custom_unique_id:r.newCustomUniqueId,uuid:d.value?r.evaluation.uuid:null,user_id:d.value?r.evaluation.user_id:$.id,kita_id:d.value?r.evaluation.kita_id:null,age:d.value?r.evaluation.age:null,child_duration_in_kita:d.value?r.evaluation.child_duration_in_kita:null,is_daz:d.value?r.evaluation.is_daz:!1,integration_status:d.value?r.evaluation.integration_status:!1,speech_therapy_status:d.value?r.evaluation.speech_therapy_status:!1,ratings:d.value?r.evaluation.data:[]});ue(()=>t.age,s=>{t.ratings=T(r.domains),B(s),s!==4.5&&(t.child_duration_in_kita=null)});const P=s=>{t.ratings=s},Z=s=>{D.value=_e(s)?s.value:s},H=async()=>{t.processing=!0,b.value=!0;let s=[];D.value.map(o=>(o.subdomains.map(m=>(m.milestones.map(g=>(s.push(parseInt(g.id)),g)),m)),o)),t.ratings=t.ratings.map(o=>(o.milestones.map(m=>(s.includes(parseInt(m.id))||(m.value=0),m)),o));let n={onSuccess:o=>{t.clearErrors(),c.value={},V.value=!0,f.value=o.props.data},onError:o=>{c.value=o},onFinish:()=>{t.processing=!1,b.value=!1}};d.value?t.put(route("evaluations.update",{evaluation:r.evaluation.id}),n):t.post(route("evaluations.store"),n)},L=async()=>{t.processing=!0,b.value=!0,t.post(route("evaluations.save"),{preserveState:!1,onSuccess:s=>{t.clearErrors(),c.value={}},onError:s=>{c.value=s},onFinish:()=>{t.processing=!1,b.value=!1}})},S=R({}),G=async s=>{S.processing=!0,S.post(route("evaluations.unfinished",{id:s}),{preserveState:!1,onSuccess:n=>{S.clearErrors(),c.value={},U()},onError:n=>{c.value=n},onFinish:()=>{S.processing=!1,b.value=!1}})};return(s,n)=>{const o=u("v-col"),m=u("v-row"),g=u("v-container"),J=u("v-text-field"),C=u("v-select"),I=u("v-checkbox"),Q=u("v-progress-circular"),z=u("v-btn"),h=u("v-hover"),A=u("v-btn-primary"),W=u("v-icon"),X=u("v-card-text"),ee=u("v-spacer"),ae=u("v-card-actions"),te=u("v-card"),ne=u("v-dialog");return k(),K(de,null,[e(i(ce),{title:"Auswertung erstellen"}),e(pe,{errors:c.value},{header:a(()=>[ge]),default:a(()=>[v("div",he,[e(g,null,{default:a(()=>[e(m,null,{default:a(()=>[e(o,{cols:"12"},{default:a(()=>[we]),_:1})]),_:1})]),_:1}),e(g,null,{default:a(()=>[e(m,null,{default:a(()=>[e(o,{cols:"12",sm:"3"},{default:a(()=>[e(J,{modelValue:i(t).custom_unique_id,"onUpdate:modelValue":n[0]||(n[0]=l=>i(t).custom_unique_id=l),"error-messages":c.value.custom_unique_id,readonly:"",label:"Bezeichner der Einschatzung",required:""},null,8,["modelValue","error-messages"])]),_:1}),e(o,{cols:"12",sm:"2"},{default:a(()=>[e(C,{modelValue:i(t).age,"onUpdate:modelValue":n[1]||(n[1]=l=>i(t).age=l),items:i(ve),"error-messages":c.value.age,"item-title":"age_name","item-value":"age_number",label:"Altersgruppe"},null,8,["modelValue","items","error-messages"])]),_:1}),e(o,{cols:"12",sm:"2"},{default:a(()=>[e(C,{modelValue:i(t).kita_id,"onUpdate:modelValue":n[2]||(n[2]=l=>i(t).kita_id=l),items:x.kitas,"error-messages":c.value.kita_id,"item-title":"name","item-value":"id",label:"Kita"},null,8,["modelValue","items","error-messages"])]),_:1}),e(o,{cols:"12",sm:"2"},{default:a(()=>[e(C,{modelValue:i(t).child_duration_in_kita,"onUpdate:modelValue":n[3]||(n[3]=l=>i(t).child_duration_in_kita=l),items:E.value,"error-messages":c.value.child_duration_in_kita,"item-title":"name","item-value":"id",label:"Zeitraum in der Kita"},null,8,["modelValue","items","error-messages"])]),_:1}),e(o,{cols:"12",sm:"3"},{default:a(()=>[e(I,{modelValue:i(t).is_daz,"onUpdate:modelValue":n[4]||(n[4]=l=>i(t).is_daz=l),label:"Deutsch ist nicht Erstsprache des Kindes"},null,8,["modelValue"]),e(I,{modelValue:i(t).integration_status,"onUpdate:modelValue":n[5]||(n[5]=l=>i(t).integration_status=l),label:"Integrationsstatus"},null,8,["modelValue"]),e(I,{modelValue:i(t).speech_therapy_status,"onUpdate:modelValue":n[6]||(n[6]=l=>i(t).speech_therapy_status=l),label:"in logopädische Behandlung"},null,8,["modelValue"])]),_:1})]),_:1}),e(m,{class:"manage-evaluation-domains"},{default:a(()=>[i(t).age?(k(),F(Y,{key:0,ratings:i(t).ratings,age:i(t).age,domains:x.domains,errors:c.value,onUpdateRatingData:P,onUpdateDomainsData:Z},null,8,["ratings","age","domains","errors"])):N("",!0)]),_:1})]),_:1}),e(g,null,{default:a(()=>[b.value?(k(),K("div",be,[e(Q,{indeterminate:"",size:40})])):(k(),F(m,{key:1},{default:a(()=>[e(o,{cols:"12",sm:"6"},{default:a(()=>[e(h,null,{default:a(({isHovering:l,props:_})=>[e(z,y({onClick:O},_,{color:l?"primary":"accent"}),{default:a(()=>[p(" Zurücksetzen ")]),_:2},1040,["color"])]),_:1})]),_:1}),e(o,{cols:"12",sm:"6",align:"right"},{default:a(()=>[e(h,null,{default:a(({isHovering:l,props:_})=>[e(z,y({class:"mr-2",onClick:L,variant:"text"},_,{color:l?"accent":"primary"}),{default:a(()=>[p(" Speichern ")]),_:2},1040,["color"])]),_:1}),i(t).age&&D.value.length>0?(k(),F(h,{key:0},{default:a(({isHovering:l,props:_})=>[e(A,y({onClick:H},_,{color:l?"accent":"primary"}),{default:a(()=>[p(" Prüfen und Einreichen ")]),_:2},1040,["color"])]),_:1})):N("",!0)]),_:1})]),_:1}))]),_:1})]),e(ne,{modelValue:V.value,"onUpdate:modelValue":n[9]||(n[9]=l=>V.value=l),width:"95vw"},{default:a(()=>[e(te,{height:"95vh"},{default:a(()=>[e(X,null,{default:a(()=>[e(g,null,{default:a(()=>[e(m,{class:"result-evaluation-domains"},{default:a(()=>[e(o,{cols:"12"},{default:a(()=>[e(h,null,{default:a(({isHovering:l,props:_})=>[v("div",ke,[v("a",{href:s.route("evaluations.pdf",{id:f.value.item.id}),onClick:U,title:"Fenster schließen"},[e(W,y(_,{size:"small",class:"tw-me-2",onClick:n[7]||(n[7]=()=>{})}),{default:a(()=>[p("mdi-close")]),_:2},1040)],8,ye)])]),_:1})]),_:1})]),_:1}),e(m,{class:"result-evaluation-domains"},{default:a(()=>[e(o,{cols:"8",offset:"2"},{default:a(()=>[v("div",xe,[Ve,Ee,e(h,null,{default:a(({isHovering:l,props:_})=>[e(z,{href:s.route("evaluations.pdf",{id:f.value.item.id}),class:"tw-px-2 tw-py-3 tw-mb-4 tw-mr-4 tw-normal-case",color:l?"primary":"accent"},{default:a(()=>[p(" Screening als PDF downloaden ")]),_:2},1032,["href","color"])]),_:1}),e(h,null,{default:a(({isHovering:l,props:_})=>[e(z,{onClick:n[8]||(n[8]=ze=>G(f.value.item.id)),class:"tw-px-2 tw-py-3 tw-mb-4 tw-normal-case",color:l?"accent":"primary"},{default:a(()=>[p(" Abgabe zurückziehen ")]),_:2},1032,["color"])]),_:1})])]),_:1}),e(o,{cols:"12"},{default:a(()=>[v("p",null,[Se,p(": "+me(`${f.value.item.kita.formatted_name}_${f.value.item.custom_unique_id}`),1)])]),_:1}),e(o,{cols:"12"},{default:a(()=>[e(Y,{ratings:f.value.item.data,age:i(t).age,domains:x.domains,disabled:!0},null,8,["ratings","age","domains"])]),_:1})]),_:1})]),_:1})]),_:1}),e(ae,null,{default:a(()=>[e(ee),e(h,null,{default:a(({isHovering:l,props:_})=>[e(A,y({onClick:U},_,{color:l?"accent":"primary"}),{default:a(()=>[p("Abbrechen")]),_:2},1040,["color"])]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["errors"])],64)}}};export{qe as default};
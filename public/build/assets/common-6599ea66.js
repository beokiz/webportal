const c=(t,e="en-US")=>{const a=new Date(t);if(isNaN(a.getTime()))return"-";const n={year:"numeric",month:"2-digit",day:"2-digit"};return new Date(t).toLocaleDateString(e,n).replace(/\//g,".")},d=(t,e="en-US",a=!1)=>{const n=new Date(t);if(isNaN(n.getTime()))return"-";const i={year:"numeric",month:"2-digit",day:"2-digit",hour:"2-digit",minute:"2-digit"};return a&&(i.second="2-digit"),new Date(t).toLocaleDateString(e,i).replace(/\//g,".")},m=[{age_name:"2.5",age_number:2.5},{age_name:"4.5",age_number:4.5}],g=t=>{let e=[];return t.forEach(function(a,n){e[n]={domain:a.id,milestones:[]},a.subdomains.forEach(function(i,r){i.milestones.forEach(function(o,s){e[n].milestones.push({id:o.id,abbreviation:o.abbreviation,value:null})})})}),e};export{m as a,c as b,d as f,g as p};
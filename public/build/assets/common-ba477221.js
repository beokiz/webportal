const r=(t,e="en-US")=>{const a=new Date(t);if(isNaN(a.getTime()))return"-";const n={year:"numeric",month:"2-digit",day:"2-digit"};return new Date(t).toLocaleDateString(e,n).replace(/\//g,".")},s=(t,e="en-US",a=!1)=>{const n=new Date(t);if(isNaN(n.getTime()))return"-";const i={year:"numeric",month:"2-digit",day:"2-digit",hour:"2-digit",minute:"2-digit"};return a&&(i.second="2-digit"),new Date(t).toLocaleDateString(e,i).replace(/\//g,".")},c=[{age_name:"2.5",age_number:2.5},{age_name:"4.5",age_number:4.5}];export{c as a,r as b,s as f};

// console.meme = (meme, top, bottom, size) => {
//   let serverUrl = 'http://lit-atoll-9603.herokuapp.com/api/v1/';

//   if (typeof size === 'undefined'){
//     size = 200;
//   }
//   //craft url
//   let url = serverUrl+meme+'/'+size+'/'+encodeURIComponent(top)+'/'+encodeURIComponent(bottom);

//   //css buffer
//   let css = [
//     `background-image: url('${url}')`,
//     'background-repeat: no-repeat',
//     'background-position: 50% 50%'
//   ]
//   //fix size
//   size = Math.floor(size/2);
//   css += 'padding: '+size+'px '+size+'px; background-size: 100%;';

//   let bottomPadding = '';

//   let how_many = Math.ceil(size / 14)+2; //2 more spaces just because

//   while(how_many--){
//     bottomPadding += '\n';
//   }

//   console.info('%c %c'+bottomPadding, css.join(';'), 'background: none;');
// };

console.noGA = () => {
  let css = [
    'color: rgba(131,93,161,1)',
    'font-size: 16px',
    'font-weight: bold'
  ];

  console.info("%c🧙 Google Tag Manager not loaded in non-production environments.", css.join(';'));
}

console.buffs = () => {
  let server = window.location.origin;
  server = (typeof server === 'string' && server.length > 0) ? server : 'https://buffs.app';

  let css = [ 
    `background: #8242c4 url(${server}/images/brand/buffs_logo.svg) 50% 50% no-repeat`,
    'background-size: 259px 90px',
    'padding: 50px 144px',
    'line-height: 0'
  ];

  console.info('%c ', css.join(';'));
}

console.intro = (env = null) => {
  env = (env === null) ? 
    'produciton' :
    (typeof env === 'string' && env.length > 0) ? 
      env : 'production';

  switch (env) {
    case "production":
    case "development":
    case "local":
      console.group("Welcome to BUFFS!");
      console.buffs();
      console.noGA();
      console.groupEnd();
      break;
    case "production":
    default:
      console.group("Welcome to BUFFS!");
      console.buffs();
      console.groupEnd();
      break;
  }
}


console.meme = (opts) => {
  let defaults = {
    'meme': 'grumpy-cat',
    'top': ' ',
    'bottom': ' ',
    'size': 200
  }

  if (typeof opts === "object") {
    opts = {...defaults, ...opts};
    
    // sanitize strings
    opts.meme = (typeof opts.meme === 'string' && opts.meme.length) ? opts.meme : defaults.meme;
    opts.top = (typeof opts.top === 'string' && opts.top.length) ? opts.top : defaults.top;
    opts.bottom = (typeof opts.bottom === 'string' && opts.bottom.length) ? opts.bottom : defaults.bottom;
    // sanitize size
    opts.size = parseInt(opts.size);
    opts.size = (typeof opts.size === 'number' && opts.size > 0) ? opts.size : defaults.size;
  } else {
    opts = defaults;
  }

  let serverUrl = 'http://lit-atoll-9603.herokuapp.com/api/v1/';
  let padding = Math.floor(opts.size / 2);
  
  //craft url
  let url = `${serverUrl}${opts.meme}/${opts.size}/${encodeURIComponent(opts.top)}/${encodeURIComponent(bottom)}`;

  //css buffer
  let css = [
    `background-image: url('${url}')`,
    'background-repeat: no-repeat',
    'background-position: 50% 50%'
  ]
  //fix size
  css.push(`padding: ${padding}px`, `background-size: 100%`);

  let bottomPadding = '';
  let paddingCounter = Math.ceil(opts.size / 14)+2; //2 more spaces just because

  while(paddingCounter--){
    bottomPadding += '\n';
  }

  console.info(`%c %c${bottomPadding}`, css.join(';'), 'background: none;');
};

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

  console.group("Welcome to BUFFS!");
  switch (env) {
    case "staging":
    case "development":
    case "local":
      console.buffs();
      console.noGA();
      break;
    case "production":
    default:
      console.buffs();
      break;
  }
  console.groupEnd();
}

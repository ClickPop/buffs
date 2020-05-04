// window._ = require('lodash');

try {
  window.$ = window.jQuery = require('jquery');
  require('bootstrap/dist/js/bootstrap.bundle');
  require('datatables.net-bs4');
} catch (e) {
  console.error(e);
}

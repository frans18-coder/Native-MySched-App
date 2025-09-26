// script.js - untuk fungsi kecil di client
document.addEventListener('DOMContentLoaded', function(){
  // contoh: konfirmasi hapus (tambahan graceful)
  document.querySelectorAll('a[data-confirm]').forEach(function(el){
    el.addEventListener('click', function(e){
      if(!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });
});
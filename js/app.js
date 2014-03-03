function showAlert(text) {
    $('<div data-alert class="alert-box alert">'+text+'<a href="#" class="close">&times;</a></div>')
             .hide()
             .appendTo($('#alerts'))
             .fadeIn('medium');
    $(document).foundation(); 
}

function processJSON(data) {
  switch(data.action) {
      case 'printerror':
          showAlert(data.data);
          break;
      case 'reload':
          window.location.reload(true);
          break;
  }   
}

function getLoader() {
    return '<ul class="loader"><li></li><li></li><li></li><li></li><li></li><li></li><li></li></ul>';
}
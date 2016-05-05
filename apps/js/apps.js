window.apps = {
  url: '/',
  varName:false,
  varData:false,
  name: function(name){
    this.varName = name; 
    return this;
  },
  data: function(data){
    this.varData = data;
    return this;
  },
  exec: function(callback){
    var blob = new Blob([JSON.stringify(this.varData)], {type : 'application/json'});
    var xhr = new XMLHttpRequest();
    xhr.open('POST', this.url, true);
    xhr.setRequestHeader('Accept', 'api://'+this.varName);
    xhr.setRequestHeader('Content-Type', 'application/octet-stream');
    xhr.responseType = 'json';
    xhr.onload = function(e) {
      if (this.status == 200) {
        callback(this.response);
      }
    };
    xhr.send(blob);
  }
}

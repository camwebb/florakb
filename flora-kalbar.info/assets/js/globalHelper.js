function remove_tags(html)
  {
       var tmp = document.createElement("DIV");
       tmp.innerHTML = html; 
       return tmp.textContent; 
  }
 function click(){
    var formMenu = document.getElementById('form_wrap');
    formMenu.style.display = 'none';  
  
	 var error = document.getElementById('error');
	
    var emptyBtn = document.getElementById('empty_buttons');
    
    var ok = document.getElementById('ok');
    var no = document.getElementById('no');
    
    emptyBtn.addEventListener("click", function() {
    formMenu.style.display = 'block';
  });
  
    no.addEventListener("click", function() {
    formMenu.style.display = 'none';

  });
  
 
  
  
  }
click();


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
































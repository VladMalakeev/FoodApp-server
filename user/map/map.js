
<script> 

   
	function initMap(){
		
		var element = document.getElementById('main');
		var options = {
		 zoom:12,
		 center: {lat:46.469391, lng:30.740883}
		};
			
		var myMap = new google.maps.Map(element, options);	
		
		var newl='';
		var confl='';
		
		 function show()   
        {   
            $.ajax({   
                url: "functions/form_orders.php",  
				dataType: 'json',				
                cache: false, 
				type:'POST',
				data:{newdata:'true'},
                success: function(data){ 
				
				var js_new = document.getElementById('js_new');	
				js_new.innerHTML = data.new_order.count;	
				js_new.style.color = '#50fcea';
				document.getElementById('js_confirm').innerHTML = data.confirmed.count;
				
				if(newl != data.new_order.count || confl!=data.confirmed.count){
						
				
						if(newl != ''){
						for(var i = 0; i<6;i++){
						setTimeout(blinke_funk, 150);
						
							function blinke_funk() { 
								
								var blinke_speed = 400;
								js_new.style.color = '#f61f1f';
								$("#js_new").fadeOut(blinke_speed).fadeIn(blinke_speed);
								
							}
						}
						
						}
						newl=data.new_order.count;
						confl=data.confirmed.count;
						
						
						var gps = {};
						var icon;
		
						for(var i = 0; i<Number(data.new_order.count); i++){
						gps.lat= Number(data.new_order.data[i].lat);
						gps.lng=Number(data.new_order.data[i].lng);
						icon = '/user/map/marker01.png';
						addMarker(gps,icon);
						}
					
					
						for(var i = 0; i<Number(data.confirmed.count); i++){
						gps.lat= Number(data.confirmed.data[i].lat);
						gps.lng=Number(data.confirmed.data[i].lng);
						icon = '/user/map/marker02.png';
						addMarker(gps,icon);
						}
						
						function addMarker(coordinates,my_icon){
						 var marker = new google.maps.Marker({
						 position:coordinates,
						 map: myMap,
						 icon:my_icon
						}); 
						
						}
		
					}
                }   
            });   
        }  
	 
	 $(document).ready(function(){ 
            show();   
            setInterval(show,5000);   
       }); 
	  
	 var infoWindow = new google.maps.InfoWindow({
		 content:'hello'
	});
	infoWindow.open(myMap, marker);	
 
 }
 </script>
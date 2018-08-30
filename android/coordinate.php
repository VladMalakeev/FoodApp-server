<script>
/*var result = {coordinates:[46.469391,30.740883]};
//document.write(JSON.stringify(  ));
eqfeed_callback(result);
function eqfeed_callback(response) {
  map.data.addGeoJson(response);
}*/
</script>
<form action="/" method="POST">
<input type="text" name="id" placeholder="id"/>
<input type="text" name="lat" placeholder="latitude"/>
<input type="text" name="lng" placeholder="longetude"/>
<select name="owner">
        <option>Vlad</option>
        <option>Igor</option>
        <option>androiduser</option>
        <option>testcafe</option>
</select>
<input type="text" name="dish_name" placeholder="название блюда которое есть в вашем меню" size="40"/>
</select>
<input type="text" name="count" placeholder="1" />
<input type="submit" name="submt"/>
</form>
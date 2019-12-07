<!doctype html>
<html lang="en-us">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <title>Find the Right Place - Team MAPM</title>
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css" />
      <link rel="stylesheet" type="text/css" href="css/sidebar.css" />
      <link rel="stylesheet" type="text/css" href="css/index.css" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


      <style>
         body {
               margin: 0px;
               border: 0px;
               padding: 0px;
               font-family: Helvetica, Arial, sans-serif;
         }
         .container {
               top: 0;
               left: 0;
               right: 0;
               bottom: 0;
               position: absolute;
         }
         #map {
               height: 100%;
               width: 100%;
         }
         #controls {
               position: absolute;
               left: 1em;
               top: 100px;
               z-index: 1000;
               background-color: rgba(200, 200, 200, 0.75);
               /* display: none; */
               width: 200px;
               height: 230px;
               border-radius: 6px;
         }
         #controls div {
               margin: 16px;
               padding: 0.5em;
               background-color: hsl(204, 100%, 72%);
               border: 1px solid black;
               border-radius: 3px;
               color: black;
               box-shadow: 2px 2px 2px black;
         }
         #controls div.off {
               background-color: hsl(204, 50%, 72%);
               color: gray;
         }
         #controls div:hover {
               background-color: dodgerblue;
               margin-top: 16px;
               margin-bottom: 16px;
               color: white;
               box-shadow: 4px 4px 4px black;
         }

         /* Create a custom checkbox */
         .checkmark {
         position: absolute;
         top: 0;
         left: 0;
         height: 15px;
         width: 15px;
         background-color: #eee;
         }

         /* On mouse-over, add a grey background color */
         .container:hover input ~ .checkmark {
         background-color: #ccc;
         }

         /* When the checkbox is checked, add a blue background */
         .container input:checked ~ .checkmark {
         background-color: #2196F3;
         }

         /* Create the checkmark/indicator (hidden when not checked) */
         .checkmark:after {
         content: "";
         position: absolute;
         display: none;
         }

         /* Show the checkmark when checked */
         .container input:checked ~ .checkmark:after {
         display: block;
         }

         /* Style the checkmark/indicator */
         .container .checkmark:after {
         left: 9px;
         top: 5px;
         width: 5px;
         height: 10px;
         border: solid white;
         border-width: 0 3px 3px 0;
         -webkit-transform: rotate(45deg);
         -ms-transform: rotate(45deg);
         transform: rotate(45deg);
         }
      </style>
  </head>

  <body>
    <div id="sidebar">
        <div class="gradient-line"></div>
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&#9776;</a>
        <div class="header">
           <h1>Find the Right Place</h1>
           <p>
              A project by Team MAPM<a target="_blank"
              href="https://www.youtube.com/watch?v=X2ieFd-o4Js">!</a>
           </p>
           <p>
              <a target="_blank" href="https://github.com/maawoo/Project_MAPM/blob/master/README.md">
              How to use this map</a>
          </p>
          <p>
            Hint: You can click on buildings to get more information!
          </p>
          <input type="text" class="slider-input" value="1" id="slider1">
          <input type="text" class="slider-input" value="1" id="slider2">
          <input type="text" class="slider-input" value="1" id="slider3">
          <input type="text" class="slider-input" value="1" id="slider4">
          <input type="text" class="slider-input" value="1" id="slider5">
          <input type="text" class="slider-input" value="1" id="slider6">
          <input type="text" class="slider-input" value="1" id="slider7">
          <input type="text" class="slider-input" value="1" id="slider8">

          <p onclick="doit()">clickme</p>
        </div>
        <div class="tabs">
           <div class="tab-container">
              <div class="tab tab-active" id="tab-1">
                 Layers
              </div>
              <div class="tab tab-active" id="tab-2">
                 Use Cases
              </div>
           </div>
           <div class="tab-bar"></div>
        </div>

        <div class="content">
         <div class="content-group" id="content-group-1">
            <div class="group columns">
               <div class="layer-tiles">
                  <label class="radio-container">
                     <input type="radio" id="noise" name="mode" onclick="toggle('noise')">
                     <span class="checkmark"></span>
                     Noise
                     <div class='legend-bar' style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold" id='noise'>noisy - less noisy</div>
                  </label>
                  <label class="radio-container">
                     <input type="radio" id="nature" name="mode" onclick="toggle('nature')">
                     <span class="checkmark"></span>
                     Nature
                     <div class='legend-bar' style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold" id='nature'>close - further away</div>
                  </label>
                  <label class="radio-container">
                     <input type="radio" id="university" name="mode" onclick="toggle('university')">
                     <span class="checkmark"></span>
                     University
                     <div class='legend-bar'
                     style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold"
                     id='university'>close - further away</div>
                  </label>
                  <label class="radio-container">
                     <input type="radio" id="transport" name="mode" onclick="toggle('transport')">
                     <span class="checkmark"></span>
                     Public Transport
                     <div class='legend-bar'
                     style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold"
                     id='transport'>close - further away</div>
                  </label>
                  <label class="radio-container">
                        <input type="radio" id="culture" name="mode" onclick="toggle('culture')">
                        <span class="checkmark"></span>
                        Culture
                        <div class='legend-bar'
                        style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold"
                        id='culture'>high density - low density</div>
                     </label>
                  <label class="radio-container">
                     <input type="radio" id="shopping" name="mode" onclick="toggle('shopping')">
                     <span class="checkmark"></span>
                     Shopping
                     <div class='legend-bar' style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold" id='shopping'>high density - low density</div>
                  </label>
                  <label class="radio-container">
                      <input type="radio" id="eating" name="mode" onclick="toggle('eating')">
                      <span class="checkmark"></span>
                      Restaurants
                      <div class='legend-bar' style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold" id='eating'>high density - low density</div>
                   </label>
                   <label class="radio-container">
                     <input type="radio" id="nightlife" name="mode" onclick="toggle('nightlife')">
                     <span class="checkmark"></span>
                     Nightlife
                     <div class='legend-bar'
                     style="text-align:center;margin:1px;color:black;font-size:10px;font-weight:bold"
                     id='nightlife'>high density - low density</div>
                  </label>
               </div>
            </div>
         </div>

           <div class="content-group" id="content-group-2">
              <div class="group">
                  <div class="group columns">
                        <div class="layer-tiles">
                            <div>
                                <label class="radio-container">
                                  <input type="radio" id="julia" name="mode" onclick="toggle('julia')">
                                  <span class="checkmark"></span>
                                  Julia
                                  </label>
                            </div>
                            <div>
                               <label class="radio-container">
                                  <input type="radio" id="peter" name="mode" onclick="toggle('peter')">
                                  <span class="checkmark"></span>
                                  Peter
                                 </label>
                            </div>
                            <div>
                               <label class="radio-container">
                                  <input type="radio" id="schillers" name="mode" onclick="toggle('schillers')">
                                  <span class="checkmark"></span>
                                  The Schiller Family
                                 </label>
                            </div>
                            <div>
                               <label class="radio-container">
                                  <input type="radio" id="anna" name="mode" onclick="toggle('anna')">
                                  <span class="checkmark"></span>
                                  Anna & Klaus
                                 </label>
                            </div>
                            <div>
                               <label class="radio-container">
                                  <input type="radio" id="mist" name="mode" onclick="toggle('mist')">
                                  <span class="checkmark"></span>
                                  eigen(mist)
                                 </label>
                            </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

    <div class="container">
        <div id="map"></div>
    </div>

    <!-- leaflet -->
    <script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"></script>

    <!-- Main tangram library -->
    <script src="https://unpkg.com/tangram/dist/tangram.min.js"></script>

    <!-- Demo setup -->
    <script>
         const $ = q => document.querySelector(q);
         const $$ = qq => document.querySelectorAll(qq);

         //setting parameters for the custom sidebar
         const height = $('#content-group-1').clientHeight || $('#content-group-1').offsetHeight;
         $('.content').style.height = (height) + 'px';

function doit()
{
  var passToAjax = {
    'var1' : $('#slider1').value,
    'var2' : $('#slider2').value,
    'var3' : $('#slider3').value,
    'var4' : $('#slider4').value,
    'var5' : $('#slider5').value,
    'var6' : $('#slider6').value,
    'var7' : $('#slider7').value,
    'var8' : $('#slider8').value
  };
  jQuery.ajax(
    {
      type: 'POST',
      url: "writetoyaml.php",
      data: passToAjax,
      success: function(result){
        var layer2 = Tangram.leafletLayer({
              scene: 'test.yaml',
              attribution: '<a href="https://mapzen.com/tangram" target="_blank">Tangram</a> | &copy; OSM contributors | <a href="https://mapzen.com/" target="_blank">Mapzen</a>',

              events: {
               click: function(selection) {
                  if (selection.feature) {
                          showPopup(selection.leaflet_event.latlng,
                             selection.feature.properties.noise_db,
                             selection.feature.properties.nature_dis,
                             selection.feature.properties.univer_dis,
                             selection.feature.properties.onpv_dis,
                             selection.feature.properties.cultur_dis,
                             selection.feature.properties.shoppi_dis,
                             selection.feature.properties.eating_dis,
                             selection.feature.properties.nightl_dis
                             );
                       } else {
                           map.closePopup();
                       }
                   }
               }
           });
           console.log(layer2);
           layer2.addTo(map);
  }});
}


        /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
         function closeNav() {
            h=document.getElementById("sidebar").style.height;
            if (h==='25px'){
               document.getElementById("sidebar").style.height = "80%";
            } else {
            document.getElementById("sidebar").style.height = "25px";
            }
         }

         //uncheck all checkboxes by default
         function uncheck(){
            var checkboxes = document.getElementsByTagName('input');
            for (var i=0; i<checkboxes.length; i++)  {
            if (checkboxes[i].type == 'radio')   {
               checkboxes[i].checked = false;
            }
            }
         };

         // Resize map to window
         function resizeMap() {
               document.getElementById('map').style.width = window.innerWidth + 'px';
               document.getElementById('map').style.height = window.innerHeight + 'px';
               map.invalidateSize(false);
         }

         // activate layers by clicking on the name
         function toggle(layerName) {
               box=document.getElementById(layerName);
               if (box.checked==true){
                  layer.scene.config.layers._test_xyz[layerName].visible=true
                  var layerNames=document.getElementsByName('mode')
                  for (var i=0; i<layerNames.length; i++){
                     if (layerNames[i].id != layerName){
                        layer.scene.config.layers._test_xyz[layerNames[i].id].visible=false
                     }
                  }
               } else {
                  layer.scene.config.layers._test_xyz[layerName].visible=false
               };
               layer.scene.updateConfig();
         }

         // Create color bars for the legend
         function setLegend() {
            // alert(layer.scene.config.layers._test_xyz[layerName].draw._polygons_inlay.color);
            // rgb=layer.scene.config.layers._test_xyz[layerName].draw._polygons_inlay.color;
            var legendEntries = document.getElementsByClassName('legend-bar');
            for (var i=0; i<legendEntries.length; i++){
               if (legendEntries[i].id=='nature'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(34,135,34,1), rgba(34,135,34,0))';
               } else if (legendEntries[i].id=='culture'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(255,140,0,1), rgb(255,140,0,0))';
               } else if (legendEntries[i].id=='nightlife'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(0,191,255,1), rgba(0,191,255,0))';
               } else if (legendEntries[i].id=='noise'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(255,0,0,1), rgba(255,0,0,0))';
               } else if (legendEntries[i].id=='eating'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(102,102,255,1), rgba(102,102,255,0))';
               } else if (legendEntries[i].id=='transport'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(139,10,80,1), rgba(139,10,80,0))';
               } else if (legendEntries[i].id=='shopping'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(178,34,34,1), rgba(178,34,34,0))';
               } else if (legendEntries[i].id=='university'){
                  legendEntries[i].style.background = 'linear-gradient(to right, rgba(127,255,0,1), rgba(127,255,0,0))';
               }
            }
         };


         var map = L.map('map', {zoomControl: true});

         map.zoomControl.setPosition('topright');

         window.addEventListener('load', uncheck);
         window.addEventListener('load', setLegend);
         window.addEventListener('resize', resizeMap);


         var layer = Tangram.leafletLayer({
               scene: 'scene.yaml',
               attribution: '<a href="https://mapzen.com/tangram" target="_blank">Tangram</a> | &copy; OSM contributors | <a href="https://mapzen.com/" target="_blank">Mapzen</a>',

               events: {
                click: function(selection) {
                   if (selection.feature) {
                           showPopup(selection.leaflet_event.latlng,
                              selection.feature.properties.noise_db,
                              selection.feature.properties.nature_dis,
                              selection.feature.properties.univer_dis,
                              selection.feature.properties.onpv_dis,
                              selection.feature.properties.cultur_dis,
                              selection.feature.properties.shoppi_dis,
                              selection.feature.properties.eating_dis,
                              selection.feature.properties.nightl_dis
                              );
                        } else {
                            map.closePopup();
                        }
                    }
                }
            });
         layer.addTo(map);

         // center of jena
         map.setView([50.92, 11.59], 13);

         // Function to round numbers
         function round(num, decimals) {
            var t = Math.pow(10, decimals);
            return (Math.round((num * t) + (decimals>0?1:0)*(Math.sign(num) * (10 / Math.pow(100, decimals)))) / t).toFixed(decimals);
            }

        // Pop-ups
        var popup = L.popup({closeButton: false});
        function showPopup(latlng, noise, nature, university, transport, culture, shopping, restaurants, nightlife) {
               if(transport===null){
              transport= '> 200'
           } else{
              transport=round(transport, 0)
           }
           if(noise===null){
              noise='no data available'
           }else{
              noise=noise + ' dB'
           }
               popup
                .setLatLng(latlng)
                .setContent(
                   'Noise level: ' + noise +
                   '<br/>Distance to Nature:' + ' ' + round(nature, 0) + ' m' +
                   '<br/>Distance to University:' + ' ' + round(university, 0) + ' m' +
                   '<br/>Distance to Public Transport:' + ' ' + transport + ' m' +
                   '<br/>Culture venues nearby:' + ' ' + culture +
                   '<br/>Shops nearby:' + ' ' + shopping +
                   '<br/>Restaurants nearby:' + ' ' + restaurants +
                   '<br/>Nightlife venues nearby:' + ' ' + nightlife
                   )
                .openOn(map);
        }

         //Tab control for sidebar
         const tabs = $$('.tab');
         tabs.forEach(t => t.onclick = tabify)
         function tabify(evt) {
            tabs.forEach(t => t.classList.remove('tab-active'));
            if (evt.target.id === 'tab-1') {
               $('.tab-bar').style.transform = 'translateX(0)';
               evt.target.classList.add('tab-active');
               $('#content-group-1').style.transform = 'translateX(0)';
               $('#content-group-2').style.transform = 'translateX(100%)';
            } else {
               $('.tab-bar').style.transform = 'translateX(100%)';
               evt.target.classList.add('tab-active');
               $('#content-group-1').style.transform = 'translateX(-100%)';
               $('#content-group-2').style.transform = 'translateX(0)';
            }
         }

      </script>
   </body>
</html>

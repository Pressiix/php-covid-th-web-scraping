        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="text/javascript" src="data.json"></script>
        <script>
            
        const proxyurl = "https://cors-anywhere.herokuapp.com/";
        const url = "http://covid19.th-stat.com/"; // site that doesn’t send Access-Control-*
        fetch(proxyurl + url) // https://cors-anywhere.herokuapp.com/https://example.com
        .then(response => response.text())
        .then(contents => { this.a(contents) })
            
            
        function readTextFile(file, callback) {
            var rawFile = new XMLHttpRequest();
            rawFile.overrideMimeType("application/json");
            rawFile.open("GET", file, true);
            rawFile.onreadystatechange = function() {
                if (rawFile.readyState === 4 && rawFile.status == "200") {
                     callback(rawFile.responseText);
                }
            }
            rawFile.send(null);
        }

        function a(contents){
            readTextFile("province.json", function(text){
                var data = JSON.parse(text);
                count(contents,data);
            });
        }
        
        
         function count(contents,province)
        {
            var json = "";
            var counta =[];
                for(i=1;i<=Object.keys(province).length;i++)
                {
                    var string = ",\"province_name_en\":\""+province[i]['province']+"\"};";
                    var find = new RegExp(string, 'g');
                    counta[province[i]['province']] = (contents.match(find) || []).length;
                    
                    json += "\""+i+"\":{\""+province[i]['province']+"\":\""+counta[province[i]['province']]+"\"}";
                    json += (i!==Object.keys(province).length ? "," : "");
                }     
                json = "{"+json+"}";      

                request = $.ajax({
                    url: "store",
                    type: "post",
                    data: { json : json }
                });

                // Callback handler that will be called on success
                request.done(function (response){
                     // Log the response to the console
                    console.log("Response: "+response);
                    $("body").append(response);
                });
        }
        </script>
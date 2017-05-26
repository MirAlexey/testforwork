<?php


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Пользователи на сайте</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="ru" />
    <link rel="stylesheet" type="text/css" href="test.css" />
    <script type="text/javascript" src="xmlhttprequest.js"></script>
    <script type="text/javascript" src="json2.js"></script>
    <script src="jquery.js"></script>
    <script type="text/javascript">
        var data;
        var curtr;
        $(document).ready(function(){
          console.log("start");
            $("#addBtn").click(function(e){
               var h=document.documentElement.clientHeight;
               var blok = $("<div>").attr('id','t_blok').attr("style",'width: 100% ; height :100%; z-index: 2;background-color: rgba(200,200,200,0.3);position: absolute;top:0;left:0');
               $('body').append(blok);
               $("#divDataEdit").css('display','block');

            });
            $("#editBtn").click(function(e){

            });
            $("#delBtn").click(function(e){
                var req = getXmlHttpRequest();
                req.open('POST','delData.php',true);
                req.onreadystatechange = function(){
                    if (req.readyState != 4) return;
                    var res = {'result': true,'data':''};
                    res = JSON.parse(req.responseText);
                    if (res['result'] == false){
                        alert(res['data']);
                    }
                }
            });


          var req = getXmlHttpRequest();
          var url = window.location.protocol + "//"+ window.location.host + "/getData.php";
          req.open('POST','getHead.php',true);
          req.onreadystatechange = function(){
                if (req.readyState != 4) return;
                console.log(req.responseText);
                data = JSON.parse(req.responseText);
                console.log(req.responseText);
                var tr = $("<tr>");
                for(var i = 0 ; i< data.length; i++){
                    var th = $("<th>").append(data[i][0]);
                    tr.append(th);
                }
                $("table thead").append(tr);
                var tableEdit = $('#divDataEdit tbody');
                for(var i = 0 ; i< data.length; i++){
                    if(data[i][3] == "PRI") continue;
                  var td = $("<td>").append($("<p>").append(data[i][0]).css("padding","0 2px"));
                  var td1 = $("<td>").append($("<input>").css('width','98%').css('border-width','0').css("padding",'0 1%'));
                  tableEdit.append($("<tr>").append(td).append(td1));
                }
                tableEdit.append($("<button>Добавить</button>").click(function(){
                    var reqAdd = getXmlHttpRequest();
                    reqAdd.open('POST','addData.php',true);
                    var bodyPost = "";
                    $("#tableDataEdit tr").each(function(){
                        var p = $(this).find('p').text();
                        var val = $(this).find('input').val();
                        bodyPost+=encodeURIComponent(p)+"="+encodeURIComponent(val)+"&"
                    });
                    reqAdd.onreadystatechange = function(){
                        if (reqAdd.readyState != 4) return;
                        var res = {'result': true,'data':''};
                        res = JSON.parse(reqAdd.responseText);
                        if (res['result'] == false){
                            alert(res['data']);
                        }else{
                            var table = $("#tableData");
                            var list = res['data'][0];
                            console.log(data.length);
                            var tr = $('<tr>');
                            for(var j = 0; j < data.length; j++) {
                                    var el;
                                    if(data[j][3] == 'PRI'){
                                        el = $('<span>').append(list[j]);
                                    }else{
                                        el = $('<input>');
                                        var x = list[j].length;
                                        x = x>5?x:5;
                                        x = x>80?80:x;
                                        el.attr('style','width: ' + x +'em');
                                        el.val(list[j]);
                                    }
                                    var td = $('<td>').append(el);
                                    tr.append(td);
                            }
                                tr.click(function(event){
                                    curtr = $(this);

                                });
                                table.append(tr);
                            $("#divDataEdit").css("display", "none");
                            $("#t_blok").remove();
                        }
                    };
                    reqAdd.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
                    reqAdd.send(bodyPost+"ajax=true");
                    return;

                }));


              var req1 = getXmlHttpRequest();
              req1.open('POST','getData.php',true);
              req1.onreadystatechange = function(){
                  if (req1.readyState != 4) return;
                  console.log(JSON.parse(req1.responseText));
                  var list = JSON.parse(req1.responseText);
                  var table = $("#tableData");
                  for(var i = 0; i < list.length; i++){
                      var tr = $('<tr>');
                      for(var j = 0; j < list[i].length; j++) {
                          var el;
                          var x;
                          x = list[i][j].length;
                          if(data[j][3] == 'PRI'){
                              el = $('<span>').append(list[i][j]);
                          }else{
                              x = x>5?x:5;
                              x = x>80?80:x;
                              el = $('<input>').val(list[i][j]);
                          }
                          var td = $('<td>').attr('style','width: ' + x +'em').append(el);
                          tr.append(td);
                      }
                      tr.click(function(event){
                         if(!$.isEmptyObject(curtr)){
                             $(curtr).removeClass("activtr");
                         }
                         curtr = $(this);
                         $(this).addClass("activtr");

                      });
                      table.append(tr);
                  }
              };
              req1.setRequestHeader("Content-Type", "text/plain");
              req1.send();
                };
          req.setRequestHeader("Content-Type", "text/plain");
          req.send();
        });


        function EditData(){

        }


    </script>
</head>
<body>

<!--  редактирования данных -->

    <div id="divDataEdit">
        <table id = "tableDataEdit">
            <caption>Добавить данные</caption>
            <tbody></tbody>
        </table>
    </div>


<!-- данные -->
<div id="divData" class="block">
    <table id = "tableData">
        <caption>Данные</caption>
        <thead>

        </thead>
        <tbody>

        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">

            </td>
        </tr>
        </tfoot>
    </table>
    <button id="addBtn">Добавить</button>
    <button id="editBtn">Редактировать</button>
    <button id="delBtn">Удалить</button>
</div>


</body>
</html>


<?php

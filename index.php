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
        function changeInput(){
            var td = $(curtr).find('td');
            var dataPost = "";
            var v;
            for(var i=0;i<td.length;i++ ){
                if(data[i][3] == 'PRI' || data[i][1] == 'timestamp'){
                    v = $(td[i]).find('p').text();
                }else{
                    v = $(td[i]).find('input').val();
                }
                dataPost+=encodeURIComponent(data[i][0])+"="+encodeURIComponent(v)+"&";
            }
            dataPost+="ajax=true";
            var reqEdit = getXmlHttpRequest();
            reqEdit.open('POST','EditData.php',true);
            reqEdit.onreadystatechange = function(){
                if (reqEdit.readyState != 4) return;
                var res = {'result': true,'data':''};
                res = JSON.parse(reqEdit.responseText);
                for(var i=0;i<td.length;i++ ){
                    if(data[i][3] == 'PRI' || data[i][1] == 'timestamp'){
                        console.log(res['data'][0][i]);
                        $(td[i]).find('p').text(res['data'][0][i]);
                    }else{
                        console.log(res['data'][0][i]);
                        $(td[i]).find('input').val(res['data'][0][i]);
                    }
                }
            };
            reqEdit.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
            reqEdit.send(dataPost);

        }
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
                var reqDel = getXmlHttpRequest();
                reqDel.open('POST','delData.php',true);
                reqDel.onreadystatechange = function(){
                    if (reqDel.readyState != 4) return;
                    var res = {'result': true,'data':''};
                    res = JSON.parse(reqDel.responseText);
                    if (res['result'] == false){
                        alert(res['data']);
                    }else{
                        $(curtr).remove();
                        curtr = null;
                    }
                };
                var td = $(curtr).find('td');
                var dataPost = data[0][0] + "=" + $(td[0]).find('p').text()+'&ajax=true';
                reqDel.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
                reqDel.send(dataPost);
            });


          var req = getXmlHttpRequest();
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
                    if(data[i][3] == "PRI" || data[i][1] == 'timestamp') continue;
                  var td = $("<td>").append($("<p>").append(data[i][0]).css("padding","0 2px"));
                  var td1 = $("<td>").append($("<input>"));
                  tableEdit.append($("<tr>").append(td).append(td1));
                }
              $("#divDataEdit").append($("<button>Добавить</button>").click(function(){
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
                                    if(data[j][3] == 'PRI' || data[j][1] == 'timestamp'){
                                        el = $('<p>').append(list[j]);
                                    }else{
                                        el = $('<input>').change(function(){changeInput();});
                                        var x = list[j].length;
                                        x = x>5?x:5;
                                        x = x>80?80:x;
                                        el.val(list[j]);
                                    }
                                    var td = $('<td>').append(el);
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
                            $("#divDataEdit").css("display", "none");
                            $("#t_blok").remove();
                        }
                    };
                    reqAdd.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    reqAdd.send(bodyPost+"ajax=true");
                    return;

                }));
              $("#divDataEdit").append($("<button>Отмена</button>").click(function(){
                  $("#divDataEdit").css("display", "none");
                  $("#t_blok").remove();
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
                          if(data[j][3] == 'PRI' || data[j][1] == 'timestamp'){
                              el = $('<p>').append(list[i][j]);
                          }else{
                              x = x>5?x:5;
                              x = x>80?80:x;
                              el = $('<input>').val(list[i][j]).change(function(){changeInput();});
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
    <button id="delBtn">Удалить</button>
</div>


</body>
</html>


<?php

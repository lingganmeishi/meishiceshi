<!doctype html>
<html>
<head>
    <title>美食测试</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, minimum-scale=0.75">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
    <script>
        $(document).on("mobileinit", function () {
          $.mobile.hashListeningEnabled = false;
          $.mobile.pushStateEnabled = false;
          $.mobile.changePage.defaults.changeHash = false;
        });
    </script>

    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="javascript/jquery.screwdefaultbuttonsV2.min.js"></script>
</head>
<style>
.ui-block-rating {
      width:75%;
}

.main_img {
      text-align: center;
      margin: 0 auto 
}
.main_img {
      width: 100%;
}
.rateit {
      text-align: center;
}

.ui-checkobx {
   text-align: center;
   width: 40%;
}

img {
  max-height: 80%;
  max-width: 95%;
}

</style>

<script type="text/javascript">
   $(function(){
      $('input:checkbox').screwDefaultButtons({
          width: 64,
          height: 64
      });
			
   });
</script>

<body>

<?php
$php_name = $_SERVER['SCRIPT_NAME'];
$file_names = file_get_contents("/var/www/file_title_count.list");
$lines_arr = preg_split('/\n|\r/',$file_names);
$num_newlines = count($lines_arr);
$page_n = 12;
$file_list = "";
$count_list = "";

$index_arr = array_fill(0,$page_n,-1);
$min_index = 0;
for( $i=1; $i<= $page_n; $i++ )
{
  $max_index = ($num_newlines - $min_index)/($page_n - $i+1) + $min_index;  
  if ($max_index >= $num_newlines) 
  {
     $max_index = $num_newlines -1 ;
  }
  $j=rand($min_index,$max_index);
  $index_arr[$i-1] = $j;
  $min_index = $j+1;
  $line = $lines_arr[$j];
  $tokens = preg_split('/\t/',$line);
  $file=$tokens[0];
  $name=$tokens[1];
  $count_list = $count_list.$j.",";
  $file_list = $file_list.$file.",";

?>

    <div id="page<?=$i?>" data-role="page">
 
        <div data-role="header">
            <p align="center">测测看你是美食达人吗？</p>
        </div><!-- /header -->
 
        <div data-role="content">
            <div class="main_img">
              <div><img src="images/resized/<?=$file?>" max-width:100% /></div>
              <div><p><?=$name?></p></div>
              <div align="center">
                 <table>
                   <tr>
                    <td width="25%"><input class="ui-checkbox" type="checkbox"  data-sdb-image="url('images/yes.vertical.png')" name="check_yes" id="check_yes_<?=$i?>" /> </td> 
                    <td width="25%">吃过 </td>
                    <td width="25%"><input class="ui-checkbox" type="checkbox"  data-sdb-image="url('images/no.vertical.png')" name="check_no" id="check_no_<?=$i?>" /> </td>
                    <td width="25%">没吃过</td>
                   </tr>
                 </table>
              </div>
              </div>
              <script type="text/javascript">
                 var response = new String();
                 $("#check_yes_<?=$i?>").bind('change', function (e) {

                    response = response + "1";
                    response = response + ",";
                    <?php if ($i<$page_n): ?> 
                        window.location.href = "#page<?=$i+1?>";
                    <?php else: ?>
                        response = response + ";";
                        response = response + "<?php echo $count_list; ?>"+";";
                        response = response + "<?php echo $file_list; ?>";
                        response = "answer=" + response;

                        var ajax =new XMLHttpRequest(); 
                        ajax.open("GET",encodeURI("compare.php?"+response),true);
//                        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                        ajax.send(response);

                        ajax.onreadystatechange=function()
                        {                     
                           if (ajax.status == 200 && ajax.readyState ==4) {
document.getElementById("result").innerHTML = ajax.responseText;
window.location.href = "#result";
//                              window.location.href = "compare.php";
//                              document.write(ajax.responseText);
                           }
                        }
                        //alert(response);
                    <? endif; ?>    
                 });


                 $("#check_no_<?=$i?>").bind('change', function (e) {

                    response = response + "0";
                    response = response + ",";
                    <?php if ($i<$page_n): ?> 
                        window.location.href = "#page<?=$i+1?>";
                    <?php else: ?>
                        response = response + ";";
                        response = response + "<?php echo $count_list; ?>"+";";
                        response = response + "<?php echo $file_list; ?>";
                        response = "answer=" + response;

                        var ajax =new XMLHttpRequest(); 
                        ajax.open("GET",encodeURI("compare.php?"+response),true);
                        ajax.send(response);

                        ajax.onreadystatechange=function()
                        {                     
                           if (ajax.status == 200 && ajax.readyState ==4) {
                              document.getElementById("result").innerHTML = ajax.responseText;
                              window.location.href = "#result";

                           }
                        }
                    <? endif; ?>    
                 });

              </script>
            </div>



        </div><!-- /content -->


    </div><!-- /page -->



<?php
}
?>

<div id="result" data-role="page">

</div><!-- /page -->

<script>

window.onload = function ()
{
   if (location.href.indexOf("#")>-1)
   {
     window.location.href = "";
   }
}

 function myFunction() {

    if (typeof WeixinJSBridge == "undefined") {
         document.getElementById("demo").innerHTML = "Can't find WeixinJSBridge";
    } else {
         WeixinJSBridge.invoke('getNetworkType', {}, function (e) {
             document.getElementById("demo").innerHTML = e.err_msg;
         });
        document.getElementById("wechat_desc").innerHTML = document.getElementById("beat_pct").innerHTML;
    }
  }

  $( document ).delegate("#result", "pagecreate", function() {
     var wechat_desc = "测测看你是美食达人吗？";

     if (document.getElementById("beat_pct")){
        wechat_desc = document.getElementById("beat_pct").innerHTML;
     }
//  alert(wechat_desc);
     if (typeof WeixinJSBridge != "undefined") {
        WeixinJSBridge.on('menu:share:appmessage', function(argv){
            WeixinJSBridge.invoke('sendAppMessage',{
                                "appid":       "",
                                "img_url":     "http://ec2-54-185-24-168.us-west-2.compute.amazonaws.com//thumb/dishes/1/thumb.1053a4914478f.jpg",
                                "img_width":   "120",
                                "img_height":  "120",
                                "link":        "http://ec2-54-185-24-168.us-west-2.compute.amazonaws.com/game.php",
                                "desc":        wechat_desc, 
//                                "desc":        "测测看你是美食达人吗？",  
                                "title":        "美食测试"
            }, function(resp){

               document.getElementById("demo").innerHTML = resp.err_msg; 
        });
       });

     }
  
  });
</script>

</body>
</html>

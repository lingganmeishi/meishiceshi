
<div data-role="header">
  <p align="center">测测看你是美食达人吗？</p>
</div><!-- /header -->
<div data-role="content">


<?php 
/*
  This cgi only need to return a part of html code.
  The returned HTML will show in the "result" section in the result page
*/

if ( $_REQUEST['answer'] )
{

# parse input
   $inputs = preg_split("/;/",$_REQUEST['answer']);
   $rating = preg_split("/,/",$inputs[0]);
   $index = preg_split("/,/",$inputs[1]);
   $file = preg_split("/,/",$inputs[2]);

# get the last valid index, since the index should be sorted from small to big, the last valid index is also the max
   $max_index = $index[0];
   if (count($rating)>1)
   {   
      $max_index = $index[count($rating)-2];
   }

   $difficulty_weight = 5; # 5 point as the maximum extra bonux point 
   $check_score = 10; # if the answer is one, give 10 point

   $max_weight = 0;
   $n=0;
   $difficulty_bonus = 0;
   for ($i=0; $i<count($rating)-1; $i++) {
      # rating can only be 0 or 1
      $n +=intval($rating[$i]);
/*
  Since the samller index means more popular dish, we give less difficult bonus for such question
  The weight is index/max_index
*/

      $bonus = ($difficulty_weight * ($index[$i]+1) / $max_index );
      $max_weight += $bonus; # max_weight is the sum of all bonus for the questions
      $difficulty_bonus += intval($rating[$i])*$bonus;  # difficulty_bonus only adds bonus for questions with 1 as answer
   }
   
   $score = $n*($check_score + $difficulty_bonus);
   $score /= (count($rating)-1) * ($check_score + $max_weight); # make it in the range of 0 - 1
   $score = sqrt($score)*0.99; # make it in the range of 0.1 -0.99
   $score *= $check_score;
   $score *= (count($rating)-1); 

   $pct = 0;
   $pct += sqrt($n/(count($rating)-1));
   $pct += sqrt($score/($check_score*(count($rating)-1)));
   $pct /=2;
   $pct = $pct * 100;
   
   if ($score<0) {
     $score = 0;
   }
   print_r("<p>You have had $n out of ".(count($rating)-1)." dishes.</p>\n");
   print_r("<p>Your score is: ");
   printf("%.0f",$score);
   print_r("</p>\n");
   print_r("<p id=\"beat_pct\">你打败了 ");
   printf("%.0f",$pct);
   print_r("%的玩家.</p>");
}
?>

<a href="#" data-role="button" data-theme="none" data-corners="false" data-shadow="false"  data-inline="true" onclick="myFunction()"><img src="images/info.png" alt="up" /></a>

<p id="wechat_desc"></p>
<p id="demo"></p>



</div><!-- /content -->

	<div data-role="footer">
	</div><!-- /footer -->


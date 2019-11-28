<!DOCTYPE html>


<html>

  <head>

  </head>


  <body>

    <?php

    extract($_REQUEST);

    if(isset($ticker)){
      $ticker= strtoupper($ticker);

      $reuters= "https://www.reuters.com/finance/stocks/overview/".$ticker;
      $nasdaq= "https://www.nasdaq.com/symbol/".$ticker;
      $yahoo= "https://finance.yahoo.com/quote/".$ticker;

      $reutResult= file_get_contents($reuters);
      $nyArr1= explode( 'font-size: 23px;' '>', $reutResult);
      if($nyArr1[1]){
        $nyArr2= explode( "</span>", $nyArr1[1]);
        if($nyArr2[1]){
          $nyPrice= $nyArr2[0];
        }
      }


      if($nyPrice){
        $jsonResponse= '{"price": "'.floatval($nyPrice).'", "source": "Reuters"}';
        echo json_encode($jsonResponse);
        return;
      }



      else{
        $nasResult= file_get_contents($nasdaq);
        $nasArr1= explode( '_LastSale1' '>', $nasResult);
        if($nasArr1[1]){
          $nasArr2= explode( "</label>", $nasArr1[1]);
          if($nasArr2[1]){
            $nasPrice= $nasArr2[0];
          }
        }

        if($nasPrice){
          $nasPrice= str_replace("$", "", $nasPrice);
          $nasPrice= str_replace(" ", "", $nasPrice);
          $jsonResponse= '{"price": "'. $nasPrice.'", "source": "Nasdaq"}';
          echo json_encode($jsonResponse);
        }




        else{
          $yahResult= file_get_contents($yahoo);

          $ticker= strtolower($ticker);
          $yahArr1 = explode( 'id="yfs_l84_'.$ticker.'">', $yahResult);
          if($yahArr1[1]){
            $yahArr2= explode( " ", $yahArr1[1]);

            if($yahArr2[1]){
              $yahPrice= $yahArr2[0];
            }
          }


          if($yahPrice){
            $jsonResponse= '{"price": "'.floatval($yahPrice).'", "source": "Yahoo"}';
            echo json_encode($jsonResponse);

          }


          else{
            $jsonResponse= '{"error": "Y"Please make sure you passed a valid stock ticker symbol. (e.g. yoursite.com/?ticker=GOOG).
              If this error persists, please update this script with the latest version (https://github.com/m140v/Real-time-Stock-Price-API/).
              The source site might have been reformatted."}';
              echo json_encode($jsonResponse);
              return;
            }


          }


        }

      }



      else{
        $jsonResponse= '{"error": "please send a ticker symbol in your request with the key 'ticker'."}';
        echo json_encode($jsonResponse);
        return;
      }


      ?>



    </body>


</html>

<?php 
error_reporting(1);
function printChart($chartID,$title,$dataArrStr,$labelsArrStr,$yMin,$yMax)
{
    $chart = "<canvas class=\"chart\" id=\"$chartID\" width=\"400\" height=\"400\"></canvas>   
    <script class='chartScript'>
    (function (){
    var ctx = document.getElementById('$chartID').getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [$labelsArrStr],
        datasets: [{
            label: '$title',
            data: [$dataArrStr],
            backgroundColor: [
                'rgba(255, 99, 132, 0.0)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                '#FF7F50'
            ],
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            y: {
                suggestedMin: $yMin,
                suggestedMax: $yMax
            }
        },
        aspectRatio : 16/9, 
        maintainAspectRatio:false
    }
    });})();
    
    </script>";

    echo $chart;
}
//Creates an array that can be used to print the charts
function createChartDataArr($startIndex = 0 )
{
    
    $startIndex = $startIndex < 0 ? 0 : $startIndex;
    $GLOBALS["internalRequest"] = true;   
    $_GET['q'] ="user/workouts/logs/exercises"; 
    $_GET['resultsNum'] =(int)$startIndex+4;    
    $URL  = ['user','workouts','logs','exercises'];
    require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");    
    $exerciseList = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);
    $chartDataArray = [null,null,null,null];

    if(!isset($exerciseList['HttpBody']))
    {
      return $chartDataArray;
    }
    $startIndex = $startIndex + 4 > count($exerciseList['HttpBody']) ? count($exerciseList['HttpBody'])-4 : $startIndex;
    $startIndex = $startIndex < 0 ? 0 : $startIndex;
    $exercises = [];

    for($k = 0; $k < count($exerciseList['HttpBody']); $k++ )
    {
        if(array_key_exists(3,$exercises)){break;}
        $exercises[$k] = $exerciseList['HttpBody'][$startIndex++];
    }

    for($i = 0; $i < count($exercises); $i++ )
    {
        $exerciseName = $exercises[$i]['exerciseName'];        
        $GLOBALS["internalRequest"] = true;   
        $_GET['q'] ="user/workouts/logs/exercises"; 
        $_GET['exName'] = $exerciseName;   
        $_GET['resultsNum'] =100; 
        $URL  = ['user','workouts','logs','exercises'];
        require_once($_SERVER["DOCUMENT_ROOT"] . "/workoutAPP/src/api/index.php");    
        $exerciseLogs = ApiRequest("GET",$URL,"",$_GET,$_COOKIE);

        if(isset($exerciseLogs['HttpBody']))
        {
            $exerciseLogs =  $exerciseLogs['HttpBody'];
        }
        else
        {
            continue;
        }
        
        $ORMarr = [];
        $labelsArr = [];
        $yMin = (int)$exerciseLogs[0]['oneRepMax'];
        $yMax = (int)$exerciseLogs[0]['oneRepMax'];
        $title = "";

        for($j = 0; $j < count($exerciseLogs); $j++ )
        {
            if($exerciseLogs[$j]['oneRepMax'] > 0 || !isset($chartDataArray[$i]['title']))
            {
                array_push($ORMarr,$exerciseLogs[$j]['oneRepMax']);
                array_push($labelsArr,"''");
                $yMin = (int)$exerciseLogs[$j]['oneRepMax'] < $yMin ? (int)$exerciseLogs[$j]['oneRepMax'] : $yMin;
                $yMax = (int)$exerciseLogs[$j]['oneRepMax'] > $yMax ? (int)$exerciseLogs[$j]['oneRepMax'] : $yMax;
                $title = $exerciseLogs[$j]['exerciseName']." 1RM";
            }            
        }
    
        $chartDataArray[$i]['dataStr'] = implode(",",$ORMarr);
        $chartDataArray[$i]['labelsStr'] = implode(",",$labelsArr); 
        $chartDataArray[$i]['yMin'] = floor($yMin-$yMin*0.3);
        $chartDataArray[$i]['yMax'] = floor($yMax+$yMax*0.3);
        $chartDataArray[$i]['title'] = $title;
    }

    return $chartDataArray;       

    }
if(isset($_GET['offset']) && is_int((int)$_GET['offset']))
{
    $chartData = createChartDataArr($_GET['offset']);
    // echo json_encode($chartData);
    // exit();   
}
else
{
    $chartData = createChartDataArr();    
}
?>
<div id="backBtnDiv" onclick='changeActivePage("right");'>
        <span id='backBtn'>Back</span>
    </div>
<div id='chartsContainer'>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.1.0/dist/chart.min.js"></script>
<div id='chartsTitleContainer'>
    <h4 id='chartsTitle'>Strength progress</h3>
    <h6 id='progressMessage'>Track your progress and optimize your training.</h6>
</div>
<div id='chartsDiv'>
    
    <div class='chart' id=chart1>
        <?php 
            $i = 0;
            if($chartData[$i] == null)
            {
                echo "Not Enough data";
            }
            else
            {
                printChart($chartData[$i]['title'],$chartData[$i]['title'],$chartData[$i]['dataStr'],$chartData[$i]['labelsStr'],$chartData[$i]['yMin'],$chartData[$i]['yMax']);
            }
        ?>
    </div>
    <div class='chart' id=chart2>
        <?php 
            $i = 1;
            if($chartData[$i] == null)
            {
                echo "Not Enough data";
            }
            else
            {
                printChart($chartData[$i]['title'],$chartData[$i]['title'],$chartData[$i]['dataStr'],$chartData[$i]['labelsStr'],$chartData[$i]['yMin'],$chartData[$i]['yMax']);
            }
        ?>
    </div>
    <div class='chart' id=chart3>
        <?php 
            $i = 2;
            if($chartData[$i] == null)
            {
                echo "Not Enough data";
            }
            else
            {
                printChart($chartData[$i]['title'],$chartData[$i]['title'],$chartData[$i]['dataStr'],$chartData[$i]['labelsStr'],$chartData[$i]['yMin'],$chartData[$i]['yMax']);
            }
        ?>
    </div>
    <div class='chart' id=chart4>
        <?php 
            $i = 3;
            if($chartData[$i] == null)
            {
                echo "Not Enough data";
            }
            else
            {
                printChart($chartData[$i]['title'],$chartData[$i]['title'],$chartData[$i]['dataStr'],$chartData[$i]['labelsStr'],$chartData[$i]['yMin'],$chartData[$i]['yMax']);
            }
        ?>
    </div>
</div>
</div>
            

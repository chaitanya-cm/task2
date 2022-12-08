<?php 
include_once 'db.php';

// header("location:index.php?company=");
$name = 'NIFTY50';
$name1 = 'NIFTY500';
$que = "SELECT * FROM chartdata WHERE comapny = '$name'";
$que1 = "SELECT * FROM chartdata WHERE comapny = '$name1'";

$result = mysqli_query($conn, $que);
$result1 = mysqli_query($conn, $que1);
$count=mysqli_num_rows($result);
$count1=mysqli_num_rows($result1);

	$wiberank = array();
	$date = array();
	$stockticker = array();

  $wiberank1 = array();
  $date1 = array();
  $stockticker1 = array();
if($count> 0){
		while ($row = mysqli_fetch_array($result)) {
			$wiberank[] = $row['wiberank'];
			$date[] = $row['date'];
			$stockticker[] = $row['stockticker']; 
	}
}else{
	echo "No records...!";
}

if($count> 0){
    while ($row = mysqli_fetch_array($result1)) {
      $wiberank1[] = $row['wiberank'];
      $date1[] = $row['date'];
      $stockticker1[] = $row['stockticker']; 
  }
}else{
  echo "No records...!";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Chart.js</title>
</head>
<body>
	<form method="POST" action="index.php?q=1&company=">
    <input type="text" name="cname" id="cname" placeholder="Enter Company Name.."  required />
    <input type="submit" name="submit" />
  </form>
<div class="ChartBox">
  <canvas id="myChart1"></canvas>
</div>
</body>

<?php 
  if(@$_GET['q']== 1){

    $cname = $_POST['cname'];

    echo $cname;
    $que = "SELECT * FROM chartdata WHERE comapny = '$cname'";
   

    $result = mysqli_query($conn, $que);
    $count=mysqli_num_rows($result);
    
    $newwiberank = array();
    $newdaten = array();
    // $newstockticker = array();

    if($count> 0){
    while ($row = mysqli_fetch_array($result)) {
      $newwiberank[] = $row['wiberank'];
      $newdate[] = $row['date'];
      // $newstockticker[] = $row['stockticker']; 
  }
}else{
  echo "No records...!";
}
    // header("location:index.php?q=1&company='$cname'");
  }

?> 

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript">
    $(function(){
      $('#cname').autocomplete({
        source: 'search.php'
      });
    });
</script>
<script>
  window.onload = (event) => {
    // console.log("Hii");
  const rank = <?php echo json_encode($wiberank) ?>;
  const date = <?php echo json_encode($date) ?>;
  const symbol = <?php echo json_encode($stockticker) ?>;

  const rank1 = <?php echo json_encode($wiberank1) ?>;
  const date1 = <?php echo json_encode($date1) ?>;
  const symbol1 = <?php echo json_encode($stockticker1) ?>;

   const newrank = <?php echo json_encode($newwiberank) ?>;
  const ndate = <?php echo json_encode($newdate) ?>;

  var name = '<?php echo"$name" ?>';
  var name1 = '<?php echo"$name1" ?>';
  var newname = '<?php echo"$cname" ?>';

  console.log("Date1 :"+date);
  console.log("Date2 :"+date1);
  console.log("Date3 :"+ndate);

  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

  var newdate = new Array();
  
  for(var i=0;i<ndate.length;i++){
    
    var timeArray = ndate[i];
    var tarr = timeArray.split(/[-]+/);
    var mm = months[tarr[1]-1];
    const index = tarr.indexOf(tarr[1]);
    tarr[index]= mm;
    var ele = tarr.join('-');
    newdate.push(ele);
  } 
  const data ={
    labels: newdate,
      datasets: [{
        label: name,
        data: rank,
        borderWidth: 1,
      },
      {
        label: name1,
        data: rank1,
        borderWidth: 1
      }
    ]
  };


  //config block
  const config = {
    type: 'line',
    data,
    options: {
      tension:0.5,
      scales: {
        y: {reverse: true,
          grid: { color: '#808080',
                  beginAtZero: true,
                }
        },
      }
    }
  };

  //Render Block
  var chart1 = document.getElementById('myChart1');
    const myChart = new Chart(
    chart1,config
  );

    
    myChart.data.datasets.push({
      label: newname,
      data: newrank,
      borderWidth:1,
      backgroundColor: '#00ff00'
    });
    myChart.update();

}

  

</script>
</html>
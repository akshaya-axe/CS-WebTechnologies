<!doctype html>
<html>
<head>
<meta charset="UTF-8">

<title>Stock Search-hw6</title>
<style>
#search{
	width: 400px;
	margin: 0 auto;
	border: 1px solid #d4d4d4;
	padding: 1px;
	line-height: 25pt;
	background-color:#F3F3F3;
	font-family:serif;
	}
hr {
	background-color:#d3d3d3; 
	height:1px; 
	margin-top:0px;
	border:none;
	}
th{
	background-color:#f3f3f3;
	font-family:Arial;
	color:#343434;
	border:1px solid #d4d4d4;
	font-size:16px;
	}
td,tr{
	border:1px solid #d4d4d4;
}	
#list{
	font-family:Arial;
	font-size:14px;
	margin: 0 auto;
	padding:0 px;
	background-color:#fafafa;
	margin-top: 8px;
	text-align: left;
	border: 1px solid #d4d4d4;
	width:50%;
	}
#list2
{
	
	font-family:Arial;
	font-size:14px;
	margin: 0 auto;
	padding:1px;
	background-color:#fafafa;
	margin-top: 8px;
	border: 1px solid #d4d4d4;
	width:40%;
	
}
a.hovr:hover{color:red;	}
#norec {
	width: 600px;
	height:30px;
	margin: 0 auto;
	border: 1px solid #d4d4d4;
	background-color:#fafafa;
	}
p{
	margin-top:5px;
	text-align:center;
	}
		
#link {
	text-align: center;
	margin-bottom:10px;
	padding:3px;			
	}
	
.green{	width:100%;height:auto;}	
.red{width:100%;height:auto;}
	
</style>
</head>
<body>
<?php 
$name = "";	$cName = "";	$cSymbol = "";$cExchange = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$name = $_POST['name'];
				}
?>
<div id="search">
<i style="font-size:30px;font-weight:bold;"><center>Stock Search</i></center><hr>		
<form id="myForm" name="myForm" method="POST" action="Ahw6kshaya.php">
 Company Name or Symbol: <input type="text" name="name" autofocus required pattern="[a-zA-Z0-9]+[a-zA-Z0-9 ]+" x-moz-errormessage="Please enter name or symbol" value="<?php echo (isset($_POST['name']))? htmlentities ($_POST['name']) : ((isset($_GET["symbol"]))? $_GET["sname"]:"");  ?>"><br/>

<input type="submit" id="submitbutton" style="margin-left:185px;background-color:white;border-radius:4px;border-style: ridge;" value="Search"></input>

<input type="button" style="background-color:white;border-radius:4px;border-style: ridge;" onClick="rem(); clearForm();" value="Clear"></input>
<div id="link">
<center><a href='http://www.markit.com/product/markit-on-demand'>Powered by Markit on Demand</a></center>
</div>
</form>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
$url = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/xml?input=";
$name = urlencode($name);
$url = $url . $name;
				
$xml = simplexml_load_file($url);
			
				
if ($xml -> LookupResult -> count() > 0)
{
global $cName, $cSymbol, $cExchange;
echo "<table id=\"list\" rules=\"all\">";
echo "<tr>";
echo "<th>Name</th>";
echo "<th>Symbol</th>";
echo "<th>Exchange</th>";
echo "<th>Details</th>";
echo "</tr>";
						
foreach ($xml -> LookupResult as $result) {
$cName = $result -> Name;
echo "<tr><td>" . $cName . "</td>";
$cSymbol = $result -> Symbol;
echo "<td>" . $cSymbol . "</td>";
$cExchange = $result -> Exchange;
echo "<td>" . $cExchange . "</td>";

echo "<td><a class=\"hovr\" href=\"?symbol=".$cSymbol."&sname=".$_POST['name']."\" >More Info</a></td></tr>";}
}
else 
{
echo "<br><div id=\"norec\">";
echo "<p align=\"center\">No Records has been found</p>";
echo "</div>";
}
}
?>
<?php
if (isset($_GET['symbol']))
{
	
$innerurl="http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=" . $_GET['symbol'];
//global $r=$_GET['symbol'];
$json = file_get_contents($innerurl);
$data = json_decode($json);
date_default_timezone_set('US/Eastern');
$redimg="<img style=\"width:10px; vertical-align:middle;\" src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png\"></img>";
$greenimg="<img style=\" width:10px;vertical-align:middle;\" src=\"http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png\"></img>";
if ($data->{"Status"}=="SUCCESS"){
echo "<table id=\"list2\" align=\"center\" rules=\"all\">";
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Name</b></td><td style=\"text-align:center;\">". $data->{"Name"} . "</td></tr>";
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Symbol</b></td><td style=\"text-align:center;\">".$data->{"Symbol"}."</td></tr>";
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Last Price</b></td><td style=\"text-align:center;\">".$data->{"LastPrice"}."</td></tr>";
$a=round($data->{"Change"},2);
if ($a==0)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change</b></td><td style=\"text-align:center;\">".$a."</td></tr>";
else 
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change</b></td><td style=\"text-align:center;\">".$a.($a>0? $greenimg: $redimg)."</td></tr>";	
$b=round($data->{"ChangePercent"},2);
if ($b==0)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change Percent</b></td><td style=\"text-align:center;\">".$b."%"."</td></tr>";
else
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change Percent</b></td><td style=\"text-align:center;\">".$b."%".($b>0? $greenimg: $redimg)."</td></tr>";
$c=$data->{"Timestamp"};
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Timestamp</b></td><td style=\"text-align:center;\">".date('Y-m-d h:i A',strtotime($c))." EST</td></tr>";
$d=$data->{"MarketCap"};
if ($d<=10000000 && $d>0)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Market Cap</b></td><td style=\"text-align:center;\">".round(($d/1000000),2)." M</td></tr>"; 
elseif ($d>10000000)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Market Cap</b></td><td style=\"text-align:center;\">".round(($d/1000000000),2)." B</td></tr>";
else
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Market Cap</b></td><td style=\"text-align:center;\">".round(($d/1000000000),2)." </td></tr>";	
$f =  number_format($data->{"Volume"}, 0, '.', ',');
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Volume</b></td><td style=\"text-align:center;\">".$f."</td></tr>";
$g=($data->{"LastPrice"})-($data->{"ChangeYTD"});
$h=round($g,2);
if ($h==0)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change YTD</b></td><td style=\"text-align:center;\">".$h."</td></tr>";	
else if ($h<0)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change YTD</b></td><td style=\"text-align:center;\">(".$h.")".($h>0? $greenimg: $redimg)."</td></tr>";
else 
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change YTD</b></td><td style=\"text-align:center;\">".$h.($h>0? $greenimg: $redimg)."</td></tr>";	
$i=round($data->{"ChangePercentYTD"},2);
if ($i==0)
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change Percent YTD</b></td><td style=\"text-align:center;\">".$i."%"."</td></tr>";
else
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Change Percent YTD</b></td><td style=\"text-align:center;\">".$i."%".($i>0? $greenimg: $redimg)."</td></tr>";
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>High</b></td><td style=\"text-align:center;\">".$data->{"High"}."</td></tr>";
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Low</b></td><td style=\"text-align:center;\">".$data->{"Low"}."</td></tr>";
echo "<tr><td style=\"background-color:#f3f3f3;\"><b>Open</b></td><td style=\"text-align:center;\">".$data->{"Open"}."</td></tr>";
echo "</table>";
}
else 
{
echo "<br><div id=\"norec\">";
echo "<p align='center'>No Records has been found</p>";
echo "</div>";
}
}
?>

	
<script>
function clearForm() {
var a = document.getElementById("list");
var b = document.getElementById("norec");
var c = document.getElementById("list2");
a.parentNode.removeChild(a);
b.parentNode.removeChild(b);
c.parentNode.removeChild(c);
}
function rem() {
window.location.href = "hw6.php";
}
			
</script>
<noscript>
</body>
</html>

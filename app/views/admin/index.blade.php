@extends('layouts/admintemplate')

@section('body')

<?
// user stats
$totalUsers = User::getTotalUsers();
$adminUsers = User::filterUsers('admin');
$normalUsers = $totalUsers - $adminUsers;
// build stats
$totalBuilds = Blog::getTotalBuilds();
$unpubBuilds = Blog::filterBuilds('unpub');
$liveBuilds = Blog::filterBuilds('live');
$hiddenBuilds = Blog::filterBuilds('hidden');
$emptyBuilds = Blog::filterBuilds('empty');
?>

<h1>Dashboard</h1>

<div class="line-divider"></div>

<div class="col-sm-3">
  <div class="totalAdminCount displaynone"><?= $adminUsers; ?></div>
  <div class="totalNormalCount displaynone"><?= $normalUsers; ?></div>

  <div class="panel panel-info">
  	<div class="panel-heading">Total Users: <?= $totalUsers ?></div>
	  <div class="panel-body">
	    <canvas id='userStats' width='200px' height='200px'></canvas>
	  </div>
  </div>

</div>

<div class="col-sm-3">
  <div class="totalBuildCount displaynone"><?= $totalBuilds; ?></div>
  <div class="unpubBuildCount displaynone"><?= $unpubBuilds; ?></div>
  <div class="liveBuildCount displaynone"><?= $liveBuilds; ?></div>
  <div class="hiddenBuildCount displaynone"><?= $hiddenBuilds; ?></div>
  <div class="emptyBuildCount displaynone"><?= $emptyBuilds; ?></div>
  
   <div class="panel panel-info">
  	<div class="panel-heading">Total Builds: <?= $totalBuilds ?></div>
	  <div class="panel-body">
	    <canvas id='buildStats' width='200px' height='200px'></canvas>
	  </div>
  </div>

</div>

<div class="col-sm-3">
  
  <div class="panel panel-info">
  	<div class="panel-heading">Total Posts:</div>
	  <div class="panel-body">
	   
	  </div>
  </div>

</div>

<div class="col-sm-3">

  <div class="panel panel-info">
  	<div class="panel-heading">Total Followings:</div>
	  <div class="panel-body">
	   
	  </div>
  </div>

</div>

@stop

@section('scripts')

<script>
$(function(){
// users
var ctx = $("#userStats")[0].getContext("2d");
var myNewChart = new Chart(ctx);
var data1 = parseInt($(".totalNormalCount").text(), 10);
var data2 = parseInt($(".totalAdminCount").text(), 10);
var data = [
    {
        value: data2,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "Admin"
    },
    {
        value: data1,
        color:"#333",
        highlight: "#FF5A5E",
        label: "Normal"
    }
]
myNewChart.Doughnut(data);
// builds
var ctxBuild = $("#buildStats")[0].getContext("2d");
var buildChart = new Chart(ctxBuild);
var data3 = parseInt($(".unpubBuildCount").text(), 10);
var data4 = parseInt($(".liveBuildCount").text(), 10);
var data5 = parseInt($(".hiddenBuildCount").text(), 10);
var data6 = parseInt($(".emptyBuildCount").text(), 10);
var dataBuild = [
    {
        value: data3,
        color:"#2980b9",
        highlight: "#FF5A5E",
        label: "Unpublished"
    },
    {
        value: data4,
        color:"#27ae60",
        highlight: "#FF5A5E",
        label: "Live"
    },
    {
        value: data5,
        color:"#d35400",
        highlight: "#FF5A5E",
        label: "Live"
    },
    {
        value: data6,
        color:"#8e44ad",
        highlight: "#FF5A5E",
        label: "Live"
    }
]
buildChart.Doughnut(dataBuild);
});
</script>

@stop
@extends('header')
@section('content')

<div class="rows">
    <div class="status-bar">
        <ul>
            <li class="progres">
                <div class="icon">
                    <img src="{{asset('vendor/images/icon/progress.png')}}" alt="">
                </div>
                <div class="content">
                    <div class="numb" data-from="0" data-to="38" data-speed="1000" data-waypoint-active="yes">{{$counts->prdc}}/{{$counts->user}}</div>
                    <div class="text">
                        메이커 / 회원
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="icon">
                    <img src="{{asset('vendor/images/icon/task.png')}}" alt="">
                </div>
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">{{$counts->beat}}</div>
                    <div class="text">
                        등록된 비트
                    </div>
                </div>
            </li>
            <li class="progres" onclick="window.location='/bbs'" style="cursor: pointer">
                <div class="icon">
                    <img src="{{asset('vendor/images/icon/tick.png')}}" alt="">
                </div>
                <div class="content">
                    <div class="numb" data-from="0" data-to="87" data-speed="1000" data-waypoint-active="yes">{{$counts->qna}}</div>
                    <div class="text">
                        답변필요 문의사항
                    </div>
                </div>
            </li>
            <li class="progres" onclick="window.location='/maker'" style="cursor: pointer">
                <div class="icon">
                    <img src="{{asset('vendor/images/icon/chart.png')}}" alt="">
                </div>
                <div class="content">
                    <div class="numb" data-from="0" data-to="43" data-speed="1000" data-waypoint-active="yes">{{$counts->wait}}</div>
                    <div class="text">
                        메이커 승인요청
                    </div>
                </div>
            </li>
        </ul>
</div>
</div>
<!--
<div class="rows">
    <div class="box box-danger left">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>ANALYSIS</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content chart-doughnut">
            <div class="divider50"></div>
            <div class="chart" id="sales-chart" style="height: 250px; width: 280px; position: relative;"></div>
            <div class="legend style1">
                <ul class="legend-list">
                    <li class="ux">
                        <span class="text">UX DESIGN</span>
                        <p>45 %</p>
                    </li>
                    <li class="ui">
                        <span class="text">UI DESIGN</span>
                        <p>35 %</p>
                    </li>
                    <li class="code">
                        <span class="text">CODE</span>
                        <p>20 %</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box box-statistics right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>STATISTICS</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style1">
            <ul class="chart-tab">
                <li class="waves-effect waves-teal">WEEK</li>
                <li class="waves-effect waves-teal">MONTH</li>
                <li class="active waves-effect waves-teal">YEAR</li>
            </ul>
            <div id="chartStatistics">
        </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="rows">
    <div class="box box-stackedColumn left">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>UX & UI ANALYSIS</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style2">
            <div class="divider35"></div>
            <div id="chart-stackedColumn"></div>
        </div>
    </div>
    <div class="box box-spline right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>POLAR CHART</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content">
            <div class="divider35"></div>
            <div id="chart-spline"></div>
            <div class="legend">
                <ul class="legend-list">
                    <li class="ux">
                        <span class="dot"></span>
                        <span class="text">UX Design</span>
                    </li>
                    <li class="ui">
                        <span class="dot"></span>
                        <span class="text">UI Design</span>
                    </li>
                    <li class="code">
                        <span class="dot"></span>
                        <span class="text">Code</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="rows">
    
    <div class="box box-bubble right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>LINE SCATTER DIAGRAM</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style2">
            <div class="divider22"></div>
            <div id="bubble-chart"></div>
            <div class="legend">
                <ul class="legend-list">
                    <li class="ux">
                        <span class="dot"></span>
                        <span class="text">UX Design</span>
                    </li>
                    <li class="ui">
                        <span class="dot"></span>
                        <span class="text">UI Design</span>
                    </li>
                    <li class="code">
                        <span class="dot"></span>
                        <span class="text">Code</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="rows">
    <div class="box box-line left">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>LINE CHART</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style2">
            <div class="divider50"></div>
            <div id="lineChart"></div>
        </div>
    </div>
    <div class="box box-radar right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>RADAR VALUE</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="waves-effect" title="">Action</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Support</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect" title="">Message</a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content">
            <div class="divider22"></div>
            <canvas id="radarChart" height="150"></canvas>
            <div class="legend style2">
                <ul class="legend-list">
                    <li class="ux">
                        <span class="dot"></span>
                        <span class="text">Last Month</span>
                    </li>
                    <li class="code">
                        <span class="dot"></span>
                        <span class="text">This Month</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>-->

    
</div><!-- /.rows -->
</section><!-- /#dashboard -->

	<!-- Map chart  -->
	<script src="{{ asset('vendor/js/ammap.js')}}"></script>
	<script src="{{ asset('vendor/js/worldLow.js')}}"></script>

	<!-- Morris.js charts -->
	<script src="{{ asset('vendor/js/raphael.min.js')}}"></script>
	<script src="{{ asset('vendor/js/morris.min.js')}}"></script>

	<!-- Chart -->
	<script src="{{ asset('vendor/js/Chart.min.js')}}"></script>

	<script type="text/javascript" src="{{ asset('vendor/js/jquery.mCustomScrollbar.js')}}"></script>
	<script src="{{ asset('vendor/js/smoothscroll.js')}}"></script>
	<script src="{{ asset('vendor/js/waypoints.min.js')}}"></script>
	<script src="{{ asset('vendor/js/jquery-countTo.js')}}"></script>
	<script src="{{ asset('vendor/js/waves.min.js')}}"></script>
	<script src="{{ asset('vendor/js/canvasjs.min.js')}}"></script>


<script style="text/javascript">
        (function($) {

'use strict'

     var isMobile = {
         Android: function() {
             return navigator.userAgent.match(/Android/i);
         },
         BlackBerry: function() {
             return navigator.userAgent.match(/BlackBerry/i);
         },
         iOS: function() {
             return navigator.userAgent.match(/iPhone|iPad|iPod/i);
         },
         Opera: function() {
             return navigator.userAgent.match(/Opera Mini/i);
         },
         Windows: function() {
             return navigator.userAgent.match(/IEMobile/i);
         },
         any: function() {
             return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
         }
     };

     var donutChart = function() {
         //DONUT CHART
         var donut = new Morris.Donut({
             element: 'sales-chart',
             resize: true,
             colors: ["#ff2d78", "#04aec6", "#4c418b"],
             data: [
                 {label: "CODE", value: 20},
                 {label: "UI DESIGN", value: 35},
                 {label: "UX DESIGN", value: 45}
             ],
             options: {
                 segmentShowStroke : true,
                 StrokeColor : "#c5c5c5",
                 fill: "#c5c5c5",
                 percentageInnerCutout : 50,
                 animationSteps : 100,
                 animationEasing : "easeOutBounce",
                 animateRotate : true,
                 responsive: true,
                 maintainAspectRatio: true,
                 showScale: true,
                 animateScale: true,
                 responsive: true,
                 resize: true,
                 segmentShowStroke : true,
                 segmentStrokeColor : "#fff",
                 segmentStrokeWidth : 0,
                 tooltipCornerRadius: 2,
             },
             hideHover: 'auto',
         });
     }; // Donut Chart

     var SplineArea = function () {
         CanvasJS.addColorSet("greenShades",
                     [//colorSet Array
                     "#6256a9",
                     "#00c7e3",
                     "#7065b6",
                     "#e02c73",            
                     ]);
           
         var chart = new CanvasJS.Chart("chartStatistics",
             {
                 backgroundColor: "transparent",
                 colorSet:  "greenShades",
                 responsive: false,
                 animationEnabled: true,
                 axisX:{
                     gridColor: "#24262a",
                     gridThickness: 1 ,
                     tickColor: "transparent",
                     LabelFontColor: "#898989",
                     labelFontSize: "12",
                     labelFontFamily: "Montserrat",
                     maximum: 75,
                     minimum: 5,
                     lineThickness: 1,
                     lineColor: "#24262a",
                     tickLength: 15,
                     tickColor: "transparent",
                 },
                 axisY:{
                     gridColor: "#24262a" ,
                     gridThickness: 1,
                     tickColor: "transparent",
                     labelFontFamily: "Montserrat",
                     LabelFontColor: "#898989",
                     labelFontSize: "12",
                     maximum: 2000,
                     minimum:0,
                     interval: 500,
                     valueFormatString: "####",
                     lineThickness: 1,
                     lineColor: "#24262a",
                     tickLength: 15,
                     tickColor: "transparent",
                 },
                 data: [{        
                     type: "column",
                     dataPoints: [
                     {label: "JAN", x: 10, y: 0},
                     {label: "FEB", x: 20, y: 0},
                     {label: "MAR", x: 30, y: 2000},
                     {label: "APR", x: 40, y: 0},
                     {label: "MAY", x: 50, y: 0},
                     {label: "JUN", x: 60, y: 0},
                     {label: "JUL", x: 70, y: 0},
                 ]},
                 {        
                     type: "splineArea",
                     markerColor: "transparent",
                     fillOpacity: 0.9,
                     bevelEnabled: true,
                     lineColor:"#04aec6",
                     dataPoints: [
                         {label: "",x: 0, y: 750},
                         {label: "JAN",x: 10, y: 700},
                         {label: "FEB",x: 20, y: 1400},
                         {label: "MAR",x: 30, y: 700},
                         {label: "APR",x: 40, y: 850},
                         {label: "MAY",x: 50, y: 1350},
                         {label: "JUN",x: 60, y: 1000},
                         {label: "JUL",x: 70, y: 900},
                         {label: "JUL",x: 80, y: 1100},
                 ]},
                 {        
                     type: "splineArea",
                     markerColor: "transparent",
                     fillOpacity: 0.9,
                     lineColor:"#6c61b1",
                     bevelEnabled: true,
                     dataPoints: [
                         {label: "",x: 0, y: 1000},
                         {label: "JAN",x: 10, y: 1122},
                         {label: "FEB",x: 20, y: 800},
                         {label: "MAR",x: 30, y: 1397},
                         {label: "APR",x: 40, y: 600},
                         {label: "MAY",x: 50, y: 700},
                         {label: "JUN",x: 60, y: 600},
                         {label: "JUL",x: 70, y: 1010},
                         {label: "JUL",x: 80, y: 1000},
                 ]},
                 {        
                     type: "splineArea",
                     markerColor: "transparent",
                     fillOpacity: 0.9,
                     bevelEnabled: true,
                     lineColor:"#f72771",
                     dataPoints: [
                         {label: "",x: 0, y: 400},
                         {label: "JAN",x: 10, y: 550},
                         {label: "FEB",x: 20, y: 340},
                         {label: "MAR",x: 30, y: 450},
                         {label: "APR",x: 40, y: 250},
                         {label: "MAY",x: 50, y: 900},
                         {label: "JUN",x: 60, y: 550},
                         {label: "JUL",x: 70, y: 700},
                         {label: "",x: 80, y: 600}, 
                 ]}
                 ]
             });
         chart.render();
     }; // Spline Area

     var StackedColumn = function () {
         CanvasJS.addColorSet("greenShades",
                     [//colorSet Array
                     "#4c418b",
                     "#00bcd5"           
                     ]);
         var chart = new CanvasJS.Chart("chart-stackedColumn",
             {
                 backgroundColor: "transparent",
                 colorSet:  "greenShades",
                 animationEnabled: true,
                 dataPointMaxWidth: 55,
                 resize: true,
                 toolTip:{
                     shared: true,
                 },
                 axisX:{
                     gridThickness: 0 ,
                     tickColor: "transparent",
                     LabelFontColor: "#898989",
                     labelFontSize: "12",
                     labelFontFamily: "Montserrat",
                     lineThickness: 0
                 },
                 axisY:{
                     gridThickness: 0,
                     tickColor: "transparent",
                     LabelFontColor: "#898989",
                     labelFontFamily: "Montserrat",
                     labelFontSize: "12",
                     maximum: 2000,
                     interval: 500,
                     valueFormatString: "####",
                     lineThickness: 0
                 },
                 data: [{
                     type: "stackedColumn",
                     name: "UI",
                     dataPoints: [
                         {  y: 800 , label: "Sun"},
                         {  y: 1250, label: "Mon" },
                         {  y: 600, label: "Tue" },
                         {  y: 800, label: "Wed" },
                         {  y: 200, label: "Thu"},
                         {  y: 1150, label: "Fri"},
                         {  y: 650, label: "Sat"}
                 ]}, 
                     {
                     type: "stackedColumn",
                     name: "UX",
                     dataPoints: [
                     {  y: 100 , label: "Sun"},
                     {  y: 400, label: "Mon" },
                     {  y: 1300, label: "Tue" },
                     {  y: 450, label: "Wed" },
                     {  y: 250, label: "Thu"},
                     {  y: 300, label: "Fri"},
                     {  y: 300, label: "Sat"}
                     ]}
                 ]
             });
         chart.render();
     }; // Stacked Column

     var Spline = function () {
         CanvasJS.addColorSet("greenShades",
                     [//colorSet Array
                     "#ff2d78",
                     "#00c7e3",
                     "#7065b6",           
                     ]);
         var chart = new CanvasJS.Chart("chart-spline",
             {
                 backgroundColor: "transparent",
                 colorSet:  "greenShades",
                 animationEnabled: true,
                 toolTip:{
                     shared: true,
                 },
                     axisX:{
                         gridColor: "#24262a",
                         gridThickness: 1 ,
                         tickColor: "transparent",
                         LabelFontColor: "#898989",
                         labelFontSize: "12",
                         labelFontFamily: "Montserrat",
                         maximum: 61,
                         minimum:0,
                         lineThickness: 1,
                         lineColor: "#24262a",
                     },
                     axisY:{
                         gridColor: "#24262a" ,
                         gridThickness: 1,
                         tickColor: "transparent",
                         LabelFontColor: "#898989",
                         labelFontFamily: "Montserrat",
                         labelFontSize: "12",
                         maximum: 50,
                         interval: 10,
                         minimum:0,
                         valueFormatString: "####",
                         lineThickness: 1,
                         lineColor: "#24262a",
                     },
                 data: [{        
                     type: "spline",
                     markerColor: "transparent",
                     fillOpacity: 0.9,
                     bevelEnabled: true,
                     lineColor:"#eee",
                     name: "CODE",
                     dataPoints: [
                         {label: "Sun",x: 0, y: 21},
                         {label: "Mon",x: 10, y: 17},
                         {label: "Tue",x: 20, y: 31},
                         {label: "Wed",x: 30, y: 10},
                         {label: "Thu",x: 40, y: 18},
                         {label: "Fri",x: 50, y: 12},
                         {label: "Sat",x: 60, y: 22},
                     ]
                 },
                 {        
                     type: "spline",
                     markerColor: "transparent",
                     fillOpacity: 0.9,
                     lineColor:"#6c61b1",
                     bevelEnabled: true,
                      name: "UI Design",
                     dataPoints: [
                         {label: "Sun",x: 0, y: 22},
                         {label: "Mon",x: 10, y: 13},
                         {label: "Tue",x: 20, y: 39},
                         {label: "Wed",x: 30, y: 18},
                         {label: "Thu",x: 40, y: 23},
                         {label: "Fri",x: 50, y: 39},
                         {label: "Sat",x: 60, y: 18},
                     ]
                 },
                 {        
                     type: "spline",
                     markerColor: "transparent",
                     fillOpacity: 0.9,
                     bevelEnabled: true,
                     lineColor:"#f72771",
                      name: "UX Design",
                     dataPoints: [
                         {label: "Sun",x: 0, y: 33},
                         {label: "Mon",x: 10, y: 21},
                         {label: "Tue",x: 20, y: 27},
                         {label: "Wed",x: 30, y: 18},
                         {label: "Thu",x: 40, y: 42},
                         {label: "Fri",x: 50, y: 28},
                         {label: "Sat",x: 60, y: 36},
                     ]
                 }]
             });
         chart.render();
     }; // Spline

     var bubbleChart = function () {
         CanvasJS.addColorSet("greenShades",
                     [//colorSet Array
                     "#ff2d78",
                     "#00c7e3",
                     "#7065b6",           
                     ]);
         var chart = new CanvasJS.Chart("bubble-chart",
             {
               zoomEnabled: true,
               colorSet:  "greenShades",
               backgroundColor: "transparent",
               animationEnabled: true,
               axisX: {
                 maximum: 204,
                 minimum: -200,
                 gridThickness: 1,
                 tickThickness: 1,
                 gridColor: "#24262a",
                 gridThickness: 1 ,
                 tickColor: "transparent",
                 LabelFontColor: "#898989",
                 labelFontSize: "12",
                 labelFontFamily: "Montserrat",
                 lineThickness: 1,
                 lineColor: "#24262a",
               },
               axisY:{              
                 gridColor: "#24262a" ,
                 gridThickness: 1,
                 tickColor: "transparent",
                 LabelFontColor: "#898989",
                 labelFontFamily: "Montserrat",
                 labelFontSize: "12",
                 maximum: 150,
                 interval: 50,
                 minimum: -100,
                 lineThickness: 1,
                 lineColor: "#24262a",
               },

               data: [
               {        
                 type: "bubble",     
                 toolTipContent: "<span style='\"'color: #ff2d78;'\"'><strong>Code</strong></span>",
                 dataPoints: [
                 { x: -145, y: -65, z:7},
                 { x: -142, y: 95, z:2},
                 { x: -127, y: 5, z:4},
                 { x: -70, y: -27, z:3},
                 { x: -78, y: -53, z:7},
                 { x: -60, y: -70, z:1},
                 { x: -60, y: 20, z:4},
                 { x: -70, y: 95, z:1},
                 { x: -60, y: 110, z:7},
                 { x: -35, y: 20, z:12},
                 { x: -20, y: -20, z:3},
                 { x: -8, y: -28, z:1},
                 { x: 16, y: 43, z:4},
                 { x: 24, y: -8, z:6},
                 { x: 46, y: -92, z:1},
                 { x: 68, y: -77, z:5},
                 { x: 115, y: 25, z:3},
                 { x: 120, y: 95, z:8},
                 { x: 126, y: 47, z:2},
                 { x: 130, y: 76, z:1},
                 { x: 130, y: -90, z:4},
                 ]
                 },
                
                {
                 type: "bubble",     
                 toolTipContent: "<span style='\"'color: #00c7e3;'\"'><strong>UI Design</strong></span>",
                 dataPoints: [
                 { x: -175, y: 1000, z:100},
                 { x: -175, y: 10, z:4},
                 { x: -148, y: 95, z:2},
                 { x: -130, y: 45, z:8},
                 { x: -140, y: -40, z:12},
                 { x: -85, y: 80, z:8},
                 { x: -75, y: 53, z:4},
                 { x: -52, y: 54, z:12},
                 { x: -52, y: -52, z:10},
                 { x: -30, y: 5, z:12},
                 { x: -32, y: 70, z:4},
                 { x: -25, y: 84, z:4},
                 { x: 2, y: -15, z:8},
                 { x: 2, y: 65, z:8},
                 { x: 16, y: 26, z:12},
                 { x: 20, y: 50, z:4},
                 { x: 18, y: 78, z:4},
                 { x: 40, y: 42, z:8},
                 { x: 40, y: -75, z:8},
                 { x: 54, y: 46, z:8},
                 { x: 70, y: 68, z:3},
                 { x: 126, y: -20, z:10},
                 { x: 138, y: 10, z:8},
                 { x: 160, y: 30, z:4},
                 { x: 162, y: 49, z:2},
                 ]
                 },
                                  {
                 type: "bubble",     
                 toolTipContent: "<span style='\"'color: #7065b6;'\"'><strong>UX Design</strong></span>",
                 dataPoints: [
                 { x: -155, y: 20, z:4},
                 { x: -130, y: 110, z:8},
                 { x: -105, y: 15, z:5},
                 { x: -105, y: 82, z:2},
                 { x: -96, y: -55, z:5},
                 { x: -90, y: -15, z:8},
                 { x: -73, y: -22, z:4},
                 { x: -82, y: 35, z:3},
                 { x: -75, y: 95, z:1},
                 { x: -35, y: -22, z:3},
                 { x: -40, y: 88, z:1},
                 { x: -12, y: 8, z:3},
                 { x: -5, y: 47, z:4},
                 { x: 47, y: 0, z:6},
                 { x: 57, y: -47, z:4},
                 { x: 78, y: -19, z:8},
                 { x: 86, y: -77, z:6},
                 { x: 100, y: 7, z:6},
                 { x: 90, y: 67, z:3},
                 { x: 137, y: 76, z:1},
                 { x: 140, y: 38, z:3},
                 { x: 147, y: 52, z:2},
                 ]
                   },
               ]
             });
         chart.render();
     }; // Bubble Chart


     var lineChart = function() {
         CanvasJS.addColorSet("greenShades",
                     [//colorSet Array
                     "#04aec6",
                     "#ff2d78",
                     "#00c7e3",
                     "#7065b6",           
                     ]);
         var chart = new CanvasJS.Chart("lineChart",
         {
           zoomEnabled: true,
           colorSet:  "greenShades",
           backgroundColor: "transparent",
           animationEnabled: true,
           dataPointMaxWidth: 3,
           axisX: {
             maximum: 111,
             minimum: 0,
             
             gridThickness: 1,
             tickThickness: 1,
             gridColor: "#252628",
             gridThickness: 1 ,
             tickColor: "transparent",
             LabelFontColor: "#898989",
             labelFontSize: "12",
             labelFontFamily: "Montserrat",
             lineThickness: 1,
             lineColor: "#252628",
           },
           axisY:{              
             gridColor: "#252628" ,
             gridThickness: 1,
             tickColor: "transparent",
             LabelFontColor: "#898989",
             labelFontFamily: "Montserrat",
             labelFontSize: "12",
             maximum: 20000,
             interval: 5000,
             valueFormatString: "#,##0,.",
             suffix:"K",
             lineThickness: 1,
             lineColor: "#252628",
           },

            data: [
            {
             type: "column",
             dataPoints: [
             {label: "Jun",x: 0, y: 0},
             {label: "Jul",x: 10, y: 0},
             {label: "Aug",x: 20, y: 0},
             {label: "Sep",x: 30, y: 0},
             {label: "Oct",x: 40, y: 0},
             {label: "Nov",x: 50, y: 0},
             {label: "Dec",x: 60, y: 0},
             {label: "Jan",x: 70, y: 0},
             {label: "Feb",x: 80, y: 0},
             {label: "Mar",x: 90, y: 12550},
             {label: "Apr",x: 100, y: 0},
             {label: "May",x: 110, y: 0},
             ]
           },
           {
             type: "line",

             dataPoints: [
             {label: "Jun",x: 0, y: 1600},
             {label: "Jul",x: 10, y: 2300},
             {label: "Aug",x: 20, y: 1700},
             {label: "Sep",x: 30, y: 3150},
             {label: "Oct",x: 40, y: 2000},
             {label: "Nov",x: 50, y: 2100},
             {label: "Dec",x: 60, y: 1750},
             {label: "Jan",x: 70, y: 2700},
             {label: "Feb",x: 80, y: 4000},
             {label: "Mar",x: 90, y: 4000},
             {label: "Apr",x: 100, y: 5000},
             {label: "May",x: 110, y: 8200},
             ]
           },
           {
             type: "line",

             dataPoints: [
             {label: "Jun",x: 0, y: 5050},
             {label: "Jul",x: 10, y: 4180},
             {label: "Aug",x: 20, y: 5100},
             {label: "Sep",x: 30, y: 6500},
             {label: "Oct",x: 40, y: 5500},
             {label: "Nov",x: 50, y: 7050},
             {label: "Dec",x: 60, y: 6000},
             {label: "Jan",x: 70, y: 6800},
             {label: "Feb",x: 80, y: 7450},
             {label: "Mar",x: 90, y: 12900},
             {label: "Apr",x: 100, y: 13300},
             {label: "May",x: 110, y: 16800},
             ]
           }, {
             type: "line",

             dataPoints: [
             {label: "Jun",x: 0, y: 2800},
             {label: "Jul",x: 10, y: 2700},
             {label: "Aug",x: 20, y: 4200},
             {label: "Sep",x: 30, y: 4250},
             {label: "Oct",x: 40, y: 2650},
             {label: "Nov",x: 50, y: 4000},
             {label: "Dec",x: 60, y: 5300},
             {label: "Jan",x: 70, y: 4100},
             {label: "Feb",x: 80, y: 5000},
             {label: "Mar",x: 90, y: 6200},
             {label: "Apr",x: 100, y: 9100},
             {label: "May",x: 110, y: 12500},
             ]
           }
           ]
         });

         chart.render();
     }; // Line Chart

     var radarChart = function() {
         var ctx6 = document.getElementById("radarChart").getContext("2d");
         var data6 = {
             labels: ["UI", "Psd", "Css", "Designing", "UX", "Java", "Html"],
             datasets: [
                 
                 {
                     fillColor: "rgba(97,100,193,0.8)",
                     strokeColor: "rgba(97,100,193,1)",
                     pointColor: "rgba(97,100,193,1)",
                     pointStrokeColor: "rgba(97,100,193,0)",
                     pointHighlightFill: "rgba(97,100,193,1)",
                     pointHighlightStroke: "rgba(97,100,193,1)",
                     data: [28, 78, 75, 19, 96, 27, 100]
                 },
                 {
                     backgroundColor:"rgba(147, 49, 115, 0.8)",
                     fillColor: "rgba(147, 49, 115, 0.8)",
                     strokeColor: "rgba(147, 49, 115, 0.8)",
                     pointColor: "rgba(147, 49, 115, 0.8)",
                     pointStrokeColor: "#fff",
                     pointHighlightFill: "#fff",
                     pointHighlightStroke: "rgba(147, 49, 115, 0.8)",
                     data: [65, 59, 90, 81, 26, 55, 40]
                 }
             ]
         };
         
         var myRadarChart = new Chart(ctx6).Radar(data6, {
             scaleShowLine : true,
             angleShowLineOut : true,
             scaleShowLabels : false,
             scaleBeginAtZero : true,
             angleLineColor : "rgba(0,0,0,.1)",
             angleLineWidth : 1,
             pointLabelFontFamily : "'Oswald'",
             pointLabelFontStyle : "normal",
             pointLabelFontSize : 12,
             pointLabelFontWeight : 500,
             pointLabelFontColor : "#666",
             pointDot : false,
             pointDotRadius : 3,
             tooltipCornerRadius: 2,
             pointDotStrokeWidth : 1,
             pointHitDetectionRadius : 20,
             datasetStroke : true,
             datasetStrokeWidth : 2,
             datasetFill : true,
             legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
             responsive: true,
         });
     }; // Radar Chart


   
     var scrollbarTable = function() {
         $(".box-project .box-content").mCustomScrollbar({
             axis:"x",
             advanced:{autoExpandHorizontalScroll:true},
             scrollInertia:400,
         });
     }; // Scrollbar MessageBox

  
     var counter = function() {
         $('.status-bar').on('on-appear', function() {             
             $(this).find('.numb').each(function() { 
                 var to = parseInt( ($(this).attr('data-to')),10 ), speed = parseInt( ($(this).attr('data-speed')),10 );
                 if ( $().countTo ) {
                     $(this).countTo({
                         to: to,
                         speed: speed
                     });
                 }
             });
        });
     }; // Counter

     var progressBar = function(){
         $('td.bg').waypoint(function() {
             $('span').each( function() {
                 var percent = $(this).data('percent');
                  $(this).animate({
                     "width": percent + '%'
                 },1500); 
             });
         }, {offset: '100%'});
     };// Progress Bar

     var detectViewport = function() {
         $('[data-waypoint-active="yes"]').waypoint(function() {
             $(this).trigger('on-appear');
         }, { offset: '90%', triggerOnce: true });
          $(window).on('load', function() {
             setTimeout(function() {
                 $.waypoints('refresh');
             }, 100);
         });
     }; // Detect Viewport

     var setWidth = function() {
         $('.box.right').on('resize', function() {
             var w1 = $('.box.right').children('.box-content').width();
             $(this).find('canvas').css({
                 width: w1,
             });
         });
         $(window).on("resize", function () {
             // Set .right's width to the window width minus 480 pixels
             $("canvas").width( $(this).parent().width() );
         // Invoke the resize event immediately
         }).resize();
     }; // Set Width

     var waveButton = function () {
         Waves.attach('.button', ['waves-button', 'waves-float']);
         Waves.init();
     }; // Wave Button

     var retinaLogos = function() {
         var retina = window.devicePixelRatio > 1 ? true : false;
         if(retina) {
             $('.header .logo').find('img').attr( {src:'./images/logo@2x.png',width:'94',height:'47'} );   
         }
     }; // Retina Logos

     var removePreload = function() { 
         $(window).load(function() { 
             setTimeout(function() {
                 $('.loader').hide(); }, 300           
             ); 
         });
     }; //remove Preloader

 $(function() {
     /*
     donutChart();
     SplineArea();
     StackedColumn();
     Spline();
     bubbleChart();
     lineChart();
     radarChart();
     scrollbarTable();
     counter();
     progressBar();
     detectViewport();
     setWidth();
     waveButton();
     retinaLogos();*/
    removePreload();
 });

})(jQuery);
</script>

@endsection

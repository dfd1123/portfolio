@extends('admin.layouts.header') 
@section('content')

<div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">Dashboard2</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
                <div>
                    <button class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-9 col-xlg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap">
                                    <div>
                                        <h4 class="card-title">Analytics Overview</h4>
                                        <h6 class="card-subtitle">Overview of Monthly analytics</h6>
                                    </div>
                                    <div class="ml-auto align-self-center">
                                        <ul class="list-inline m-b-0">
                                            <li>
                                                <h6 class="text-muted text-success"><i class="fa fa-circle font-10 m-r-10 "></i>Site A</h6> </li>
                                            <li>
                                                <h6 class="text-muted text-info"><i class="fa fa-circle font-10 m-r-10"></i>Site B</h6> </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="campaign ct-charts" style="height:305px!important;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Total Visit</h4>
                                <div class="d-flex">
                                    <div class="align-self-center">
                                        <h4 class="font-medium m-b-0"><i class="ti-angle-up text-success"></i>  12456</h4></div>
                                    <div class="ml-auto">
                                        <div id="spark8"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Bounce rate</h4>
                                <div class="d-flex">
                                    <div class="align-self-center">
                                        <h4 class="font-medium m-b-0"><i class="ti-angle-down text-danger"></i>  456</h4></div>
                                    <div class="ml-auto">
                                        <div id="spark9"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Page Views</h4>
                                <div class="d-flex">
                                    <div class="align-self-center">
                                        <h4 class="font-medium m-b-0"><i class="ti-angle-up text-success"></i> 2456</h4></div>
                                    <div class="ml-auto">
                                        <div id="spark10"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex no-block">
                                    <h4 class="card-title">Visit From Countries</h4>
                                    <div class="ml-auto">
                                        <select class="custom-select">
                                            <option selected="">January</option>
                                            <option value="1">February</option>
                                            <option value="2">March</option>
                                            <option value="3">April</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive m-t-20">
                                    <table class="table nowrap stylish-table">
                                        <thead>
                                            <tr>
                                                <th>Language</th>
                                                <th>Vists</th>
                                                <th>%</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <i class="flag-icon flag-icon-us"></i>
                                                    <span class="country-name">U.S.A</span>
                                                </td>
                                                <td>18,224</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-danger " role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%; height:6px;"> <span class="sr-only">50% Complete</span></div>
                                                    </div>
                                                </td>
                                                <td>50%</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="flag-icon flag-icon-gb"></i>
                                                    <span class="country-name">U.K</span>
                                                </td>
                                                <td>12,347</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success " role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:60%; height:6px;"> <span class="sr-only">50% Complete</span></div>
                                                    </div>
                                                </td>
                                                <td>60%</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="flag-icon flag-icon-ca"></i>
                                                    <span class="country-name">Canada</span>
                                                </td>
                                                <td>11,868</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:70%; height:6px;"> <span class="sr-only">50% Complete</span></div>
                                                    </div>
                                                </td>
                                                <td>50%</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="flag-icon flag-icon-de"></i>
                                                    <span class="country-name">Germany</span>
                                                </td>
                                                <td>10,346</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%; height:6px;"> <span class="sr-only">50% Complete</span></div>
                                                    </div>
                                                </td>
                                                <td>50%</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="flag-icon flag-icon-in"></i>
                                                    <span class="country-name">India</span>
                                                </td>
                                                <td>8,354</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-inverse" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:80%; height:6px;"> <span class="sr-only">50% Complete</span></div>
                                                    </div>
                                                </td>
                                                <td>80%</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="flag-icon flag-icon-au"></i>
                                                    <span class="country-name">Australia</span>
                                                </td>
                                                <td>7,675</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-danger " role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%; height:6px;"> <span class="sr-only">50% Complete</span></div>
                                                    </div>
                                                </td>
                                                <td>50%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-4 col-xlg-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Browser Stats</h4>
                                <table class="table browser m-t-30 no-border">
                                    <tbody>
                                        <tr>
                                            <td style="width:40px"><img src="/adminassets/images/browser/chrome-logo.png" alt=logo /></td>
                                            <td>Google Chrome</td>
                                            <td class="text-right"><span class="label label-light-info">23%</span></td>
                                        </tr>
                                        <tr>
                                            <td><img src="/adminassets/images/browser/firefox-logo.png" alt=logo /></td>
                                            <td>Mozila Firefox</td>
                                            <td class="text-right"><span class="label label-light-success">15%</span></td>
                                        </tr>
                                        <tr>
                                            <td><img src="/adminassets/images/browser/safari-logo.png" alt=logo /></td>
                                            <td>Apple Safari</td>
                                            <td class="text-right"><span class="label label-light-primary">07%</span></td>
                                        </tr>
                                        <tr>
                                            <td><img src="/adminassets/images/browser/internet-logo.png" alt=logo /></td>
                                            <td>Internet Explorer</td>
                                            <td class="text-right"><span class="label label-light-warning">09%</span></td>
                                        </tr>
                                        <tr>
                                            <td><img src="/adminassets/images/browser/opera-logo.png" alt=logo /></td>
                                            <td>Opera mini</td>
                                            <td class="text-right"><span class="label label-light-danger">23%</span></td>
                                        </tr>
                                        <tr>
                                            <td><img src="/adminassets/images/browser/internet-logo.png" alt=logo /></td>
                                            <td>Microsoft edge</td>
                                            <td class="text-right"><span class="label label-light-megna">09%</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xlg-9">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Total Visits</h4>
                                <div id="visitfromworld" style="width:100%!important; height:410px"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-4 col-xlg-3">
                        <div class="card card-inverse card-info">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="m-r-20 align-self-center">
                                        <h1 class="text-white"><i class="ti-light-bulb"></i></h1></div>
                                    <div>
                                        <h3 class="card-title">Sales Analytics</h3>
                                        <h6 class="card-subtitle">March  2017</h6> </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 align-self-center">
                                        <h2 class="font-light text-white"><sup><small><i class="ti-arrow-up"></i></small></sup>35487</h2>
                                    </div>
                                    <div class="col-8 p-t-10 p-b-20 text-right">
                                        <div class="spark-count" style="height:65px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-inverse card-danger">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="m-r-20 align-self-center">
                                        <h1 class="text-white"><i class="ti-pie-chart"></i></h1></div>
                                    <div>
                                        <h3 class="card-title">Bandwidth usage</h3>
                                        <h6 class="card-subtitle">March  2017</h6> </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 align-self-center">
                                        <h2 class="font-light text-white">50 GB</h2>
                                    </div>
                                    <div class="col-8 p-t-10 p-b-20 text-right align-self-center">
                                        <div class="spark-count2" style="height:65px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xlg-9">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Sales Difference</h4>
                                <h6 class="card-subtitle">Check the difference between two site</h6>
                                <div id="morris-area-chart2" style="height: 335px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme working">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="/adminassets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © 2019 Admin Press Admin by themedesigner.in1234
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>

@include('admin.layouts.footer') 
@endsection
@extends('layouts.master')
@section('tittle')
لوحة التحكم - برنامج الفواتير
@stop
@section('css')
<!--  Owl-carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
                    @php
                    $currentHour = date('H'); // Get the current hour (24-hour format)
                    $greeting = 'مرحبا'; // Default greeting

                    if ($currentHour >= 5 && $currentHour < 12) {
                        $greeting = 'صباح الخير, '; // Good morning with a comma
                    } elseif ($currentHour >= 12 && $currentHour < 18) {
                        $greeting = 'مساء الخير, '; // Good evening with a comma
                    }
                @endphp

                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">
                    {{ $greeting }}
                    <span style="color: #007bff;">{{ Auth::user()->name }}</span>
                </h2>

				</div>
				<!-- /breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <a href="{{ route('invoices.index') }}" class="text-decoration-none">
                            <div class="card overflow-hidden sales-card bg-primary-gradient">
                                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">اجمالي الفواتير</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                    {{ \App\Models\Invoice::sum('Total') }} جنيه مصري
                                                </h4>
                                                <p class="mb-0 tx-12 text-white op-7">مجموع النتائج</p>
                                            </div>
                                            <span class="float-right my-auto mr-auto">
                                                <i class="fas fa-arrow-circle-up text-white"></i>
                                                <span class="text-white op-7">100%</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-3 pt-3 pr-3 pb-2">
                                    <h6 class="mb-3 tx-12 text-white">عدد الفواتير</h6>
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                        {{ \App\Models\Invoice::count() }}
                                    </h4>
                                </div>
                                <!-- Keep the existing part for the graph -->
                                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <a href="{{ route('invoices.showAllInvoicesPaid') }}" class="text-decoration-none">
                            <div class="card overflow-hidden sales-card bg-success-gradient">
                                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعه</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                    {{ \App\Models\Invoice::where('Value_Status', 1)->sum('Total') }} جنيه مصري
                                                </h4>
                                                <p class="mb-0 tx-12 text-white op-7">مجموع النتائج</p>
                                            </div>
                                            <span class="float-right my-auto mr-auto">
                                                <i class="fas fa-arrow-circle-up text-white"></i>
                                                <span class="text-white op-7">
                                                    @php
                                                        $count_all= \App\Models\Invoice::count();
                                                        $count_invoices1 = \App\Models\Invoice::where('Value_Status', 1)->count();

                                                        if($count_invoices1 == 0){
                                                           echo $count_invoices1 = 0;
                                                        }
                                                        else{
                                                            echo number_format($count_invoices1 = $count_invoices1 / $count_all *100,0) . '%';
                                                        }
                                                    @endphp
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-3 pt-3 pr-3 pb-2">
                                    <h6 class="mb-3 tx-12 text-white">عدد الفواتير</h6>
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                        {{ \App\Models\Invoice::where('Value_Status', 1)->count() }}
                                    </h4>
                                </div>
                                <span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <a href="{{ route('invoices.showAllInvoicesPartialyPaid') }}" class="text-decoration-none">
                            <div class="card overflow-hidden sales-card bg-warning-gradient">
                                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعه جزئيا</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                    {{ \App\Models\Invoice::where('Value_Status', 3)->sum('Total') }} جنيه مصري
                                                </h4>
                                                <p class="mb-0 tx-12 text-white op-7">مجموع النتائج</p>
                                            </div>
                                            <span class="float-right my-auto mr-auto">
                                                <i class="fas fa-arrow-circle-up text-white"></i>
                                                <span class="text-white op-7">
                                                    @php
                                                        $count_all= \App\Models\Invoice::count();
                                                        $count_invoices1 = \App\Models\Invoice::where('Value_Status', 3)->count();

                                                        if($count_invoices1 == 0){
                                                           echo $count_invoices1 = 0;
                                                        }
                                                        else{
                                                            echo number_format($count_invoices1 = $count_invoices1 / $count_all *100,0) . '%';
                                                        }
                                                    @endphp
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-3 pt-3 pr-3 pb-2">
                                    <h6 class="mb-3 tx-12 text-white">عدد الفواتير</h6>
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                        {{ \App\Models\Invoice::where('Value_Status', 3)->count() }}
                                    </h4>
                                </div>
                                <span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <a href="{{ route('invoices.showAllInvoicesUnPaid') }}" class="text-decoration-none">
                            <div class="card overflow-hidden sales-card bg-danger-gradient">
                                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                                    <div class="">
                                        <h6 class="mb-3 tx-12 text-white">الفواتير الغير مدفوعه</h6>
                                    </div>
                                    <div class="pb-0 mt-0">
                                        <div class="d-flex">
                                            <div class="">
                                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                                    {{ \App\Models\Invoice::where('Value_Status', 2)->sum('Total') }} جنيه مصري
                                                </h4>
                                                <p class="mb-0 tx-12 text-white op-7">مجموع النتائج</p>
                                            </div>
                                            <span class="float-right my-auto mr-auto">
                                                <i class="fas fa-arrow-circle-up text-white"></i>
                                                <span class="text-white op-7">
                                                    @php
                                                        $count_all= \App\Models\Invoice::count();
                                                        $count_invoices1 = \App\Models\Invoice::where('Value_Status', 2)->count();

                                                        if($count_invoices1 == 0){
                                                           echo $count_invoices1 = 0;
                                                        }
                                                        else{
                                                           echo number_format($count_invoices1 = $count_invoices1 / $count_all *100,0) . '%';
                                                        }
                                                    @endphp
                                                </span>
                                            </span>                                          </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-3 pt-3 pr-3 pb-2">
                                    <h6 class="mb-3 tx-12 text-white">عدد الفواتير</h6>
                                    <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                        {{ \App\Models\Invoice::where('Value_Status', 2)->count() }}
                                    </h4>
                                </div>
                                <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
                            </div>
                        </a>
                    </div>


				</div>
				<!-- row closed -->

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">نسبة احصائية الفواتير (Bar Charts)</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>
                <div class="card-body" style="width: 67%">
                    {!! $chart1->render() !!}
                    {!! $chart2->render() !!}
                    {!! $chart3->render() !!}
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-6">
            <div class="card card-dashboard-map-one">
                <label class="main-content-label">نسبة احصائية الفواتير (Pie Chart)</label>
                <div class="" style="width: 100%">
                    {!! $chart4->render() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
			</div>
		</div>
		<!-- Container closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>
<!--Internal  Flot js-->
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
<script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
<script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>
<!-- Internal Map -->
<script src="{{URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
<!--Internal  index js -->
<script src="{{URL::asset('assets/js/index.js')}}"></script>
<script src="{{URL::asset('assets/js/jquery.vmap.sampledata.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

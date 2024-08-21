<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Charts;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function index()
    // {
    //     $chart_options = [
    //         'chart_title' => 'Users by months',
    //         'report_type' => 'group_by_date',
    //         'model' => 'App\Models\User',
    //         'group_by_field' => 'created_at',
    //         'group_by_period' => 'month',
    //         'chart_type' => 'bar',
    //         'filter_field' => 'created_at',
    //         'filter_days' => 30, // show only last 30 days
    //     ];

    //     $chart1 = new LaravelChart($chart_options);


    //     $chart_options = [
    //         'chart_title' => 'Users by names',
    //         'report_type' => 'group_by_string',
    //         'model' => 'App\Models\User',
    //         'group_by_field' => 'name',
    //         'chart_type' => 'pie',
    //         'filter_field' => 'created_at',
    //         'filter_period' => 'month', // show users only registered this month
    //     ];

    //     $chart2 = new LaravelChart($chart_options);

    //     $chart_options = [
    //         'chart_title' => 'Transactions by dates',
    //         'report_type' => 'group_by_date',
    //         'model' => 'App\Models\Transaction',
    //         'group_by_field' => 'transaction_date',
    //         'group_by_period' => 'day',
    //         'aggregate_function' => 'sum',
    //         'aggregate_field' => 'amount',
    //         'chart_type' => 'line',
    //     ];

    //     $chart3 = new LaravelChart($chart_options);

    //     return view('index', compact('chart1', 'chart2', 'chart3'));
    // }

//     public function index()
//     {

// //=================احصائية نسبة تنفيذ الحالات======================



//       $count_all =Invoice::count();
//       $count_invoices1 = Invoice::where('Value_Status', 1)->count();
//       $count_invoices2 = Invoice::where('Value_Status', 2)->count();
//       $count_invoices3 = Invoice::where('Value_Status', 3)->count();

//       if($count_invoices2 == 0){
//           $nspainvoices2=0;
//       }
//       else{
//           $nspainvoices2 = $count_invoices2/ $count_all*100;
//       }

//         if($count_invoices1 == 0){
//             $nspainvoices1=0;
//         }
//         else{
//             $nspainvoices1 = $count_invoices1/ $count_all*100;
//         }

//         if($count_invoices3 == 0){
//             $nspainvoices3=0;
//         }
//         else{
//             $nspainvoices3 = $count_invoices3/ $count_all*100;
//         }


//         $chartjs = app()->chartjs
//             ->name('barChartTest')
//             ->type('bar')
//             ->size(['width' => 350, 'height' => 200])
//             ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
//             ->datasets([
//                 [
//                     "label" => "الفواتير الغير المدفوعة",
//                     'backgroundColor' => ['#ec5858'],
//                     'data' => [$nspainvoices2]
//                 ],
//                 [
//                     "label" => "الفواتير المدفوعة",
//                     'backgroundColor' => ['#81b214'],
//                     'data' => [$nspainvoices1]
//                 ],
//                 [
//                     "label" => "الفواتير المدفوعة جزئيا",
//                     'backgroundColor' => ['#ff9642'],
//                     'data' => [$nspainvoices3]
//                 ],


//             ])
//             ->options([]);


//         $chartjs_2 = app()->chartjs
//             ->name('pieChartTest')
//             ->type('pie')
//             ->size(['width' => 340, 'height' => 200])
//             ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
//             ->datasets([
//                 [
//                     'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
//                     'data' => [$nspainvoices2, $nspainvoices1,$nspainvoices3]
//                 ]
//             ])
//             ->options([]);

//         return view('index', compact('chartjs','chartjs_2'));

//     }


// public function index()
// {
//     $chart_options1 = [
//         'chart_title' => 'نسبة الفواتير الغير مدفوعه',
//         'report_type' => 'group_by_string',
//         'model' => 'App\Models\Invoice',
//         'group_by_field' => 'Value_Status',
//         'chart_type' => 'bar',
//         'filter_field' => 'Value_Status',
//         'filter_value' => 2, // Only include invoices where Value_Status = 1
//     ];

//     $chart1 = new LaravelChart($chart_options1);



//     $chart_options2 = [
//         'chart_title' => 'نسبة الفواتير المدفوعة',
//         'report_type' => 'group_by_string',
//         'model' => 'App\Models\Invoice',
//         'group_by_field' => 'Value_Status',
//         'chart_type' => 'bar',
//         'filter_field' => 'Value_Status',
//         'filter_value' => 1, // Only include invoices where Value_Status = 1
//     ];

//     $chart2 = new LaravelChart($chart_options2);

//     $chart_options3 = [
//         'chart_title' => 'نسبة الفواتير المدفوعة جزئيا',
//         'report_type' => 'group_by_string',
//         'model' => 'App\Models\Invoice',
//         'group_by_field' => 'Value_Status',
//         'chart_type' => 'bar',
//         'filter_field' => 'Value_Status',
//         'filter_value' => 3, // Only include invoices where Value_Status = 1
//     ];

//     $chart3 = new LaravelChart($chart_options3);

//     $chart_options4 = [
//         'chart_title' => 'نسبة تنفيذ الحالات (Pie Chart)',
//         'report_type' => 'group_by_string',
//         'model' => 'App\Models\Invoice',
//         'group_by_field' => 'Value_Status',
//         'chart_type' => 'pie',
//         'filter_field' => 'Value_Status',
//     ];

//     $chart4 = new LaravelChart($chart_options4);

//     return view('index', compact('chart1', 'chart2', 'chart3', 'chart4'));
// }

public function index()
{
    // Unpaid Invoices Chart
    $unpaidInvoices = Invoice::where('Value_Status', 2)->count();
    $paidInvoices = Invoice::where('Value_Status', 1)->count();
    $partiallyPaidInvoices = Invoice::where('Value_Status', 3)->count();

    $chart1 = app()->chartjs
        ->name('unpaidInvoicesChart')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['الفواتير الغير مدفوعه'])
        ->datasets([
            [
                "label" => "الفواتير الغير مدفوعه",
                'backgroundColor' => ['#FF6384'],
                'data' => [$unpaidInvoices],
            ]
        ])
        ->options([]);

    // Paid Invoices Chart
    $chart2 = app()->chartjs
        ->name('paidInvoicesChart')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['الفواتير المدفوعة'])
        ->datasets([
            [
                "label" => "الفواتير المدفوعة",
                'backgroundColor' => ['#36A2EB'],
                'data' => [$paidInvoices],
            ]
        ])
        ->options([]);

    // Partially Paid Invoices Chart
    $chart3 = app()->chartjs
        ->name('partiallyPaidInvoicesChart')
        ->type('bar')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['الفواتير المدفوعة جزئيا'])
        ->datasets([
            [
                "label" => "الفواتير المدفوعة جزئيا",
                'backgroundColor' => ['#FFCE56'],
                'data' => [$partiallyPaidInvoices],
            ]
        ])
        ->options([]);

    // Pie Chart for Overall Status
    $chart4 = app()->chartjs
        ->name('overallInvoicesChart')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['الفواتير الغير مدفوعه', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
        ->datasets([
            [
                'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                'data' => [$unpaidInvoices, $paidInvoices, $partiallyPaidInvoices]
            ]
        ])
        ->options([]);

    return view('index', compact('chart1', 'chart2', 'chart3', 'chart4'));
}




}

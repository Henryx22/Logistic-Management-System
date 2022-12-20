<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\Sale;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $title = 'dashboard';
        //$total_purchases = Purchase::where('expiry_date','!=',Carbon::now())->count();
        $total_categories = Category::count();
        //$total_suppliers = Supplier::count();
        $tot_sales = Sale::count();
        $total_deliveries=Delivery::count();
        
        $pieChart = app()->chartjs
                ->name('pieChart')
                ->type('pie')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['Total Ventas','Total Entregas'])
                ->datasets([
                    [
                        'backgroundColor' => ['#7bb13c','#FF6384'],
                        'hoverBackgroundColor' => ['#7bb13c','#FF6384'],
                        'data' => [$tot_sales,$total_deliveries]
                    ]
                ])
                ->options([]);
        
        //$total_expired_products = Purchase::whereDate('expiry_date', '=', Carbon::now())->count();
        $latest_sales = Sale::whereDate('created_at','=',Carbon::now())->get();
        $total_sales = Sale::get()->sum('total_price');
        $total_salary = Delivery::get()->sum('salaryPH');
        
        return view('admin.dashboard',compact(
            'title','pieChart',
            'latest_sales','total_sales','total_categories','total_salary',
        ));
    }
}

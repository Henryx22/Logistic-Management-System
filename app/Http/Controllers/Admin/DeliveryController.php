<?php

namespace App\Http\Controllers\Admin;

// use App\Models\Sale;

use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Events\PurchaseOutStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\factory;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function indexAux(Request $request)
    {
        $title = 'sales';
        if($request->ajax()){
            $sales = Sale::latest();
            return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('product',function($sale){
                        $image = '';
                        if(!empty($sale->product)){
                            $image = null;
                            if(!empty($sale->product->purchase->image)){
                                $image = '<span class="avatar avatar-sm mr-2">
                                <img class="avatar-img" src="'.asset("storage/purchases/".$sale->product->purchase->image).'" alt="image">
                                </span>';
                            }
                            return $sale->product->purchase->product. ' ' . $image;
                        }                 
                    })
                    ->addColumn('total_price',function($sale){                   
                        return settings('app_currency','$').' '. $sale->total_price;
                    })
                    ->addColumn('date',function($row){
                        return date_format(date_create($row->created_at),'d M, Y');
                    })
                    ->addColumn('action', function ($row) {
                        $editbtn = '<a href="'.route("sales.edit", $row->id).'" class="editbtn"><button class="btn btn-primary"><i class="fas fa-edit"></i></button></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('sales.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if (!auth()->user()->hasPermissionTo('edit-sale')) {
                            $editbtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('destroy-sale')) {
                            $deletebtn = '';
                        }
                        $btn = $editbtn.' '.$deletebtn;
                        return $btn;
                    })
                    ->rawColumns(['product','action'])
                    ->make(true);

        }
        $products = Product::get();
        return view('admin.delivery.index',compact(
            'title','products',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'create sales';
        $products = Product::get();
        return view('admin.sales.create',compact(
            'title','products'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'product'=>'required',
            'quantity'=>'required|integer|min:1'
        ]);
        $sold_product = Product::find($request->product);
        
        /**update quantity of
            sold item from
         purchases
        **/
        $purchased_item = Purchase::find($sold_product->purchase->id);
        $new_quantity = ($purchased_item->quantity) - ($request->quantity);
        $notification = '';
        if (!($new_quantity < 0)){

            $purchased_item->update([
                'quantity'=>$new_quantity,
            ]);

            /**
             * calcualting item's total price
            **/
            $total_price = ($request->quantity) * ($sold_product->price);
            Sale::create([
                'product_id'=>$request->product,
                'quantity'=>$request->quantity,
                'total_price'=>$total_price,
                'status'=>"Pendiente",
            ]);

            $notification = notify("Product has been sold");
        } 
        if($new_quantity <=1 && $new_quantity !=0){
            // send notification 
            $product = Purchase::where('quantity', '<=', 1)->first();
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = notify("Product is running out of stock!!!");
            
        }

        return redirect()->route('sales.index')->with($notification);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \app\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        $title = 'edit sale';
        $products = Product::get();
        return view('admin.sales.edit',compact(
            'title','sale','products'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \app\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        $this->validate($request,[
            'product'=>'required',
            'quantity'=>'required|integer|min:1'
        ]);
        $sold_product = Product::find($request->product);
        /**
         * update quantity of sold item from purchases
        **/
        $purchased_item = Purchase::find($sold_product->purchase->id);
        if(!empty($request->quantity)){
            $new_quantity = ($purchased_item->quantity) - ($request->quantity);
        }
        $new_quantity = $sale->quantity;
        $notification = '';
        if (!($new_quantity < 0)){
            $purchased_item->update([
                'quantity'=>$new_quantity,
            ]);

            /**
             * calcualting item's total price
            **/
            if(!empty($request->quantity)){
                $total_price = ($request->quantity) * ($sold_product->price);
            }
            $total_price = $sale->total_price;
            $sale->update([
                'product_id'=>$request->product,
                'quantity'=>$request->quantity,
                'total_price'=>$total_price,
            ]);

            $notification = notify("Product has been updated");
        } 
        if($new_quantity <=1 && $new_quantity !=0){
            // send notification 
            $product = Purchase::where('quantity', '<=', 1)->first();
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = notify("Product is running out of stock!!!");
            
        }
        return redirect()->route('sales.index')->with($notification);
    }

    /**
     * Generate sales reports index
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request){
        $title = 'deliveries reports';
        return view('admin.delivery.reports',compact(
            'title'
        ));
    }

    /**
     * Generate sales report form post
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request){
        /**
        $this->validate($request,[
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        */
        $title = 'deliveries reports';
        $deliveries = Delivery::get();
        return view('admin.delivery.reports',compact(
            'deliveries','title'
        ));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Sale::findOrFail($request->id)->delete();
    }

    public function index(Request $request)
    {
        $title = 'deliveries';
        if($request->ajax()){
            $sales = Sale::get();
            return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('id',function($sale){                   
                        return ' '.$sale->id;
                    })
                    ->addColumn('product',function($sale){
                        $image = '';
                        if(!empty($sale->product)){
                            $image = null;
                            if(!empty($sale->product->purchase->image)){
                                $image = '<span class="avatar avatar-sm mr-2">
                                <img class="avatar-img" src="'.asset("storage/purchases/".$sale->product->purchase->image).'" alt="image">
                                </span>';
                            }
                            return $sale->product->purchase->product. ' ' . $image;
                        }                 
                    })
                    /**
                    ->addColumn('user',function($sale){
                        //$idUser = $sale->user_id;
                        //$user = User::where('id', '<=', $sale->user_id)->first();
                        $user = DB::table('users')->where('id', $sale->user_id);

                        return ''.$user->name;
                    })
                    */
                /**
                    ->addColumn('location',function($sale){
                        $idUser = $sale->user_id;
                        $user = DB::select('select * from users where id = :id', ['id' => $idUser]);
                        
                        return $user->location;
                    })
                    */
                    
                    ->addColumn('total_price',function($sale){                   
                        return settings('app_currency','$').' '. $sale->total_price;
                    })
                    ->addColumn('date_up',function($row){
                        return date_format(date_create($row->created_at),'d M, Y');
                    })
                    ->addColumn('date_down',function($row){
                        return date_format(date_create($row->updated_at),'d M, Y');
                    })
                /**    
                    ->addColumn('action', function ($row) {
                        $editbtn = '<a href="'.route("sales.edit", $row->id).'" class="editbtn"><button class="btn btn-primary"><i class="fas fa-edit"></i></button></a>';
                        $deletebtn = '<a data-id="'.$row->id.'" data-route="'.route('sales.destroy', $row->id).'" href="javascript:void(0)" id="deletebtn"><button class="btn btn-danger"><i class="fas fa-trash"></i></button></a>';
                        if (!auth()->user()->hasPermissionTo('edit-sale')) {
                            $editbtn = '';
                        }
                        if (!auth()->user()->hasPermissionTo('destroy-sale')) {
                            $deletebtn = '';
                        }
                        $btn = $editbtn.' '.$deletebtn;
                        return $btn;
                    })
                */    
                    ->rawColumns(['product','action'])
                    ->make(true);

        }
        $products = Product::get();
        return view('admin.delivery.index',compact(
            'title','products',
        ));
    }

    public function indexProcessed(Request $request)
    {
        //processDelivery();

        $title = 'deliveries';
        if($request->ajax()){
            $sales = Delivery::get();
            return DataTables::of($sales)
                    ->addIndexColumn()
                    ->addColumn('id',function($sale){                   
                        return ' '. $sale->id;
                    })
                    ->addColumn('day',function($sale){                   
                        return ' '. $sale->day;
                    })

                    ->addColumn('time',function($sale){                   
                        return ' '. $sale->time;
                    })
                    
                    ->addColumn('deliveriesPH',function($sale){                   
                        return ' '. $sale->deliveriesPH;
                    })
                    ->addColumn('quantityPH',function($sale){                   
                        return ' '. $sale->quantityPH;
                    })
                    ->addColumn('solvedPH',function($sale){                   
                        return ' '. $sale->solvedPH;
                    })

                    ->addColumn('progressPH',function($sale){                   
                        return ' '. $sale->progressPH;
                    })
                    ->addColumn('workersPH',function($sale){                   
                        return ' '. $sale->workersPH;
                    })
                    
                    ->rawColumns(['product','action'])
                    ->make(true);

        }
        $products = Product::get();
        return view('admin.delivery.processed',compact(
            'title','products',
        ));
    }

    public function generateSales(Request $request)
    {

        /**
    
    $user = \App\Models\User::factory()->create();
        \App\Models\Course::factory()->count(5)->create(['user_id' => $user->id ])->each(function ($course) {
            \App\Models\Episode::factory()->count(rand(6 , 20))->make()->each(function ($episode , $key) use ($course){
                $episode->number = $key +1;
                $course->episodes()->save($episode);
            });
        });
*/
        //$new_user = factory(App\User::class)->create();
        //$new_sale = factory(App\Sale::class)->create(1);
//        Sale::factory()->count(1)->create();
        \App\Models\Sale::factory()->count(24)->create();

        return view('welcome');
    }
        
    public function processDelivery(Request $request)
    {
        Delivery::truncate();
/**
        foreach(Delivery::all() as $d){
            $d->delete();
            echo $d;
        }
*/
        $aux;
        $day=1;
        $hr=8;

        $ci=0;    //$deliveriesPH;    
        $cpr=0;   //$solvedPH;   
        $cpH;   //$quantityPH;   
        $np=0;    //$workersPH;
        $cmt = 30;
        //$scap=0;  //acumulado horas fuera de trabajo  
        
        $prog=0;  //$progressPH;   
        $sales = Sale::get();

    //$np = ($cpr+$ci)/$cmt;

        $i=1;
            foreach ($sales as $sale) {
                $cph = $sale->quantity;
            
            /**    
                echo $day;
                echo "\n\n";
                echo $i;
                echo "\n\n";
                echo $cph;
            */    
    
                //si hora entre 01 a 04
                if($i<5){
                    $prog=$prog+$cph;
                    $ci=$prog;
                    
                }else{
                    if($i==5){
                        $ci=$prog+$cph;

                        if ($day>2) {
                            if($ci>1000){
                                $aux = ($ci)/$hr;
                                $hr=$hr-1;

                                if(($aux%30)==0){
                                    $np = floor($aux/$cmt);   //parte entera division
                                }else{
                                    $np = ceil($aux/$cmt);    //redondeo a parte superior
                                }
                            }    
                        }
                                                    
                    }else{
                        if ($i<8){
                            $ci = $cph;

                            if($day>2){
                                if(($prog+$ci)>1000){
                                    $aux = ($prog + $ci)/$hr;
                                    $hr=$hr-1;

                                    if(($aux%30)==0){
                                        $np = floor($aux/$cmt);   //parte entera division
                                    }else{
                                        $np = ceil($aux/$cmt);    //redondeo a parte superior
                                    }
                                }
                            }

                        }else{
                                
                            if (($i>7)&&($i<13)){
                                $ci=$cph;
                                        
                                if ($i==8){
                                            
                                    if((($day==1)&&($prog>3000))||(($day==2)&&($prog>3000))||($day>2)){
                                
                                        if(($prog+$ci)>0){
                                            $aux = ($prog + $ci)/$hr;
                                            $hr=$hr-1;

                                            if(($aux%30)==0){
                                                $np = floor($aux/$cmt);   //parte entera division
                                            }else{
                                                $np = ceil($aux/$cmt);    //redondeo a parte superior
                                            }
                                        }
                                    }else{
                                        $np=0;
                                    }
                                }
                            }else{
                                if ($i==13) {
                                    if ($np>0) {
                                        $ci=$ci+$cph;
                                        $np=0;
                                    }else{
                                        $ci=$cph;
                                    }
                                    
                                }else{
                                    if (($i>13)&&($i<22)) {
                                        $ci=$cph;

                                        if ($i==14) {
                                            if((($day==1)&&($prog>3000))||(($day==2)&&($prog>3000))||($day>2)){
                                                if(($prog+$ci)>0){
                                                    $hr=3;
                                                    $aux = ($prog + $ci)/$hr;
                                                            
                                                    if(($aux%30)==0){
                                                        $np = floor($aux/$cmt);   //parte entera division
                                                    }else{
                                                        $np = ceil($aux/$cmt);    //redondeo a parte superior
                                                    }
                                                }
                                            }
                                        }

                                        if ($i==17) {
                                            if((($day==1)&&($prog>3000))||(($day==2)&&($prog>3000))||($day>2)){
                                                if(($prog+$ci)>0){
                                                    $hr=1;
                                                    $aux = ($prog + $ci)/$hr;
                                                            
                                                    if(($aux%30)==0){
                                                        $np = floor($aux/$cmt);   //parte entera division
                                                    }else{
                                                        $np = ceil($aux/$cmt);    //redondeo a parte superior
                                                    }
                                                    $hr=3;
                                                }
                                            }
                                        }

                                        if ($i==18) {
                                            $np=0;
                                        }                 

                                        if (($i>18)&&($i<22)) {
                                            if (($day==3)||($day==7)) {
                                                if ($i==19) {
                                                    if(($prog+$ci)>0){
                                                        $hr=3;
                                                        $aux = ($prog + $ci)/$hr;
                                                                
                                                        $np = floor($aux/$cmt);   //parte entera division
                                                                
                                                    }    
                                                }
                                            }                       
                                        }

                                    }else{
                                        $np=0;
                                    }
                                }
                            }    
                        }
                    }

                    $cpr = $ci - ($np*$cmt);

                    if(($prog+$cpr)>0){
                        
                        if ($i==5) {
                            $prog=$cpr;
                        }else{
                            $prog=$prog+$cpr;    
                        }        
                    }else{
                        $prog=0;
                    }
                }

            /**    
                echo "\n\n";
                echo $ci;
                echo "\n\n";
                echo $cpr;
                echo "\n\n";
                echo $prog;
                echo "\n\n";
                echo $np;
            */
            $dayText="";

            if($day==1){
                    $dayText="Sabado";    
                }else{
                    if($day==2){
                        $dayText="Domingo";    
                    }else{
                        if($day==3) {
                            $dayText="Lunes";
                        }else{
                            if($day==4) {
                                $dayText="Martes";
                            }else{
                                if($day==5) {
                                    $dayText="Miercoles";
                                }else{
                                    if($day==6) {
                                        $dayText="Jueves";
                                    }else{
                                        if ($day==7) {
                                            $dayText="Viernes";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            Delivery::create([
                
                'day'=>$dayText,
                'time'=>$i,
                'deliveriesPH'=>$cph,
                'quantityPH'=>$ci,
                'solvedPH'=>$cpr,
                'progressPH'=>$prog,
                'workersPH'=>$np,
            ]);
            
                if($i==24){
                    $i=1;
                    $hr=8;
                    $np=0;

                    if($day==7){
                        $day=1;
                    }else{
                        $day= $day+1;
                    }

                }else{
                    $i=$i+1;
                }                
        }
        
        return view('welcome');
    }
}

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
     * Generar pagina inicial para reporte de pedidos procesados
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
     * Generar reporte de Pedidos procesados desde post
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request){
    
        $this->validate($request,[
        ]);
        
        $title = 'deliveries reports';
        $deliveries = Delivery::get();
        return view('admin.delivery.reports',compact(
            'deliveries','title'
        ));
    }

    /**
     * Generar reporte de Pedidos procesados desde post de pantalla inicial
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function genRep(Request $request){
        
        $title = 'deliveries reports';
        $deliveries = Delivery::get();
        return view('deliveryRep',compact(
            'deliveries','title'
        ));
    }

    /**
     * Generar Pagina inicial de listado de pedidos recibidos
     *
     * 
     */
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
                    
                    ->addColumn('total_price',function($sale){                   
                        return settings('app_currency','$').' '. $sale->total_price;
                    })
                    ->addColumn('date_up',function($row){
                        return date_format(date_create($row->created_at),'d M, Y');
                    })
                    ->addColumn('date_down',function($row){
                        return date_format(date_create($row->updated_at),'d M, Y');
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
     * Generar listado de Pedidos Procesados
     *
     * 
     */
    public function indexProcessed(Request $request)
    {
        //llamamos al metodo que procesa los pedidos y los agrega a la base de datos
        app(DeliveryController::class)->processDeliv($request);


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
                    ->addColumn('salaryPH',function($sale){                   
                        return ' '. $sale->salaryPH;
                    })
                    
                    ->rawColumns(['product','action'])
                    ->make(true);

        }
        $products = Product::get();
        return view('admin.delivery.processed',compact(
            'title','products',
        ));
    }

    /**
     * Generar Ventas de forma automatica para las 24 horas del dia
     * para lo cual se pide la cantidad de dias que se desea generar
     * (tomese en cuenta que los dias siguen el orden 
     *  Sabado,domingo,Lunes,Martes,Miercoles,Jueves,Viernes
     * )
     */
    public function generateSales(Request $request)
    {
        $this->validate($request,[
            'numDays'=>'required',
        ]);
        $aux=($request->numDays)*24;
        \App\Models\Sale::factory()->count($aux)->create();

        return view('welcome');
    }
        
    /**
    *  Metodo de procesamiento de pedidos, calculando la cantidad 
    *  de personal necesario 
    */
    public function processDeliv(Request $request)
    {
        Delivery::truncate();

        $aux;
        $day=1;     // contador de dia de la semana tomando el valor 
                    // de 1 como Sabado y asi sucesivamente 

        $hr=8;  //numero de horas de trabajo

        $ci=0;    //$deliveriesPH;    
        $cpr=0;   //$solvedPH;   
        $cpH;   //$quantityPH;   
        $np=0;    //$workersPH;
        $cmt = 30;
        
        $prog=0;  //$progressPH;
        $salary=0;   
        $sales = Sale::get();

        $i=1;   // contador de horas del dia 1,2...24

            foreach ($sales as $sale) {
                $cph = $sale->quantity;
            
                //si hora entre 01 a 04 
                if($i<5){
                    $prog=$prog+$cph;   //se suma la cantidad entrante x hr al progreso total
                    $ci=$prog;
                    
                }else{
                    if($i==5){
                        $ci=$prog+$cph;

                        //se verifica si es dia laboral normal
                        if ($day>2) {       

                            // se verifica si los pedidos pendientes son mayores a 1000
                            // para asignar personal de emergencia en horas extra
                            if($ci>1000){
                                $aux = ($ci)/$hr;
                                $hr=$hr-1;

                                if(($aux%30)==0){
                                    $np = floor($aux/$cmt);   //parte entera division
                                }else{
                                    $np = ceil($aux/$cmt);    //redondeo a parte superior
                                }
                            }
                            $salary=$np*50;     //se paga 50 bs por hora extra a cada trabajador 
                        }
                                                    
                    }else{

                        // se procesa el personal necesario para horas extra 
                        if ($i<8){
                            $ci = $cph;

                            //se verifica si es dia laboral normal
                            if($day>2){
                            
                                // se verifica si los pedidos pendientes son mayores a 1000
                                // para asignar personal de emergencia en horas extra
                                if(($prog+$ci)>1000){
                                    $aux = ($prog + $ci)/$hr;
                                    $hr=$hr-1;

                                    if(($aux%30)==0){
                                        $np = floor($aux/$cmt);   //parte entera division
                                    }else{
                                        $np = ceil($aux/$cmt);    //redondeo a parte superior
                                    }
                                }
                                $salary=$np*50;    //se paga 50 bs por hora extra
                            }

                        }else{
                                
                            if (($i>7)&&($i<13)){
                                $ci=$cph;
                                        
                                if ($i==8){
                                    
                                    // se verifica si los pedidos pendientes son mayores a 3000
                                    // en dias Sabado y Domingo, o si es dia laboral normal
                                    // para asignar el personal necesario       
                                    if((($day==1)&&($prog>3000))||(($day==2)&&($prog>3000))||($day>2)){
                                        
                                        // se procesa el personal necesario 
                                        // para la jornada laboral
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

                                    if($day>2) {
                                        $salary=$np*100;    //se paga 100 bs por hora en dia normal
                                    }else{
                                        $salary=$np*200;    //se paga 200 bs por hora en sabado y domingo
                                    }
                                }
                            }else{

                                // si hora == 13 se reestablece el contador de personal
                                // dedido a la hora de descanso o almuerzo
                                if ($i==13) {
                                    if ($np>0) {
                                        $ci=$ci+$cph;
                                        $np=0;
                                    }else{
                                        $ci=$cph;
                                    }
                                    $salary=0;
                                    
                                }else{
                                    if (($i>13)&&($i<22)) {
                                        $ci=$cph;

                                        if ($i==14) {

                                            // se verifica si los pedidos pendientes son mayores a 3000
                                            // en dias Sabado y Domingo, o si es dia laboral normal
                                            // para asignar el personal necesario       
                                    
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

                                            // se verifica si los pedidos pendientes son mayores a 3000
                                            // en dias Sabado y Domingo, o si es dia laboral normal
                                            // para asignar el personal necesario 
                                            // para la ultima hora antes de la hora de entrega    
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

                                        // si hora == 18 se reestablece el contador de personal
                                        // dedido a la hora de despacho de pedidos
                                        if ($i==18) {
                                            $np=0;
                                        }

                                        if (($i>13)&&($i<19)) {
                                            if($day>2) {
                                                $salary=$np*100;    //se paga 100 bs por hora en dia normal
                                            }else{
                                                $salary=$np*200;    //se paga 200 bs por hora en sabado y domingo
                                            }
                                        }                 

                                        if (($i>18)&&($i<22)) {

                                            // se verifica si el dia de trabajo es Lunes o Viernes
                                            // para asignar personal de emergencia en horas extra
                                            // para reducir la cantidad de pedidos de manera tolerable
                                
                                            if (($day==3)||($day==7)) {
                                                if ($i==19) {
                                                    if(($prog+$ci)>0){
                                                        $hr=3;
                                                        $aux = ($prog + $ci)/$hr;
                                                                
                                                        $np = floor($aux/$cmt);   //parte entera inferior
                                                                
                                                    }    
                                                }
                                            }

                                            $salary=$np*50;    //se paga 50 bs por hora extra
                                                                   
                                        }

                                    }else{
                                    
                                        // si (hora > 21) && (hora <= 24) se reestablece el 
                                        // contador de personal dedido a las horas sin atencion
                                        $np=0;
                                        $salary=0;
                                    }
                                }
                            }    
                        }
                    }

                    // se calcula los pedidos procesados por hora
                    $cpr = $ci - ($np*$cmt);

                    // se actualiza el progreso de pedidos pendientes
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

            // se convierte los valores de dia a su significado 
            // para agregarlos a la base de datos
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

            // se carga los datos obtenidos a la tabla Delivery
            Delivery::create([
                
                'day'=>$dayText,
                'time'=>$i,
                'deliveriesPH'=>$cph,
                'quantityPH'=>$ci,
                'solvedPH'=>$cpr,
                'progressPH'=>$prog,
                'workersPH'=>$np,
                'salaryPH'=>$salary,
            ]);
            
                // se controla el incremento de las variables
                // de control de dia y hora
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
        
        
    }
    
    /**
    *  Procesamiento de pedidos desde pantalla inicial 
    *  sin necesidad de inicio de sesion
    */
    public function processDelivery(Request $request)
    {
        app(DeliveryController::class)->processDeliv($request);
        return view('welcome');
    }    
}



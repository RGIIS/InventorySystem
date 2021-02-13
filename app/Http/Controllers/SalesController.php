<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemSold;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SalesController extends Controller
{
    //
    public function showDailySales(Request $request)
    {
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        

        $dailySales = (array) null;
        $overAllTotal = 0;
        $fromDate=Carbon::now();
        $toDate=Carbon::now();
        if($request->filled('startdate')&&$request->filled('enddate'))
        {
            $startDate = $request->startdate;
            $endDate = $request->enddate;
          
            // $period = CarbonPeriod::create($startDate,'1 day',$endDate);
            $period = Carbon::parse($startDate)->toPeriod($endDate);

            foreach($period as $date)
            {
              
                $itemSoldQuantity = ItemSold::whereBetween('created_at',[$date->startOfDay()->toDateTimeString(),$date->endOfDay()->toDateTimeString()])->sum('quantity');
                $itemSoldTotal = ItemSold::whereBetween('created_at',[$date->startOfDay()->toDateTimeString(),$date->endOfDay()->toDateTimeString()])->sum('total');
                $overAllTotal+=$itemSoldTotal;
                array_push($dailySales,[
                    'date'=> $date->toDateString(),
                    'itemSoldQuantity' => $itemSoldQuantity,
                    'totalAmount' => $itemSoldTotal
                ]);
            }
            $fromDate = new Carbon($startDate);
            $toDate = new Carbon($endDate);
           
        }
       else
       {
       
        $loopDays = 0;
       
        if($request->filled('days'))
        {
            $loopDays = $request->days;
            $fromDate = Carbon::now();
            $toDate = Carbon::now()->subDay($request->days);
        }
        
        
       
       for($i = 0; $i<=$loopDays;$i++)
       {
        $now = Carbon::now();
        $day =$now->subDay($i);
        $startOfDay = $day->startOfDay()->toDateTimeString();
        $endOfDay = $day->endOfDay()->toDateTimeString();
        $itemSoldQuantity = ItemSold::whereBetween('created_at',[$startOfDay,$endOfDay])->sum('quantity');
        $itemSoldTotal = ItemSold::whereBetween('created_at',[$startOfDay,$endOfDay])->sum('total');
        $overAllTotal+=$itemSoldTotal;
        array_push($dailySales,[
            'date'=> $day->toDateString(),
            'itemSoldQuantity' => $itemSoldQuantity,
            'totalAmount' => $itemSoldTotal
        ]);
        //  array_push($last7Days,$day->toDateString());
       }
      
    }
    
        return view('dailySales')->with('dailySales',$dailySales)
                                ->with('overAllTotal',$overAllTotal)
                                ->with('fromDate',$fromDate->format('F d, Y'))
                                ->with('toDate',$toDate->format('F d, Y'));

    }

    public function showMonthlySales(Request $request)
    {
           
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        
    //    dd($request->filled('year'));
        $monthlyArr = (array) null;
        
       $now = Carbon::now();
      
       
     
        for($i=0; $i<=11 ;$i++)
        {
           
            if($request->filled('year'))
            {

                $now->year = $request->year;
               
               
            }
            $startOfMonth = $now->startOfYear()->addMonth($i)->startOfMonth()->toDateTimeString();
            $endOfMonth = $now->startOfYear()->addMonth($i)->endOfMonth()->toDateTimeString();
            
          $itemSoldQuantity = ItemSold::whereBetween('created_at',[$startOfMonth,$endOfMonth])->sum('quantity');
          $itemSoldTotal = ItemSold::whereBetween('created_at',[$startOfMonth,$endOfMonth])->sum('total');
            // $itemSoldQuantity = ItemSold::whereBetween('created_at',[$startOfMonth->subMonth($i)->subDay(1)->startOfMonth()->toDateTimeString(),$endOfMonth->subMonth($i)->subDay(1)->endOfMonth()->toDateTimeString()])->sum("quantity");
            // $itemSoldTotal = ItemSold::whereBetween('created_at',[$startOfMonth,$endOfMonth])->sum("total");
           $monthName = Carbon::now()->addMonth($i)->startOfMonth()->format('F');
                //     $optionString = Carbon::now()->subMonth($i)->startOfMonth()->format('F j, Y')." - ".Carbon::now()->subMonth($i)->endOfMonth()->format('F j, Y');
            // }
            array_push($monthlyArr,[
                'monthName'=> $monthName. " - ".$now->year,
                'itemSoldQuantity' => $itemSoldQuantity,
                'totalAmount' => $itemSoldTotal
            ]);

           

           
        }
        
        return view('monthlySales')->with('monthlyArray',$monthlyArr);

    
        

    }

    public function loadDailySales()
    {
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        
       
        $arr = (array) null;
        for($i=0;$i<7;$i++)
        {
            $startOfWeek = Carbon::now()->startOfWeek()->addDay($i)->toDateString();
            $itemSold = ItemSold::whereBetween('created_at',[$startOfWeek." 00:00:00",$startOfWeek." 23:59:59"])->sum('total');
            array_push($arr,$itemSold);
        }
        
       
        return \Response::json($arr);
       
        // $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();
        // $getDailySales = ItemSold::whereBetween('created_at',[$startOfWeek,$endOfWeek])->get();
        // return \Response::json($getDailySales);
    }

    public function loadMonthlySales()
    {
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        
       
        $arr = (array) null;
        for($i=0;$i<12;$i++)
        {
            $startOfYear = Carbon::now()->startOfYear()->addMonth($i);
            $endOfMonth = $startOfYear->endOfMonth()->toDateString();;
            $itemSold = ItemSold::whereBetween('created_at',[$startOfYear->firstOFMonth()." 00:00:00",$endOfMonth." 23:59:59"])->sum('total');
            array_push($arr,$itemSold);
        }
        
       
        return \Response::json($arr);
       
        // $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();
        // $getDailySales = ItemSold::whereBetween('created_at',[$startOfWeek,$endOfWeek])->get();
        // return \Response::json($getDailySales);
    }

    public function showDetailedSales(Request $request)
    {
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        
        // $dates = (array) null;
        // $itemSold = (array) null;
        $startDate = Carbon::now()->toDateString();
        $endDate = Carbon::now()->toDateString();
        if($request->filled('startDate')&&$request->filled('endDate'))
        {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        }
        $itemSold = ItemSold::whereBetween('created_at',[$startDate." 00:00:00",$endDate." 23:59:59"]);
       if($request->filled('customer'))
       {
        $itemSold = $itemSold->where('costumer_name',$request->customer);
       }
       if($request->filled('receipt'))
       {
        $itemSold = $itemSold->where('receipt_number',$request->receipt);
       }
       if($request->filled('cashier'))
       {
        $itemSold = $itemSold->where('cashier',$request->cashier);
       }

        
                            // ->where('cashier','Revan')
                            // ->latest('created_at')->simplePaginate(10);
        // $period = Carbon::parse($startDate)->toPeriod($endDate);

        // foreach ($period as $date) {
        //     $start = $date->startOfDay()->toDateTimeString();
        //     $end = $date->endOfDay()->toDateTimeString();
        //     $itemSoldData = ItemSold::whereBetween('created_at',[$start,$end])->get();
        //     array_push($itemSold,$itemSoldData->toArray());

        // }
        $itemSold = $itemSold->latest('created_at')->simplePaginate(10);
       
        return view('detailedSales')->with('itemSold',$itemSold);

    }

    public function removeItem(Request $request)
    {
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        
        $itemSold = ItemSold::where('id',$request->id)->delete();
    }

    public function clearSales()
    {
        $loginUser = session('User');
        if(is_null($loginUser))
        {
            return redirect('/')->with('error','Please Login');
        }
        if($loginUser->role=='inventory')
        {
             return redirect()->route('inventory');
        }
        
        $itemSold = ItemSold::truncate();
    }

}

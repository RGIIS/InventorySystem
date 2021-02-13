<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\ItemSold;
use App\Models\CashierAccount;
use App\Models\Log;

class CashierController extends Controller
{
    public function pos(Request $request)
    {
        
        $loginCashier = session('cashier');

        if($loginCashier)
        {
        $inventory = Inventory::orderBy('name','asc')->get();
        if($request->filled('page'))
        {
            return response($inventory,200);
        }
        return view('cashier.pos')->with('inventory',$inventory)
                                    ->with('loginCashier',$loginCashier);
        }
        else
        {
            return redirect()->route('cashierLogin');
        }
    }

    public function recordTransaction(Request $request)
    {
        $loginCashier = session('cashier');
        if(is_null($loginCashier))
        {
            return redirect('/')->with('error','Please Login');
        }
       
        try {
            $itemSold = new ItemSold;
            $itemSold->item_name = $request->name;
            $itemSold->price = $request->price;
            $itemSold->quantity = $request->quantity;
            $itemSold->total = $request->total;
            $itemSold->costumer_name = $request->costumerName;
            $itemSold->receipt_number = $request->receiptNumber;
            $itemSold->cashier = $loginCashier->name;
            $itemSold->save();
            $log = new Log;
            $log->action = 'Cashier: '.session('cashier')->name.' IssueReceipt: '.$request->receiptNumber.' TO '.$request->costumerName;
            $log->save();

            
        } catch (\Throwable $th) {
            //throw $th;
            // return \Response::json($th);
            return \Response::json('Something Went Wrong', 505);
        }

        try {
              
           $oldStock = Inventory::where('id',$request->id)->first();
        //    return \Response::json($oldStock->stock);
           Inventory::where('id',$request->id)->decrement('stock',$request->quantity);
            //    ->update(['stock'=>($oldStock->stock)-($request->quantity)]);
            return \Response::json('Success');
        } catch (\Throwable $th) {
            // return \Response::json($th);
            return \Response::json('Something Went Wrong', 505);
        }
       
    }


public function search(Request $request)
{
    $searchItem = Inventory::where('name','LIKE', '%' . $request->name . '%')->orderBy('name','asc')->get();
    return \Response::json($searchItem);
}


public function login()
{
    return view('cashier.login');
}

public function checkCashier(Request $request)
{
    $cashier = CashierAccount::where('cashier_id',$request->cashierID)->first();
    if($cashier)
    {
        session(['cashier'=>$cashier]);
        $log = new Log;
        $log->action = 'cashierAccount.'.$cashier->name.'.loggedIN';
        $log->save();
       return redirect()->route('pos');
    }
    else
    {
       return redirect()->route('cashierLogin')->with('msg','Invalid cashier ID');
    }
}

}

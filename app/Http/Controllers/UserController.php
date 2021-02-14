<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItemSold;
use App\Models\CashierAccount;
use Auth;
use App\Models\Inventory;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Log;


class UserController extends Controller
{
    //

public function checkUser(Request $request)
{
   $username=$request->username;
   $pwd = $request->pass;
$loginUser = User::where('username',$username)->first();
if($loginUser)
{
    if(Hash::check($pwd,$loginUser->password))
    {
        session(['User' => $loginUser]);
        try {
            $log = new Log;
            $log->action = $loginUser->name.'.loggedIN';
            $log->save();
        } catch (\Throwable $th) {
            
        }
       
        if($loginUser->role=='admin')
        {
        return redirect()->route('dashboard');
        }
        else
        {
            return redirect()->route('inventory');
        }
    }
    else
    {
        return redirect('/')->with('error','Username or Password is incorrect');
    }
}
else{
    return redirect('/')->with('error','Username or Password is incorrect');
}
   
}


public function dashboard(Request $request)
{
    $endMonth = Carbon::now()->endOfMonth()->toDateTimeString();
    $startOfMonth = Carbon::now()->startOfMonth()->toDateTimeString();
    $yesterday = Carbon::now()->subDays(1)->toDateString();
    $lastMonth = Carbon::now()->subMonth(1);
    $lastMonthSales = ItemSold::whereBetween('created_at',[$lastMonth->startOfMonth()->toDateTimeString(),$lastMonth->endOfMonth()->toDateTimeString()])->sum('total');
    $yesterdaySales = ItemSold::whereBetween('created_at',[$yesterday." 00:00:00",$yesterday." 23:59:59"])->sum('total');
    $thisMonthSales = ItemSold::whereBetween('created_at', [$startOfMonth,$endMonth])->sum('total');
    
    $todayFrom = Carbon::now()->toDateString()." "."00:00:00";
   
    $todayTo = Carbon::now()->toDateString()." "."23:59:59";
    $itemCount = Inventory::all()->sum('stock');
    $itemSoldCount = ItemSold::whereBetween('created_at',[$todayFrom,$todayTo])->sum('quantity');
    $itemSold = ItemSold::whereBetween('created_at',[$todayFrom,$todayTo])->latest('created_at')->simplePaginate(10);
    if($request->filled('receipt'))
    {
        $itemSold = ItemSold::where('receipt_number',$request->receipt)->latest('created_at')->simplePaginate(10);
        $itemSold->appends(['receipt'=>$request->receipt]);
    }
    elseif($request->filled('customer'))
    {
        $itemSold = ItemSold::where('costumer_name','LIKE', '%' . $request->customer . '%')->latest('created_at')->simplePaginate(10);
        $itemSold->appends(['customer'=>$request->customer]);
    }
    $dailySales = ItemSold::whereBetween('created_at',[$todayFrom,$todayTo])->sum('total');
    $outOfStockCount = Inventory::where('stock',0)->count();
    $outOfStockItem = Inventory::where('stock',0)->get();
    $loginUser = session('User');
   
    if(is_null($loginUser))
    {
        return redirect('/')->with('error','Please Login');
    }
    if($loginUser->role == 'inventory')
    {
        return redirect()->route('inventory');
    }
    
    return view('dashboard')->with('itemCount',$itemCount)
                            ->with('dailySales',$dailySales)
                            ->with('itemSoldCount',$itemSoldCount)
                            ->with('itemSold',$itemSold)
                            ->with('outOfStockCount',$outOfStockCount)
                            ->with('thisMonthSales',$thisMonthSales)
                            ->with('outOfStockItem', $outOfStockItem)
                            ->with('yesterdaySales',$yesterdaySales)
                            ->with('lastMonthSales',$lastMonthSales);
}   

public function inventory(Request $request)
{
    $inventory = Inventory::orderBy('name','asc')->simplePaginate(10);
    $search ='';

    if($request->exists('search')&&$request->search!='')
    {
        $inventory = Inventory::where('name','LIKE', '%' . $request->search . '%')->orderBy('name','asc')->simplePaginate(10);
        $search=$request->search;
        $inventory->appends(['search' => $search]);
        
    }

    
    
    $loginUser = session('User');
  
    if(is_null($loginUser))
    {
        return redirect('/')->with('error','Please Login');
    }
    
    return view('inventory')->with('inventory',$inventory)
                            ->with('search',$search);
}




// FUNCTIONS
public function additem(Request $request)
{
    $loginUser = session('User');

    $request->validate([
        'name' => 'required',
        'supply_price' => 'numeric|required',
        'price' => 'numeric|required',
        'stock' => 'numeric|required',
    ]);




    try {
        $item = new Inventory;
        $item->name = $request->name;
        $item->supply_price = $request->supply_price;
        $item->price = $request->price;
        $item->stock = $request->stock;
     
        $item->save();

        $log = new Log;
        $log->action = $loginUser->name.'.addedItem:'.$request->name;
        $log->save();

        return redirect()->back()->with('msg','Successfully added');
    } catch (\Throwable $th) {
        //throw $th;
    }
  
}

public function updateitem(Request $request)
{
    // dd($request->id);
    $loginUser = session('User');
    $request->validate([
        'iname' => 'required',
        'iprice' => 'numeric|required',
        'istock' => 'numeric|required',
    ]);

    try {
       $item = Inventory::where('id',$request->id)->first();
        $log = new Log;
        $log->action = $loginUser->name.'.updatedItem:From '.$item->name.":".$item->price.":".$item->stock.' TO '.$request->iname.":".$request->iprice.":".$request->istock;
        $log->save();
        $item->update([
            'name'=>$request->iname,
            'price'=>$request->iprice,
            'stock'=>$request->istock
        ]);
        return redirect()->back()->with('msg','Successfully updated');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error','Something Went Wrong '.$th->getMessage());
    }
    
}

public function removeitem(Request $request)
{
    $loginUser = session('User');
    $res = Inventory::where('id',$request->remId)->first();
    
    if($res)
    {
        $log = new Log;
        $log->action = $loginUser->name.'.removeItem:'.$res->name;
        $log->save();
        $res->delete();
        return redirect()->back()->with('msg','Successfully Deleted');
    }
    else{
        return redirect()->back();
    }
}


public function accounts()
{
    $loginUser = session('User');
    if(is_null($loginUser))
    {
        return redirect('/')->with('error','Please Login');
    }
    $accounts = User::all();
    $cashiers = CashierAccount::all();
    if($loginUser->role == 'inventory')
    {
        return redirect()->route('inventory');
    }
    
    return view('accounts')->with('accounts',$accounts)
                            ->with('cashiers',$cashiers);
}


public function addAdmin(Request $request)
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
        
    
    $admin = new User;
    $admin->name = $request->adminName;
    $admin->username = $request->adminUsername;
    $admin->password= Hash::make($request->adminPassword);
    $admin->role = $request->role;
    $admin->save();

    if(!$admin)
    {
        return redirect('/accounts')->with('error','Not Success');
    }
    $log = new Log;
    $log->action = $loginUser->name.'.addedAccount:'.$request->adminName.' as '.$request->role;
    $log->save();
    
    
    return redirect()->back()->with('msg','Successfully Added');
}

public function addCashier(Request $request)
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
        

    $cashier = new CashierAccount;
    $cashier->cashier_id = $request->cashierId;
    $cashier->name = $request->cashierName;

    $cashier->save();

    if(!$cashier)
    {
        return redirect('/accounts')->with('error','Not Success');
    }
    $log = new Log;
    $log->action = $loginUser->name.'.addedAccount:'.$request->cashierName.' as Cashier.ID'.$request->cashierId;
    $log->save();
    
    return redirect()->back()->with('msg','Successfully Added Cashier');


}

public function updateCashier(Request $request)
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
        
    
    $cashier = CashierAccount::where('id',$request->id)->first();
    
    $log = new Log;
    $log->action = $loginUser->name.'.updatedCashier:From '.$cashier->cashier_id.':'.$cashier->name.' To '.$request->cashierId.':'.$request->cashierName;
    $log->save();
    $cashier->cashier_id = $request->cashierId;
    $cashier->name = $request->cashierName;
    $cashier->save();

    if(!$cashier)
    {
        return redirect('/accounts')->with('error','Not Success');
    }
   
    return redirect()->back()->with('msg','Successfully Updated Cashier');
    


}

public function removeCashier(Request $request)
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
    

   $cashier = CashierAccount::where('id',$request->id)->first();
   $log = new Log;
    $log->action = $loginUser->name.'.removedAccount:'.$cashier->name.' with CashierID'.$cashier->cashier_id;
    $log->save();
   $cashier->delete();

 if(!$cashier)
    {
       
        return redirect('/accounts')->with('error','Not Success');
    }
    if(session('cashier'))
    {
        session()->forget('cashier');
    }
    return redirect()->back()->with('msg','Successfully Deleted Cashier');

}

public function updateAdmin(Request $request)
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
        

    $admin = User::where('id',$request->adminID)->first();
   

    if(!is_null($request->adminPassword))
    {
        $admin->password = Hash::make($request->adminPassword);
    }
    $log = new Log;
    $log->action = $loginUser->name.'.updatedInventoryAccount:From'.$admin->username.':'.$admin->name.' To '.$request->adminUsername.':'.$request->adminName;
    $log->save();
    $admin->username = $request->adminUsername;
    $admin->name = $request->adminName;
    $admin->save();
    if(!$admin)
    {
        return redirect('/accounts')->with('error','Not Success');
    }
    return redirect()->back()->with('msg','Successfully Updated Inventory Account');
}

public function logs()
{
    $logs = Log::orderBy('created_at','desc')->simplePaginate(100);
    return view('logs')->with('logs',$logs);
}

public function removeAdmin(Request $request)
{
    $loginUser = session('User');
    if(Hash::check($request->password,$loginUser->password))
    {
        try {
            $admin = User::where('id',$request->id)->first();
            $log = new Log;
            $log->action = $loginUser->name.'.removedAccount:'.$admin->name.':'.$admin->username.' role:'.$admin->role;
            $log->save();
            $admin->delete();
            return response('Success',200);
        } catch (\Throwable $th) {
            return response('Error Deleting',500);
        }
    }
    else{
        return response('Wrong Password',500);
    }
    

    // try {
    //     $admin = User::where('id',$request->id)->first();
    //     return response(200);
    // } catch (\Throwable $th) {
    //     //throw $th;
    // }
    

}

public function checkUpdate()
{   
    exec('git fetch');
    exec(' git diff origin/master:version.json master:version.json', $output,$success);
    
    if($success!=1)
    {
        
        $resMessage='';
        $newVersion='';
        $currentVersion='';
        $hasNewVersion = false;
        if(count($output)>0)
        {
            $newVersion = json_decode('{'.substr($output[6],strpos($output[6],'"')).'}');
            $currentVersion = json_decode('{'.substr($output[7],strpos($output[7],'"')).'}');
            $hasNewVersion = true;
            return response()->json([
                'hasNewVersion'=>$hasNewVersion,
                'currentVersion'=>$currentVersion->version,
                'newVersion'=>$newVersion->version
            ],200);
        }
        else
        {
            $hasNewVersion = false;
            $resMessage = 'No Update Available';
            return response()->json([
                'message'=>$resMessage,
                'hasNewVersion'=>$hasNewVersion
            ]);
        }
       
    }
    else
    {
        return response('error',400);
    }
}

public function updateVersion()
{
   
    exec('git pull https://github.com/RGIIS/InventorySystem.git',$output,$success);
    if($output>0)
        {

            return response('Successfully Updated',200);
           
        }
    else{
        return response('Error',400);
    }
        
}

}

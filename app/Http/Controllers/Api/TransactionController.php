<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {


        $todayXml = Cache::get("todayXml", null);
        if (!$todayXml) {
            $todayXml = file_get_contents("https://www.tcmb.gov.tr/kurlar/today.xml");
            Cache::put("todayXml", $todayXml, 600); // 10 Minutes
        }

        $xml = simplexml_load_string($todayXml, "SimpleXMLElement", LIBXML_NOCDATA);
        $currencies = json_decode(json_encode($xml), true)['Currency'];

        $try = 1;
        $usd = $currencies[0]['BanknoteBuying'];
        $eur = $currencies[3]['BanknoteBuying'];

        $whereArray = [date('Y-m-d 00:00', strtotime($request->fromDate)), date('Y-m-d 23:59', strtotime($request->toDate))];

        $total = 0;
        $totalsPerCurrency = Transaction::selectRaw("sum(amount) as amount, lower(currency) as currency")->whereBetween('created_at', $whereArray)->groupBy('currency')->get();

        foreach ($totalsPerCurrency as $totals) {
            $total += $totals->amount * ${$totals->currency};
        }

        $transactions['total'] = ['try' => number_format($total, 2, '.', '')];
        $transactions['data'] = Transaction::select(['id', 'currency', 'created_at', 'amount', 'description', 'category_id'])->whereBetween('created_at', $whereArray)->with('category:id,name')->orderByDesc('created_at')->get();
        return new TransactionCollection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TransactionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TransactionRequest $request)
    {
        $requestData = $request->all();
        $category = Category::select('cash_flow_direction')->where('id', $request->category_id)->first();
        $requestData['amount'] = $request->amount * $category->cash_flow_direction;

        Transaction::create($requestData);
        return sendResponse('ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $requestData = $request->all();
        $category = Category::select('cash_flow_direction')->where('id', $request->category_id)->first();
        $requestData['amount'] = $request->amount * $category->cash_flow_direction;

        $transaction->update($requestData);
        return sendResponse('ok');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return sendResponse('ok');
    }
}

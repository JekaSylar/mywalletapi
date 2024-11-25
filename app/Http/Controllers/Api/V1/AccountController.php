<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Accounts\StoreAccountRequest;
use App\Http\Requests\Api\V1\Accounts\UpdateAccountRequest;
use App\Http\Resources\Api\V1\Accounts\AccountResource;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $accounts = $user->accounts()->get();

        return  AccountResource::collection($accounts);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
       $account =  Account::create($request->all());

       return new AccountResource($account);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account->update($request->all());

        return new AccountResource($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        if ($account->user->id !== auth()->id()) {
            return response()->json('Немає доступу', 403);
        }

        $account->delete();

        return response()->noContent();
    }
}

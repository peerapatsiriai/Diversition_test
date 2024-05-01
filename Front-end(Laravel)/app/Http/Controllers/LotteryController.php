<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Database\Eloquent\Lottery;



class LotteryController extends Controller
{
    public function getLottery()
    {
        $lotteries = Lottery::all();
        return response()->json(['lotteries' => $lotteries], 200);
    }

    public function randomize()
    {
        try {
            
            $prize1 = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Random number between 000 and 999
            $prize2_1 = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Random number between 000 and 999
            $prize2_2 = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Random number between 000 and 999
            $prize2_3 = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            
            $randomizedData = [
                'prize1' => $prize1,
                'prize2_1' => $prize2_1,
                'prize2_2' => $prize2_2,
                'prize2_3' => $prize2_3
            ];

            $lottery = Lottery::create($randomizedData);

            return response()->json($randomizedData, 200);
        } catch (Throwable $th) {
            Log::error($e->getMessage());
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortnerFV;
use App\Models\Shortner;
use Illuminate\Http\Request;


class HomeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
    }
    public function view($slug)
    {
        $exists = Shortner::where('short_url', $slug)->firstOrFail();

        return redirect()->away($exists->long_url);

    }
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'long_url' => 'required|url|unique:shortners,long_url',
        ]);

        if ($validator->fails()) {
            $exists = Shortner::where('long_url', $request->long_url)->first();
            $exists_url = ($exists)?url($exists->short_url):null;
            $message = "";
            foreach($validator->errors()->all() as $list){
                $message .= $list;
                return response()->json([
                    'status' => false,
                    'message' => $message,
                    'step' => 'Invalid',
                    'url' => $exists_url
                ]);
            }
            // return $validator->errors();
         }
        $data  = new Shortner();
        $data->fill($request->all());

        // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $shotlink = substr(str_shuffle($permitted_chars), 0, 6);
        $shotlink = substr(sha1(time()), 0, 6);
        $data->short_url = $shotlink;

        $is_safe = $this->chekSave($data->long_url);
        if($is_safe['status']){
            try{
                $data->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Data Updated Successfully',
                    'step' => 'Done',
                    'url' => url($data->short_url)
                ]);

            }catch(\Illuminate\Database\QueryException $ex){
                // dd($ex);
                return response()->json([
                    'status' => false,
                    'message' => 'Something this wrong.',
                    'step' => 'query',
                    'url' => null
                ]);
            }
        }

        return response()->json([
            'status' => $is_safe['status'],
            'message' => $is_safe['message']. ' !! Your Enter link is not safe for browsing.',
            'step' => 'unsafe',
            'url' => null
        ]);

    }

    private function chekSave($url)
    {
        $key = config('safebrowsing.api_key');
        $clientId = config('safebrowsing.client_id');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key='.$key,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'    {
            "client": {
            "clientId":      "'.$clientId.'",
            "clientVersion": "1.5.2"
            },
            "threatInfo": {
            "threatTypes":      ["MALWARE", "SOCIAL_ENGINEERING","POTENTIALLY_HARMFUL_APPLICATION"],
            "platformTypes":    ["ANY_PLATFORM"],
            "threatEntryTypes": ["URL"],
            "threatEntries": [
                {"url": "'.$url.'"}
            ]
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
        $status = true;
        $message = "No Threat Found";
        if(isset($response['matches'])){
            $status = false;
                $message = "";
                foreach($response['matches'] as $key => $match){
                    $message .= ($key > 0)? ' ,': ''. $match['threatType'];
                }
        }
        return [
            'status' => $status,
            'message' => $message,
        ];

        // "https://testsafebrowsing.appspot.com/s/malware.html" example Unsafe url
    }



}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function showBlog(Request $req)
    {
    $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8005/api/blog/show");
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $header = [
            //'Content-Type: application/json',
            'Authorization: Bearer '. \Session::get('bearer'),
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $server_output = curl_exec($ch);

        curl_close($ch);
        
        $response = json_decode($server_output, true);
        $data = $response["data"];

        return view('pages.dashboard', compact('data'));

    }
    
    public function createBlog(Request $req)
    {
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:8005/api/blog/add");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $header = [
            //'Content-Type: application/json',
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $server_output = curl_exec($ch);

        curl_close($ch);
        
        $response = json_decode($server_output, true);
        //dd($response["token"]);
        

        if($response["status"] ==401)
        {
            return \redirect()->back()->with('error', 'username/password salah');
        }
        else {
                session([
                    'bearer' => $response["token"],
                    'username' => $response["user"][0]["username"]
                    ]);



            //session('bearer', $response["token"]);
            //session('username', $response["user"][0]["username"]);

            $bearer = $response ["token"];
            $username = $response ["user"][0]["username"];
            
            return redirect()->to('/dashboard');

            //echo "Login Berhasil";
            //return view('pages.dashboard', compact ('bearer', 'username'));
        }


        //dd($server_output);

    }

    

}
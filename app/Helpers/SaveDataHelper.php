<?php 

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SaveDataHelper{

    public function __constrcut(){

    }

    public function saveData($file){
        $handle = fopen($file->getPathname(), "r");
        $response = [];
        try{   
            if ($handle) {
                $header = fgetcsv($handle);
                $data = [];
                $pattern = '/^[a-zA-Z0-9]{3,20}$/';
                while (($row = fgetcsv($handle)) !== FALSE) {                   
                        $data[] = [
                            'id'=>$row[0],
                            'username'=>$row[1],
                            'name'=>$row[1],
                            'email'=>$row[2],
                            'password'=>$row[3],
                        ];
                }
                fclose($handle);
            }
            DB::beginTransaction();
            $validator = Validator::make($data,[
                '*'=>[
                    'id'=>'unique:users,id',
                    'username'=>'required|string|alpha_num|min:3|max:20|',
                    'email'=>'required|email|unique:users,email',
                    'password'=>'required'
                ]
            ]);
            if(!$validator->fails()){
                // eloquent will do hashing
                User::insertOrIgnore($data);
            }else{
                $response = $validator->validate();
            }
        }catch(Throwable $th){
            $response = ['status'=>'error','message'=>$th->getMessage()];
        }
        return $response;
    }
}
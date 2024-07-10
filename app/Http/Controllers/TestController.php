<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private $apiUrl = "https://test.drogueriahofmann.cl";

    public function home() {
        return view('listado')->with([
            'users' => $this->getUsers()
        ]);
    }

    private function getUsers() {
        $peticion = \Http::get($this->apiUrl."/usuarios/listTableUsers");

        if(!$peticion->successful())
            return "Error al consultar API ListTableUsers";

        $users = $peticion->json();
        foreach($users as &$u) {
            $u['amount_formatted'] = number_format($u['amount'],0,"", ".");
            $u['date_formatted'] = date('d-m-Y',strtotime($u['date']));
        }
        return $users;
    }

    public function getUser($id = null) {
        if(!$id)
            return response()->json(['mensaje'=>'Debe enviar ID'], 500);
        
        $user = array_filter($this->getUsers(), function($u) use ($id) {
            return $u['id'] == $id;
        });

        $user = reset($user);

        if(!$user)
            return response()->json(['mensaje'=>'Usuario no encontrado'], 404);
        
        $usersCode = $this->getUsersCode();

        return view('modals/user')->with(['user'=>$user,'usersCode'=>$usersCode]);
    }

    public function getUsersCode() {
        $peticion = \Http::get($this->apiUrl."/usuarios/GetUsers");

        if(!$peticion->successful())
            return "Error al consultar API GetUsers";

        $users = $peticion->json();        
        return $users;
    }

    public function updateUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'Code' => 'required',
            'Amount' => 'required',
            'Id' => 'required',
            'Date' => 'required',
            'Github'=> 'required'
        ]);

        if($validator->fails())
            return response()->json(['errores'=>$validator->errors()],422);


        $userCode = array_filter($this->getUsersCode(), function($u) use ($request) {
            return $u['code'] == $request->input("Code");
        });

        if(!$userCode)
            return response()->json(['mensaje'=>'No se encontro usuario code']);

        $data = $validator->validated();

        $peticion = \Http::post($this->apiUrl."/usuarios/SendUser",$data);
        if($peticion->successful())
            return response()->json(['estado'=>200,'mensaje'=>'Registro actualizado correctamente']);
        else
            return response()->json(['estado'=> 500,'mensaje'=>'Ocurrio un error al actualizar usuario','error'=>$peticion->json()]);
    }
}

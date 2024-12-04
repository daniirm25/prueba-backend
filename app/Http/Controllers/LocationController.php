<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Classes\FormatResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LocationController extends FormatResponse
{

    public function createLocation(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'description' => 'required'
        ];

        $messages = [
            'name.required' => 'El nombre de la tarea es requerido.',
            'code.required' => 'El codigo es requerida.',
            'description.required' => 'La descripcion es requerida.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->errors()->getMessages()){
            return $this->toJson($this->estadoOperacionFallida($validator->getMessageBag()->all()));
        }else{
            try {
                DB::beginTransaction();
                $user_id = auth()->user()->id;
                $task = Location::where('name', $request->name)->orWhere('code', $request->code)->first();
                if($task){
                    return response($this->toJson($this->estadoOperacionFallida('La tarea ya existe')), 400);
                }else{
                    $task = new Location();
                    $task->name = $request->name;
                    $task->code = $request->code;
                    $task->description = $request->description;
                    $task->user_id = $user_id;
                    $task->save();
                    DB::commit();
                    return $this->toJson($this->estadoExitoso('Tarea creada exitosamente'), $task);
                }
            } catch (Throwable $th) {
                DB::rollback();
                Log::error($th->getMessage());
                return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
            }
        }
    }

    public function locations()
    {
        try {
            $tasks = Task::get();
            if(!$tasks->isEmpty()){
                return $this->toJson($this->estadoExitoso('Exitoso'),$tasks);
            }
            return response($this->toJson($this->estadoNoEncontrado('No se encuentran tareas registradas')), 400);
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
        }
    }

    


}

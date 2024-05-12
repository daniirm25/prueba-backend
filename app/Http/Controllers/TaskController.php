<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Classes\FormatResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends FormatResponse
{

    public function createTask(Request $request)
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
                $task = Task::where('name', $request->name)->orWhere('code', $request->code)->first();
                if($task){
                    return response($this->toJson($this->estadoOperacionFallida('La tarea ya existe')), 400);
                }else{
                    $task = new Task();
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

    public function updateTask(Request $request)
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
                $task = Task::where('name', $request->name)->orWhere('code', $request->code)->where('id', '!=', $request->id)->first();
                if($task){
                    return response($this->toJson($this->estadoOperacionFallida('La tarea ya existe')), 400);
                }else{
                    $task = Task::find($request->id);
                    $task->name = $request->name;
                    $task->code = $request->code;
                    $task->description = $request->description;
                    $task->user_id = $user_id;
                    $task->save();
                    DB::commit();
                    return $this->toJson($this->estadoExitoso('Tarea actualizada exitosamente'), $task);
                }
            } catch (Throwable $th) {
                DB::rollback();
                Log::error($th->getMessage());
                return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
            }
        }
    }

    public function showTask($id)
    {
        try {
            $task = Task::find($id);
            if($task){
                return $this->toJson($this->estadoExitoso('Exitoso'),$task);
            }else{
                return response($this->toJson($this->estadoExitoso('No se encontro la tarea')), 400);
            }
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
        }
    }
    
    public function getTasks()
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

    public function deleteTask($id)
    {
        try {
            $task = Task::find($id);
            if($task){
                $task->delete();
                return $this->toJson($this->estadoExitoso('Tarea eliminada exitosamente'));
            }else{
                return response($this->toJson($this->estadoExitoso('No se encontro la tarea')), 400);
            }
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return $this->toJson($this->estadoOperacionFallida($th->getMessage() . ' ' . $th->getLine()));
        }
    }
    


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Arr;
use Validator;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tasks = Task::all();

        if($tasks->count() == 0) {
            return response()->json(['message'=>'data not exists'],'402');
        }

        return response()->json($tasks,'200');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
        Arr::except($post, ['token']);

        $rules = [
            'name' => 'required|max:255',
            'description' => 'required|min:5',
            'status' => 'required'
        ];

        $validator = Validator::make($post, $rules);

        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()],'400');
        }

        try {
            $task = Task::create($post);
        } catch (\Exeption $e) {
            return response()->json(['success'=>false, 'error'=>$e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'create successfuly',
            'data' => $task
        ],'201');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $task = Task::find($id);

        if (is_null($task)) {
            return response()->json([
                'success'=>false,
                'error' => 'Data not found or not exists'
            ],'404');
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ],'200');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $task = Task::find($id);

        if (is_null($task)) {
            return response()->json([
                'success'=>false,
                'error' => 'Data not found or not exists'
            ],'404');
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ],'200');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $post = $request->all();
        Arr::except($post, ['token']);
        
        $rules = [
            'name' => 'required|max:255',
            'description' => 'required|min:5',
            'status' => 'required'
        ];
        
        $validator = Validator::make($post, $rules);
        
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()],'400');
        }
        
        $task = Task::find($id);

        if (is_null($task)) {
            return response()->json([
                'success'=>false,
                'error' => 'Data not found or not exists'
            ],'404');
        }

        try {
            $result = $task->update($post);
        } catch (\Exeption $e) {
            return response()->json(['success'=>false, 'error'=>$e->getMessage()]);
        }

        return response()->json(['success'=>true, 'message'=>'Update successfuly'],'200');
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $destroy = Task::destroy($id);
        } catch (\Exeption $e) {
            return response()->json(['success'=>false, 'error'=>$e->getMessage()]);
        }

        if ($destroy == 0) {
            return response()->json([
                'success'=>false,
                'error' => 'Deleted error, data not found or not exists'
            ],'404');
        }

        return response()->json(['success'=>true, 'message'=>'Deleted successfuly'],'200');
    }
}

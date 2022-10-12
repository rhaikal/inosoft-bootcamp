<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class TaskController extends Controller {
	private TaskService $taskService;
	public function __construct() {
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title'=>'required|string|min:3',
			'description'=>'required|string'
		]);

		$data = [
			'title'=>$request->post('title'),
			'description'=>$request->post('description')
		];

		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	public function updateTask(Request $request, string $id)
	{
		$request->validate([
			'title'=>'string',
			'description'=>'string',
			'assigned'=>'string',
			'subtasks'=>'array',
		]);

		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$existTask = $this->taskService->getById($id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$id." tidak ada"
			], 401);
		}

		$this->taskService->updateTask($existTask, $formData);

		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	// TODO: deleteTask()
	public function deleteTask(string $id)
	{
		$existTask = $this->taskService->getById($id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$id." tidak ada"
			], 401);
		}

		$this->taskService->deleteTask($id);

		return response()->json([
			'message'=> 'Success delete task '.$id
		]);
	}

	// TODO: assignTask()
	public function assignTask(Request $request, string $id)
	{
		$request->validate([
			'assigned'=>'required|string'
		]);

		$assigned = $request->post('assigned');
		$existTask = $this->taskService->getById($id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$id." tidak ada"
			], 401);
		}

		$this->taskService->assignTask($existTask, $assigned);

		$task = $this->taskService->getById($id);

		return response()->json($task);
	}

	// TODO: unassignTask()
	public function unassignTask(string $id)
	{
		$existTask = $this->taskService->getById($id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$id." tidak ada"
			], 401);
		}

		$this->taskService->unassignTask($existTask);

		$task = $this->taskService->getById($id);

		return response()->json($task);
	}

	// TODO: createSubtask()
	public function createSubtask(Request $request, string $id)
	{
		$request->validate([
			'title'=>'required|string',
			'description'=>'required|string'
		]);

		$data = [
			'title' => $request->post('title'),
			'description' => $request->post('description'),
		];
		
		$existTask = $this->taskService->getById($id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$id." tidak ada"
			], 401);
		}

		$this->taskService->createSubTask($existTask, $data);

		$task = $this->taskService->getById($id);

		return response()->json($task);
	}

	// TODO deleteSubTask()
	public function deleteSubtask(string $id, string $subtaskId)
	{
		$existTask = $this->taskService->getById($id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$id." tidak ada"
			], 401);
		}

		$this->taskService->deleteSubTask($existTask, $subtaskId);

		$task = $this->taskService->getById($id);

		return response()->json($task);
	}

}
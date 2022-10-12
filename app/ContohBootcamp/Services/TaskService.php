<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService {
	private TaskRepository $taskRepository;

	public function __construct() {
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	/**
	 * NOTE: untuk menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: untuk mengambil data task
	 */
	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if(isset($formData['title']))
		{
			$editTask['title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save($editTask);
		return $id;
	}

	/**
	 * NOTE: untuk menghapus task
	 */
	public function deleteTask(string $taskId)
	{
		$this->taskRepository->delete($taskId);
	}

	public function assignTask(array $task, string $assigned)
	{
		$task['assigned'] = $assigned;

		$this->taskRepository->save($task);
	}
	
	public function unassignTask(array $task)
	{
		$task['assigned'] = null;

		$this->taskRepository->save($task);
	}

	public function createSubTask(array $task, array $data)
	{
		$id = $this->taskRepository->createSubTask($task, $data);
		
		return $id; 
	}

	public function deleteSubTask(array $task, string $subtaskId)
	{
		$id = $this->taskRepository->deleteSubTask($task, $subtaskId);
		
		return $id;
	}

	public function updateSubtask(string $subtaskId, array $formData)
	{
		if(isset($formData['title']))
		{
			$editSubtask['subtasks.$.title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editSubtask['subtasks.$.description'] = $formData['description'];
		}

		$id = $this->taskRepository->updateSubtask($subtaskId, $editSubtask);

		return $id;
	}
}
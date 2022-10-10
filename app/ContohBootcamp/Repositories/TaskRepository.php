<?php
namespace App\ContohBootcamp\Repositories;

use App\Helpers\MongoModel;

class TaskRepository
{
	private MongoModel $tasks;
	public function __construct()
	{
		$this->tasks = new MongoModel('tasks');
	}

	/**
	 * Untuk mengambil semua tasks
	 */
	public function getAll()
	{
		$tasks = $this->tasks->get([]);
		return $tasks;
	}

	/**
	 * Untuk mendapatkan task bedasarkan id
	 *  */
	public function getById(string $id)
	{
		$task = $this->tasks->find(['_id'=>$id]);
		return $task;
	}

	/**
	 * Untuk membuat task
	 */
	public function create(array $data)
	{
		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->tasks->save($dataSaved);
		return $id;
	}

	/**
	 * Untuk menyimpan task baik untuk membuat baru atau menyimpan dengan struktur bson secara bebas
	 *  */
	public function save(array $editedData)
	{
		$id = $this->tasks->save($editedData);
		return $id;
	}

	/**
	 * Untuk menghapus task
	 *  */
	public function delete(string $id)
	{
		$this->tasks->deleteQuery(['_id'=>$id]);
	}

	/**
	 * Untuk membuat subtask
	 */
	public function createSubTask(array $task, array $subtasks, array $data)
	{
		$subtasks[] = [
			'_id'=> (string) new \MongoDB\BSON\ObjectId(),
			'title'=> $data['title'],
			'description'=> $data['description']
		];

		$task['subtasks'] = $subtasks;

		$id = $this->tasks->save($task);
		return $id;
	}
}
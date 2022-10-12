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
	public function createSubtask(array $task, array $data)
	{
		$subtask = [
			'_id'=> (string) new \MongoDB\BSON\ObjectId(),
			'title'=> $data['title'],
			'description'=> $data['description']
		];

		$this->tasks->collection->updateOne(['_id' => $task['_id']], ['$push' => ['subtasks' => $subtask]]);

		return $task['_id'];
	}

	/**
	 * Untuk menghapus subtask
	 */
	public function deleteSubtask(array $task, string $subtaskId)
	{
		$this->tasks->collection->updateOne(['_id' => $task['_id']], ['$pull' => ['subtasks' => ['_id' => $subtaskId]]]);

		return $task['_id'];
	}

	/**
	 * Untuk mengubah subtask
	 */
	public function updateSubtask(string $subtaskId, array $formData)
	{
		$this->tasks->collection->updateOne(['subtasks._id' => $subtaskId], ['$set' => $formData]);
	}
}
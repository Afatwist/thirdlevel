<?php
// https://github.com/auraphp/Aura.SqlQuery/blob/HEAD/docs/index.md

namespace App\Classes;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
	private $pdo, $queryFactory;

	public function __construct(PDO $pdo, QueryFactory $queryFactory)
	{
		$this->pdo = $pdo;
		$this->queryFactory = $queryFactory;
	}

	/**
	 * Получить ВСЕ записи из таблицы
	 * @param string $table   имя таблицы
	 * @param null/int $limit   количество извлекаемых строк
	 * @param string $fetch_type   тип возвращаемого результата ASSOC - массив, OBJ - объект. Это окончание константы PDO::FETCH_
	 * @return mixed/false 
	 */

	public function getAll($table, $limit = null, $fetch_type = 'ASSOC')
	{
		$select = $this->queryFactory->newSelect();
		$select->cols(['*'])
			->from($table)
			->limit($limit);

		$sth = $this->pdo->prepare($select->getStatement());

		$sth->execute($select->getBindValues());

		return $sth->fetchAll(constant('PDO::FETCH_' . mb_strtoupper($fetch_type)));
	}

	/**
	 * Получить ОДНУ записи из таблицы
	 * @param string $table   имя таблицы
	 * @param string $byField   столбец в таблице, по которому ведется поиск
	 * @param string $value   искомое значение
	 * @param string $fetch_type  тип возвращаемого результата ASSOC - массив, OBJ - объект. Это окончание константы PDO::FETCH_
	 * @return mixed/false 
	 */

	public function getOne($table, $byField, $value, $fetch_type = 'ASSOC')
	{
		$select = $this->queryFactory->newSelect();

		$select->cols(['*'])
			->from($table)
			->where("$byField = :$byField")
			->bindValue($byField, $value);

		$sth = $this->pdo->prepare($select->getStatement());

		$sth->execute($select->getBindValues());

		return $sth->fetch(constant('PDO::FETCH_' . mb_strtoupper($fetch_type)));
	}
	/**
	 * Создание/Добавление записи в БД
	 * @param string $table название таблицы в БД
	 * @param array $data вносимые данные
	 * @return int id внесенной записи
	 * @return false в случае ошибки 
	 */
	public function create($table, $data)
	{
		$insert = $this->queryFactory->newInsert();

		$insert
			->into($table)
			->cols($data);

		$sth = $this->pdo->prepare($insert->getStatement());

		$sth->execute($insert->getBindValues());

		$name = $insert->getLastInsertIdName('id');
		return $this->pdo->lastInsertId($name);
	}

	/**
	 * Обновление записи в таблице
	 * @param string $table название таблицы в БД
	 * @param string $byField название столбца, по которому
	 * ищется нужная запись: 'id', 'user_id'...
	 * @param int $id id записи
	 * @param array $data обновляемые данные
	 * @return bool 
	 */
	public function update($table, $byField, $id, $data)
	{
		$update = $this->queryFactory->newUpdate();

		$update
			->table($table)
			->cols($data)
			->where("$byField = :$byField")
			->bindValue($byField, $id);

		$sth = $this->pdo->prepare($update->getStatement());

		$sth->execute($update->getBindValues());
		if ($sth->errorCode() === '00000') return true;
		return false;
	}

	/**
	 * Удаление записи из таблицы
	 * @param string $table название таблицы в БД
	 * @param string $byField название столбца, по которому
	 * ищется нужная запись: 'id', 'user_id'...
	 * @param int $id id записи
	 */
	public function delete($table, $byField, $id)
	{
		$delete = $this->queryFactory->newDelete();

		$delete
			->from($table)
			->where("$byField = :$byField")
			->bindValue($byField, $id);

		$sth = $this->pdo->prepare($delete->getStatement());

		$sth->execute($delete->getBindValues());
	}

	/** 
	 * удалить все записи из таблицы
	 * @param string $table  имя таблицы
	 * @param bool $sure для подтверждения удаления передайте значение true
	 */
	public function deleteAll($table, $sure = false)
	{
		if ($sure !== true) return false;
		$delete = $this->queryFactory->newDelete();

		$delete->from($table);

		$sth = $this->pdo->prepare($delete->getStatement());

		$sth->execute($delete->getBindValues());
	}

	/**
	 * Посчитать количество записей в таблице
	 * @param string $table название таблицы в БД
	 * @param string $byField название столбца, по которому
	 * ищется нужная запись: 'id', 'user_id'...
	 * @param int/string $value значение по которому ищутся записи
	 * @return int количество записей удовлетворяющих запросу
	 */
	public function getCount($table, $byField = null, $value = null)
	{
		$select = $this->queryFactory->newSelect();

		if ($byField == null or $value == null) {
			$select->cols(['*'])->from($table);
		} else {
			$select->cols(['*'])
				->from($table)
				->where("$byField = :$byField")
				->bindValue($byField, $value);
		}

		$sth = $this->pdo->prepare($select->getStatement());

		$sth->execute($select->getBindValues());

		return count($sth->fetchAll(PDO::FETCH_ASSOC));
	}

	/**
	 * Метод для пагинации
	 * @param string $table имя таблицы из которой берутся данные
	 * @param int $paging количество выводимых записей
	 * @param int $page номер отображаемой страницы, если значение 0,
	 * то отобразятся все записи
	 * @param string $fetch_type тип возвращаемого результата ASSOC - массив,
	 * OBJ - объект. Это окончание константы PDO::FETCH_
	 * @return mixed возвращает массив или объект, зависит от выбранного $fetch_type
	 * @return false если передано значение $page меньше нуля,
	 * или не было получено ни одной записи из БД
	 */
	public function paginator($table, $paging, $page = 1, $fetch_type = 'ASSOC')
	{
		if ($page < 0) return false;
		$select = $this->queryFactory->newSelect();

		$select->cols(['*'])
			->from($table)
			->setPaging($paging)
			->page($page);

		$sth = $this->pdo->prepare($select->getStatement());

		$sth->execute($select->getBindValues());

		$result = $sth->fetchAll(constant('PDO::FETCH_' . mb_strtoupper($fetch_type)));
		if (count($result) == 0) return false;
		return $result;
	}
}

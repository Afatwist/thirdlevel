<?php

namespace App\Classes;

class Status
{
	const NORMAL = 0;
	const ARCHIVED = 1;
	const BANNED = 2;
	const LOCKED = 3;
	const PENDING_REVIEW = 4;
	const SUSPENDED = 5;

	public static function getStatusList()
	{
		return [
			[
				'id'     =>   self::NORMAL,
				'title'  =>   'Нормальный',
				'style'  =>   'success'
			],
			[
				'id'     =>   self::ARCHIVED,
				'title'  =>   'Архивный',
				'style'  =>   'secondary'
			],
			[
				'id'     =>   self::BANNED,
				'title'  =>   'Забанен',
				'style'  =>   'danger'
			],
			[
				'id'     =>   self::LOCKED,
				'title'  =>   'Заблокирован', //read only
				'style'  =>   'warning'
			],
			[
				'id'     =>   self::PENDING_REVIEW,
				'title'  =>   'Ожидает рассмотрения',
				'style'  =>   'info'
			],
			[
				'id'     =>   self::SUSPENDED,
				'title'  =>   'Приостановлен', //read only
				'style'  =>   'secondary'
			],
		];
	}

	public static function getStatus($key)
	{
		foreach (self::getStatusList() as $status) {
			if ($status['id'] == $key) {
				return $status['title'];
			}
		}
	}

	public static function getStyle($key)
	{
		foreach (self::getStatusList() as $status) {
			if ($status['id'] == $key) {
				return $status['style'];
			}
		}
	}
}

<?php

namespace App\Classes;

class Activity
{
	const ONLINE = 1;
	const OFFLINE = 2;
	const AWAY = 3;
	const NOT_DISTURB = 4;

	public static function getActivityList()
	{
		return [
			[
				'id'     =>   self::ONLINE,
				'title'  =>   'Онлайн',
				'style'  =>   'success'
			],
			[
				'id'     =>   self::OFFLINE,
				'title'  =>   'Оффлайн',
				'style'  =>   'secondary'
			],
			[
				'id'     =>   self::AWAY,
				'title'  =>   'Отошел',
				'style'  =>   'warning'
			],
			[
				'id'     =>   self::NOT_DISTURB,
				'title'  =>   'Не беспокоить',
				'style'  =>   'danger'
			]
		];
	}

	public static function getActivity($key)
	{
		foreach (self::getActivityList() as $activity) {
			if ($activity['id'] == $key) {
				return $activity['title'];
			}
		}
	}

	public static function getStyle($key)
	{
		foreach (self::getActivityList() as $activity) {
			if ($activity['id'] == $key) {
				return $activity['style'];
			}
		}
	}
}

<?php

namespace App\Classes;

use Intervention\Image\ImageManagerStatic as Image;

class ImageManager
{

	/**
	 * проверяет была ли загружена картинка
	 * @param array $image это массив $_FILES[*]
	 * @return bool true/false
	 */
	private function isImageUpload($image)
	{
		if (isset($image['error']) and $image['error'] === 0) return true;

		return false;
	}

	/**
	 * подготавливает уникальное имя для загружаемого файла
	 * @param string    $path путь к папке, в которую будет загружен файл  
	 * @param string    $uploadImageName  название загруженного файла $_FILES[*]['name']
	 * @return string уникальное имя файла  
	 */
	private function uniqueImageName($path, $uploadImageName)
	{
		$ext = pathinfo($uploadImageName);
		$ext = $ext['extension'];
		do {
			$name = uniqid();
			$name = $name . '.' . $ext;
		} while (file_exists($path . $name));
		return $name;
	}

	/**
	 * Загрузка файла на сервер
	 * @param string    $path путь к папке, в которую будет загружен файл  
	 * @param array    $image массив $_FILES[*]
	 * @return string/false уникальное имя файла 
	 */
	private function uploadImage($path, $image)
	{
		$imageName = $this->uniqueImageName($path, $image['name']);
		$image = Image::make($image['tmp_name']);
		$image->save($path . $imageName);
		return $imageName;
	}

	/**
	 * Удаление файла с сервера
	 * @param string    $path путь к папке с изображением
	 * @param string    $imageName название файла
	 */
	private function imageDelete($path, $imageName)
	{
		if (file_exists($path . $imageName)) {
			unlink($path . $imageName);
		}
	}


	// ПУБЛИЧНЫЕ МЕТОДЫ ДЛЯ ОБРАБОТКИ АВАТАРОВ

	/**
	 * Сохраняет новый аватар пользователя, возвращает его название и удаляет старый
	 * аватар, если он есть.
	 * @param array $avatar массив $_FILES['avatar']
	 * @param string $currentAvatar название старой аватарки из БД
	 * @return string название новой аватарки
	 */
	public function avatarUpdate($avatar, $currentAvatar = null)
	{
		if (!$this->isImageUpload($avatar)) {
			if (!is_null($currentAvatar)) return $currentAvatar;
			return null;
		}

		$avatar_name = $this->uploadImage(AVATARS, $avatar);
		if ($currentAvatar !== null) {
			$this->imageDelete(AVATARS, $currentAvatar);
		}
		return $avatar_name;
	}

	/**
	 * Сохраняет фейковый аватар из галереи для автоматически генерируемого пользователя
	 * @param string $fakerAvatarName путь к файлу аватара
	 * @return string avatarName возвращает уникальное имя аватара для записи в БД
	 */
	public function saveFakerAvatar($fakerAvatarName)
	{
		$imageName = $this->uniqueImageName(AVATARS, $fakerAvatarName);
		$image = Image::make($fakerAvatarName);
		$image->save(AVATARS . $imageName);
		return $imageName;
	}

	public function deleteAvatar($userAvatar)
	{
		if (!is_null($userAvatar)) {
			$this->imageDelete(AVATARS, $userAvatar);
		}
	}

	/**
	 * Проверяет наличие аватара, если нет, то выводит заглушку
	 * @param string $userAvatar название аватара из БД
	 * @return string ссылка на аватар или на заглушку
	 */
	public static function getAvatar($userAvatar = null)
	{
		if ($userAvatar === null) {
			return AVATAR_NOT;
		}
		if(!file_exists(AVATARS . $userAvatar)){
			return AVATAR_NOT;
		}
		return AVATARS_HTML . $userAvatar;
	}
}

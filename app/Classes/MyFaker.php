<?php
//https://github.com/ottaviano/faker-gravatar
namespace App\Classes;

class MyFaker
{
	private $faker;
	public function __construct()
	{
		$this->faker = \Faker\Factory::create('ru_RU');
	}
	/**
	 * генератор фейковых пользователей
	 * @return array возвращает массив, аналогичный массиву $_POST,
	 * который получается при создании пользователя вручную
	 */
	public function fakerUser()
	{
		$fakeUser = [
			'email' => $this->faker->email(),
			'send_email' => 'false',
			'username' => $this->faker->name(),
			'password' => '123456789',
			'password_confirm' => '123456789',
			'roles_mask' => '2',
			'status' => '0',

			'myInfo' => [
				'about' => $this->faker->text(),
				'work_place' => $this->faker->address(),
				'phone' => $this->faker->phoneNumber(),
				'address' => $this->faker->address(),
				'activity' => rand(1, 4),
				'vk' => $this->faker->word(),
				'telegram' => $this->faker->word(),
				'instagram' => $this->faker->word(),
			]
		];

		return $fakeUser;
	}

	/**
	 * Генератор аватарок
	 * @return string возвращает путь к файлу аватарки
	 */
	public function fakerAvatar()
	{
		$avatarArray = glob(AVATAR_FAKER);
		$randKey = array_rand($avatarArray);
		return $avatarArray[$randKey];
	}
}

<?php
/**
 * @var $faker \Faker\Generator
 * * @var $index integer
 *
 */
return [
    'name' => $faker->name(),
    'email' => $faker->unique()->email(),
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'phone' => $faker->numerify('+7(###)###-##-##'),
    'telegram' => $faker->unique()->word(),
    'description' => $faker->paragraph(),
    'birth_date' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
    'avatar_file_id' => random_int(1, 5),
    'category_id' => random_int(1, 8),
    'city_id' => random_int(1, 1087)
];

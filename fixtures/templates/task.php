<?php
/**
 * @var $faker \Faker\Generator
 */
$faker = Faker\Factory::create('ru_RU');

return [
    'title' => $faker->sentence(),
    'description' => $faker->paragraph(),
    'budget' => random_int(500, 5000),
    'status' => random_int(1, 5),
    'customer_id' => random_int(1, 5),
    'category_id' => random_int(1, 8),
    'city_id' => random_int(1, 1087),
    'expiry_dt' => $faker->dateTimeBetween('+1 day', '+10 days')->format('Y-m-d')
];

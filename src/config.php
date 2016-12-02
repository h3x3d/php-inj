<?php
return [
  'db' => [
    'url' => 'mysql:host=127.0.0.1;port=3306;dbname=inject',
    'user' => 'inject',
    'password' => 'inject'
  ],
  'users' => [
    ['name' => 'Vasya', 'email' => 'test@example.com', 'hidden' => 0, 'card' => '3456745312345678', 'password' => 'qwerty'],
    ['name' => 'Petya', 'email' => 'test@example.com', 'hidden' => 0, 'card' => '3456745312345678', 'password' => 'qwerty'],
    ['name' => 'Kolya', 'email' => 'test@example.com', 'hidden' => 1, 'card' => '3456745312345678', 'password' => 'qwerty'],
    ['name' => 'Anna', 'email' => 'test@example.com', 'hidden' => 0, 'card' => '3456745312345678', 'password' => 'qwerty'],
    ['name' => 'Evstrafiy', 'email' => 'test@example.com', 'hidden' => 1, 'card' => '3456745312345678', 'password' => 'qwerty'],
    ['name' => 'Polina', 'email' => 'test@example.com', 'hidden' => 1, 'card' => '3456745312345678', 'password' => 'qwerty']
  ]
];
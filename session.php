<?php
#session.php

session_start();
if (isset($_SESSION['username'])) {

  if (!isset($_SESSION['proyecto'])) {
    //comprobar si existe el archivo
    $filename = '../data/usersData/' . $_SESSION['username'] . '.json';
    if (file_exists($filename)) {
      cargarProyecto();
    } else {
      $_SESSION['proyecto'] = inicializarProyecto();
    }
  }
} else {
  redirectIfNotLoggedIn();
}


function redirectIfNotLoggedIn()
{
  //checar si la pagina actual es login.php o register.php
  if (basename($_SERVER['PHP_SELF']) === 'login.php' || basename($_SERVER['PHP_SELF']) === 'register.php') {
    return;
  } else {
    header('Location: ../index/login.php');
    exit;
  }
}

//JSON inicial para guardar los datos del proyecto
function inicializarProyecto()
{
  return [
    'preguntas' => [
      'iQue' => '',
      'iPara' => 'para ',
      'iCon' => 'con ',
      'iComo' => 'como ',
    ],
    'etapa0' => [],
    'etapa1' => [],
    'etapa2' => [],
    'etapa3' => [],
    'etapa4' => []
  ];
}

//Función para guardar los datos de la sesión en un archivo JSON en data/usersData/{username}.json

function guardarProyecto()
{
  //si el archivo no existe, se crea
  $filename = '../data/usersData/' . $_SESSION['username'] . '.json';
  if (!file_exists($filename)) {
    file_put_contents($filename, json_encode($_SESSION['proyecto'], JSON_PRETTY_PRINT));
  }
  //si el archivo existe, se actualiza
  else {
    $data = file_get_contents($filename);
    $usersData = json_decode($data, true);
    $usersData = $_SESSION['proyecto'];
    file_put_contents($filename, json_encode($usersData, JSON_PRETTY_PRINT));
  }
}

//Función para cargar los datos del proyecto desde un archivo JSON en data/usersData/{username}.json cuando el usuario inicia sesión

function cargarProyecto()
{
  $filename = '../data/usersData/' . $_SESSION['username'] . '.json';
  if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $_SESSION['proyecto'] = json_decode($data, true);
  }
}


function saveIfPost()
{
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //no aplica para login y register
    if (basename($_SERVER['PHP_SELF']) !== 'login.php' && basename($_SERVER['PHP_SELF']) !== 'register.php') {
      guardarProyecto();
    }
  }
}

saveIfPost();

?> 



<?php //ejemplo de var_dump($_SESSION) 

//cada que se inicia la sesion se crea un array con los datos del proyecto

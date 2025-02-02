<?php

require_once '../config/config.php';


if (isset($_POST['email'], $_POST['name'], $_POST['password']) && !any_empty($_POST['email'], $_POST['name'], $_POST['password'])) {

    if(session()->has('user_id')){
        header('location:../pages/home.php');
        exit;
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = ORM::for_table('users')->where('email', $email)->find_one();

    if ($check) {
        session()->set_flash_message('error', 'Email Already Exists');
        header('location:../?register=1');
        exit;
    }

    $insert = array(
        'name' => $name,
        'email' => $email,
        'password' => md5($password)
    );

    $new = ORM::for_table('users')->create($insert);

    $new->save();

    $session_data = array('name'=> $new->name, 'email'=>$new->email, 'user_id'=>$new->id(), 'storage'=>$new->storage_allocate);

    session()->set($session_data);

    header('location:../pages/home.php');
    exit;
}

if (isset($_POST['email'], $_POST['password']) && !any_empty($_POST['email'], $_POST['password'])) {

    if(session()->has('user_id')){
        header('location:../pages/home.php');
        exit;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = ORM::for_table('users')->where('email', $email)->where('password', md5($password))->find_one();

    if (!$check) {
        session()->set_flash_message('error', 'Invalid Credential');
        header('location:../');
        exit;
    }


    $session_data = array('name'=> $check->name, 'email'=>$check->email, 'user_id'=>$check->id(), 'storage'=>$check->storage_allocate);

    session()->set($session_data);

    header('location:../pages/home.php');
    exit;
}

if(isset($_GET['logout'])){

    session()->destroy();

    is_logged_in(true);

}

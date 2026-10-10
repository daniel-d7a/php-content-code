<?php

$db = mysqli_connect("localhost", "root", "", "blog_test");

if(!$db){
  die();
}
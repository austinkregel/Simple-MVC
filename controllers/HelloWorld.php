<?php
class HelloWorld extends Controller{
	public function __construct(){
		parent::__construct();
	}
	public function index(){
	  echo "Hello World!";
	}
	public function second(){
		View::make('second');
	}
}

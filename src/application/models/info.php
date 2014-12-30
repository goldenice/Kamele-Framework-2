<?php
namespace Application\Models;
use System\Application\Model;

if (!defined('SYSTEM')) exit('No direct script access allowed');

class Info extends Model {
	
	public function getTitle() {
		return 'Kamele 2.0';
	}
	
	public function getHelloWorld() {
		return 'This is a hello world page. Thanks for picking Kamele as your framework!';
	}
	
}
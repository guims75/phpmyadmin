<?php

class Location
{
	static function to($file)
	{
		header("Location:$file");
		exit;
	}
}

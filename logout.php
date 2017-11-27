<?php

	session_start();

	session_unset(); //unset od data

	session_destroy(); //destory of data

	header('Location: index.php');
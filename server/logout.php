<?php
session_start();

session_unset();
session_destroy();

header('Location: /ws1-jamer/src/index.html');
exit();

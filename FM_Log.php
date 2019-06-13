<?php

function FM_Log (string $method, string $message = '') {
	$paddingLen = strlen($method) < 20 ? 20 : 
	strlen($method) >= 20 && strlen($method) < 40 ? 40 :
	strlen($method) >= 40 && strlen($method) < 60 ? 60 :
	80;
	$method_pad = str_pad($method , $paddingLen, " " , STR_PAD_RIGHT);
	error_log('[FM_Log]['.$method_pad.']'.(strlen($message) == 0 ? '' : '['.$message.']'));
}

?>
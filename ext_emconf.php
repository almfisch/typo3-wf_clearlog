<?php

########################################################################
# Extension Manager/Repository config file for ext "wf_clearlog".
#
# Auto generated 24-05-2011 11:06
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'wfClearLog',
	'description' => 'Clears the database tables sys_log and sys_history. Be careful!!!',
	'category' => 'module',
	'author' => 'Andi Platen',
	'author_email' => 'info@wireframe.de',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => 'mod1',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.4',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:11:{s:9:"ChangeLog";s:4:"04be";s:10:"README.txt";s:4:"9fa9";s:12:"ext_icon.gif";s:4:"2d78";s:14:"ext_tables.php";s:4:"e74b";s:19:"doc/wizard_form.dat";s:4:"1b3e";s:20:"doc/wizard_form.html";s:4:"7176";s:13:"mod1/conf.php";s:4:"d759";s:14:"mod1/index.php";s:4:"780f";s:18:"mod1/locallang.xml";s:4:"ada3";s:22:"mod1/locallang_mod.xml";s:4:"a25b";s:19:"mod1/moduleicon.gif";s:4:"2d78";}',
	'suggests' => array(
	),
);

?>

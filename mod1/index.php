<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Andi Platen <info@wireframe.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


$LANG->includeLLFile('EXT:wf_clearlog/mod1/locallang.xml');
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('backend') . 'Classes/Template/StandardDocumentTemplate.php';
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('backend') . 'Classes/Module/BaseScriptClass.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]



/**
 * Module 'Clear Log' for the 'wf_clearlog' extension.
 *
 * @author	Andi Platen <info@wireframe.de>
 * @package	TYPO3
 * @subpackage	tx_wfclearlog
 */
class  tx_wfclearlog_module1 extends t3lib_SCbase {
				var $pageinfo;

				/**
				 * Initializes the Module
				 * @return	void
				 */
				function init()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					parent::init();

					/*
					if (t3lib_div::_GP('clear_all_cache'))	{
						$this->include_once[] = PATH_t3lib.'class.t3lib_tcemain.php';
					}
					*/
				}

				/**
				 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
				 *
				 * @return	void
				 */
				function menuConfig()	{
					global $LANG;
					$this->MOD_MENU = Array (
						'function' => Array (
							'1' => $LANG->getLL('function1'),
							'2' => $LANG->getLL('function2'),
						)
					);
					parent::menuConfig();
				}

				/**
				 * Main function of the module. Write the content to $this->content
				 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
				 *
				 * @return	[type]		...
				 */
				function main()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					// Access check!
					// The page will show only if there is a valid page and if this page may be viewed by the user
					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
					$access = is_array($this->pageinfo) ? 1 : 0;

					if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{

							// Draw the header.
						//$this->doc = t3lib_div::makeInstance('noDoc');
						$this->doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
						$this->doc->backPath = $BACK_PATH;
						$this->doc->form='<form action="" method="post" enctype="multipart/form-data">';

							// JavaScript
						$this->doc->JScode = '
							<script language="javascript" type="text/javascript">
								script_ended = 0;
								function jumpToUrl(URL)	{
									document.location = URL;
								}
							</script>
						';
						$this->doc->postCode='
							<script language="javascript" type="text/javascript">
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = 0;
							</script>
						';

						$headerSection = $LANG->getLL('description');

						$this->content.=$this->doc->startPage($LANG->getLL('title'));
						$this->content.=$this->doc->header($LANG->getLL('title'));
						$this->content.=$this->doc->spacer(5);
						$this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
						$this->content.=$this->doc->divider(10);


						// Render content:
						$this->moduleContent();


						// ShortCut
						if ($BE_USER->mayMakeShortcut())	{
							$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
						}

						$this->content.=$this->doc->spacer(10);
					} else {
							// If no access or if ID == zero

						//$this->doc = t3lib_div::makeInstance('noDoc');
						$this->doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
						$this->doc->backPath = $BACK_PATH;

						$this->content.=$this->doc->startPage($LANG->getLL('title'));
						$this->content.=$this->doc->header($LANG->getLL('title'));
						$this->content.=$this->doc->spacer(5);
						$this->content.=$this->doc->spacer(10);
					}

				}

				/**
				 * Prints out the module HTML
				 *
				 * @return	void
				 */
				function printContent()	{

					$this->content.=$this->doc->endPage();
					echo $this->content;
				}

				/**
				 * Generates the module content
				 *
				 * @return	void
				 */
				function moduleContent()	{
					global $LANG;

					if(t3lib_div::_GP('action') == 'clear')
					{
						$GLOBALS['TYPO3_DB']->sql_query('TRUNCATE TABLE sys_log');
						$GLOBALS['TYPO3_DB']->sql_query('TRUNCATE TABLE sys_history');
						$GLOBALS['TYPO3_DB']->sql_query('TRUNCATE TABLE tx_extensionmanager_domain_model_extension');
						$GLOBALS['TYPO3_DB']->sql_query('TRUNCATE TABLE cache_imagesizes');
						$GLOBALS['TYPO3_DB']->sql_query('TRUNCATE TABLE sys_file_processedfile');
					}

					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_log', '');
					$countLog = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
					
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_history', '');
					$countHistory = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
					
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_extensionmanager_domain_model_extension', '');
					$countExtensions = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
					
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'cache_imagesizes', '');
					$countImgSizes = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
					
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'sys_file_processedfile', '');
					$countProcessedFile = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
					$GLOBALS['TYPO3_DB']->sql_free_result($res);

					$res = $GLOBALS['TYPO3_DB']->sql_query('SHOW TABLE STATUS');
					while($row = mysqli_fetch_array($res))
					{
    					$total_size = ($row[ "Data_length" ] + $row[ "Index_length" ]) / 1024;
    					$tables[$row['Name']] = sprintf("%.2f", $total_size);
					}
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
					$sizeLog = $tables['sys_log'];
					$sizeHistory = $tables['sys_history'];
					$sizeExtensions = $tables['tx_extensionmanager_domain_model_extension'];
					$sizeImgSizes = $tables['cache_imagesizes'];
					$sizeProcessedFile = $tables['sys_file_processedfile'];

					$content = '<b>sys_log:</b> ' . $countLog . ' (' . $sizeLog . ' KiB)';
					$content .= '<br />';
					$content .= '<b>sys_history:</b> ' . $countHistory . ' (' . $sizeHistory . ' KiB)';
					$content .= '<br />';
					$content .= '<b>sys_file_processedfile:</b> ' . $countProcessedFile . ' (' . $sizeProcessedFile . ' KiB)';
					$content .= '<br />';
					$content .= '<b>cache_imagesizes:</b> ' . $countImgSizes . ' (' . $sizeImgSizes . ' KiB)';
					$content .= '<br />';
					$content .= '<b>tx_extensionmanager_domain_model_extension:</b> ' . $countExtensions . ' (' . $sizeExtensions . ' KiB)';

					switch((string)$this->MOD_SETTINGS['function'])	{
						case 1:
							$content .= '<br /><br />';
							$content .= $LANG->getLL('longdescription');
							$this->content.=$this->doc->section($LANG->getLL('function1'),$content,0,1);
						break;
						case 2:
							$content .= '<br />';
							$content .= '<form action="index.php?id='.$this->id.'" method="POST" name="editform">';
							$content .= '<input type="hidden" name="action" value="clear" />';
							$content .= '<br /><input type="submit" value="' . $LANG->getLL('function2') . '" title="' . $LANG->getLL('function2') . '" />';
							$content .= '</form>';
							$content .= '<br /><br />';
							$content .= $LANG->getLL('warning');
							$this->content.=$this->doc->section($LANG->getLL('function2'),$content,0,1);
						break;
					}
				}

		}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wf_clearlog/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wf_clearlog/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_wfclearlog_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>
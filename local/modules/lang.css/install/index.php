<?defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Lang\Main\Css\Modifier;

IncludeModuleLangFile(__FILE__);

if (class_exists('lang_css')) return;

class lang_css extends CModule {

	const MODULE_ID = 'lang.css';
	var $MODULE_ID = 'lang.css';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_GROUP_RIGHTS;
	var $strError = '';
	
	function __construct()
	{
		$arModuleVertion = [];
		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("{$this->MODULE_ID}_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("{$this->MODULE_ID}_MODULE_DESCRIPTION");
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_NAME = Loc::getMessage("{$this->MODULE_ID}_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("{$this->MODULE_ID}_PARTNER_URI");
	}

	public function InstallDB()
	{
		$cssStyle = file_get_contents(dirname(__FILE__) . '/sql/cssStyle.sql');
		$cssModifier = file_get_contents(dirname(__FILE__) . '/sql/cssModifier.sql');

		Application::getConnection()->query($cssStyle);
		Application::getConnection()->query($cssModifier);

		return true;
	}

	public function UnInstallDB()
	{
		Application::getConnection()->dropTable('cssStyle');
		Application::getConnection()->dropTable('cssModifier');

		return true;
	}

	function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'Lang\\Main\\Css\\Modifier',
            'addStyleHead'
        );

		return true;
	}

	public function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'Lang\\Main\\Css\\Modifier',
            'addStyleHead'
        );

		return true;
	}

	function InstallFiles($arParams = [])
	{
		
	}

	function UnInstallFiles()
	{

	}

	function DoInstall()
	{
		$this->InstallDB();
        $this->InstallEvents();
		$this->InstallFiles();
		ModuleManager::registerModule($this->MODULE_ID);

		Loader::includeModule('lang.css');

		$css = new Modifier([
			'baseFont' => 16,
			'sm' => true,
			'md' => true,
			'lg' => true,
			'xl' => true,
		]);

		@include $_SERVER['DOCUMENT_ROOT'] . "/local/templates/{$this->MODULE_ID}/php.css/baseVar.php";

		$css->generate($cssGlobal);
		$css->addStyleBase();
	}

	function DoUninstall()
	{
		$this->UnInstallDB();
        $this->UnInstallEvents();
		$this->UnInstallFiles();
        ModuleManager::unregisterModule($this->MODULE_ID);
	}
}
?>
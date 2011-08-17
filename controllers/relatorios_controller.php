<?php
/**
 * Controller para Relatórios
 * 
 * @package		icake
 * @subpakage	icake.controller
 */
/**
 * @package		icake
 * @subpakage	icake.controller
 */
class RelatoriosController extends AppController {
	/**
	 * 
	 * Nome do controller
	 * 
	 * @var		string
	 * @access	public
	 */
	public $name 	= 'Relatorios';

	/**
	 * Model usado pelo controlador
	 * 
	 * @var		array
	 * @access	public
	 */
	public $uses	= array();

	/**
	 * Ajudantes
	 * 
	 * @var		array
	 * @access	public
	 */
	public $helpers		= array('Jcake.Visao');

	/**
	 * Antes de tudo
	 * 
	 * @return void
	 */
	public function beforeFilter()
	{
		$this->layout 	= 'jcake';
		$this->plugin 	= 'jcake';
		parent::beforeFilter();
	}

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		// título link
		$linkTit[1] = 'Relatórios';
		$this->set(compact('linkTit'));
	}

	/**
	 * Método start
	 * 
	 * @return void
	 */
	public function index()
	{
		if ($this->data)
		{
			$msg = '';
			switch($this->data['tipo'])
			{
				case 'limparCache':
					$this->setLimpaCache();
					$msg = 'Cache limpo com sucesso';
					break;
			}
			$this->set('msg',$msg);
		}
	}
}
?>

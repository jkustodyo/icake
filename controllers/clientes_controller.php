<?php
/**
 * Controller para o cadastro de clientes
 * 
 * @package		icake
 * @subpackage	icake.controller
 */
/**
 * @package		icake
 * @subpackage	icake.controller
 */
class ClientesController extends AppController {
	/**
	 * Nome do controller
	 * 
	 * @var		string
	 * @access	public
	 */
	public $name 		= 'Clientes';

	/**
	 * Model do controller
	 * 
	 * @var		array
	 * @access	public
	 */
	public $uses		= array('Cliente');

	/**
	 * Componentes
	 * 
	 * @var		array
	 * @access	public
	 */
	public $components	= array('Jcake.Controlador');

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
	 * @return	void
	 */
	public function beforeFilter()
	{
		if (isset($this->data) && ($this->action=='editar' || $this->action='novo') )
		{
			// tornando alguns campos em maiúsculo
			$campos = array('nome','endereco','bairro');
			foreach($campos as $_campo)
			{
				if (isset($this->data['Cliente'][$_campo])) $this->data['Cliente'][$_campo] = mb_strtoupper($this->data['Cliente'][$_campo]);
			}
			// removendo a máscara 
			$campos = array('aniversario','cep');
			foreach($campos as $_campo)
			{
				if (isset($this->data['Cliente'][$_campo])) $this->data['Cliente'][$_campo]	= ereg_replace('[./-]','',$this->data['Cliente'][$_campo]);
			}
			$campos = array('telefone','celular','cpf','cnpj');
			foreach($campos as $_campo)
			{
				if (isset($this->data['Cliente'][$_campo]))
				{
					$this->data['Cliente'][$_campo]	= ereg_replace('[- ./]','',$this->data['Cliente'][$_campo]);
				}
			}
		}
		parent::beforeFilter();
	}

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		// campos padrão
		$campos				= array();
		$onReadView 		= array();
		$edicaoCampos		= array('Cliente.nome','Cliente.aniversario','#','Cliente.endereco','#','Cliente.bairro','Cliente.cep','#','Cliente.estado_id','Cliente.cidade_id','@','Cliente.telefone','Cliente.celular','#','Cliente.cpf','Cliente.cnpj','#','Cliente.email','@','Cliente.obs','@','Cliente.modified','Cliente.created');
		$botoesEdicao		= array();
		$listaCampos 		= array('Cliente.nome','Cliente.bairro','Cidade.nome','Cliente.telefone','Cliente.celular','Cliente.modified','Cliente.created');
		$listaFerramentas	= array();
		$escreverTitBt 		= true;

		// configurando a pesquisa
		$camposPesquisa['Cliente.nome'] 	= 'Nome';
		$camposPesquisa['Cliente.email'] 	= 'e-mail';
		$camposPesquisa['Cliente.endereco']	= 'Endereço';

		// recuperando conteúdo de relacionamentos
		$cidades 			= $this->Cliente->Cidade->find('all');
		$estados 			= $this->Cliente->Cidade->Estado->find('list');

		// recuperando o id da cidade e do estado
		$idCidade 			= isset($this->data['Cliente']['cidade_id']) ? $this->data['Cliente']['cidade_id'] : 2302;
		$idEstado			= isset($this->data['Cliente']['estado_id']) ? $this->data['Cliente']['estado_id'] : 1;

		// recarregando somente as cidades do municipio
		$_cidades = array();
		foreach($cidades as $_linha => $_arrModel)
		{
			if ($idEstado==$_arrModel['Estado']['id'])
			{
				$_cidades[$_arrModel['Cidade']['id']] = $_arrModel['Cidade']['nome'];
			}
		}
		$cidades = $_cidades;

		$campos['Cliente']['nome']['input']['label']['text'] 		= 'Nome';
		$campos['Cliente']['nome']['input']['size']					= '60';
		$campos['Cliente']['nome']['th']['width']					= '400px';

		$campos['Cliente']['aniversario']['input']['label']['text']	= 'Aniversário';
		$campos['Cliente']['aniversario']['input']['label']['style']= 'color: green; font-weight: bold;';
		$campos['Cliente']['aniversario']['mascara'] 				= '99/99';
		$campos['Cliente']['aniversario']['input']['style']			= 'width: 46px; text-align: center; ';

		$campos['Cliente']['endereco']['input']['label']['text']	= 'Endereço';
		$campos['Cliente']['endereco']['input']['size']				= '60';

		$campos['Cliente']['bairro']['input']['label']['text']		= 'Bairro';
		$campos['Cliente']['bairro']['input']['size']				= '60';
		$campos['Cliente']['bairro']['th']['width']					= '200px';

		$campos['Cliente']['email']['input']['size']				= '60';
		
		$campos['Cliente']['telefone']['input']['label']['text']	= 'Telefone';
		$campos['Cliente']['telefone']['th']['width']				= '120px';
		$campos['Cliente']['telefone']['mascara']					= '99 9999-9999';
		$campos['Cliente']['telefone']['td']['align']				= 'center';

		$campos['Cliente']['celular']['input']['label']['text']		= 'Celular';
		$campos['Cliente']['celular']['input']['label']['class']	= '';
		$campos['Cliente']['celular']['th']['width']				= '120px';
		$campos['Cliente']['celular']['mascara']					= '99 9999-9999';
		$campos['Cliente']['celular']['td']['align']				= 'center';

		$campos['Cliente']['cpf']['input']['label']['text']			= 'Cpf';
		$campos['Cliente']['cpf']['mascara']						= '999.999.999-99';
		$campos['Cliente']['cpf']['input']['size']					= 11;

		$campos['Cliente']['cnpj']['input']['label']['text']		= 'Cnpj';
		$campos['Cliente']['cnpj']['input']['label']['class']		= '';
		$campos['Cliente']['cnpj']['mascara']						= '99.999.999/9999-99';
		$campos['Cliente']['cnpj']['input']['size']					= 16;

		$campos['Cliente']['cep']['input']['label']['text']			= 'Cep';
		$campos['Cliente']['cep']['input']['label']['class']		= '';
		$campos['Cliente']['cep']['input']['style']					= 'width: 90px; text-align: center;';
		$campos['Cliente']['cep']['mascara']						= '99.999-999';

		$campos['Cliente']['cidade_id']['input']['label']['text']	= 'Cidade';
		$campos['Cliente']['cidade_id']['input']['label']['class']	= '';
		$campos['Cliente']['cidade_id']['input']['label']['style']	= 'width: 70px; float: left; text-align: right; display: block; margin-right: 5px;';
		$campos['Cliente']['cidade_id']['input']['empty']			= '-- Escolha um cidade --';
		$campos['Cliente']['cidade_id']['input']['style']			= 'width: 245px;';
		$campos['Cliente']['cidade_id']['input']['default'] 		= 2302;

		$campos['Cidade']['nome']['input']['label']['text']			= 'Cidade';
		$campos['Cidade']['nome']['input']['style']					= 'width: 60px;';
		$campos['Cidade']['nome']['th']['width']					= '200px';

		$campos['Cliente']['obs']['input']['label']['text']			= 'Observações';
		$campos['Cliente']['obs']['input']['type']					= 'textarea';
		$campos['Cliente']['obs']['input']['cols']					= '69';

		$campos['Cliente']['estado_id']['input']['label']['text'] 	= 'Estado';
		$campos['Cliente']['estado_id']['input']['default'] 		= 1;

		$campos['Estado']['uf']['input']['label']['text'] 			= 'Uf';

		if ($this->action=='imprimir')
		{
			$edicaoCampos = array('Cliente.nome','Cliente.aniversario','#','Cliente.endereco','#','Cliente.bairro','#','Cliente.cep','#','Cidade.nome','Estado.uf','@','Cliente.telefone','Cliente.celular','#','Cliente.cpf','Cliente.cnpj','#','Cliente.email','@','Cliente.obs','@','Cliente.modified','Cliente.created');
		}

		if (in_array($this->action,array('editar','novo')))
		{
			array_unshift($onReadView,'$("#ClienteNome").focus();');
			array_unshift($onReadView,'$("#ClienteEstadoId").change(function() { setCombo("ClienteCidadeId","'.Router::url('/',true).'cidades/combo/estado_id/'.'", $(this).val());  });');
		}

		$this->set(compact('listaCampos','edicaoCampos','campos','camposPesquisa','escreverTitBt','onReadView','listaFerramentas','botoesEdicao','estados','cidades','perfis'));
	}
}
?>

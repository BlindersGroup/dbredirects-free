<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    DevBlinders <info@devblinders.com>
 *  @copyright 2007-2020 DevBlinders
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */


class AdminDbRedirects410Controller extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'dbredirects';
        $this->className = 'DbRedirect';
        $this->lang = false;
        $this->multishop_context = Shop::CONTEXT_ALL;

        parent::__construct();

        $this->toolbar_title = $this->l('Redirecciones 410');
        $this->fields_list = array(
            'id_dbredirects' => array(
                'title' => $this->trans('ID', array(), 'Admin.Global'),
                'align' => 'center',
                'width' => 30
            ),
            'url_antigua' => array(
                'title' => $this->trans('Url', array(), 'Admin.Global'),
            ),
            'active' => array(
                'title' => 'Activo',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => true,
                'width' => 25,
            ),
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );
    }

    public function initProcess()
    {
        if (Tools::getIsset('status'.$this->table))
        {
            DbRedirect::isToggleStatus((int)Tools::getValue('id_dbredirects'));
            return;
        }

        return parent::initProcess();
    }

    public function renderList()
    {
        $this->_where = 'AND a.`type` = 2';

        // removes links on rows
        $this->list_no_link = true;

        // adds actions on rows
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $list = parent::renderList();
        return $list;
    }

    public function renderView()
    {
        // adds actions on rows
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {

        // Sets the title of the toolbar
        $this->toolbar_title = $this->l('Redirección 410');

        $this->fields_value = array(
            'type' => 2,
        );

        // Sets the fields of the form
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Redirección 410'),
                'icon' => 'icon-pencil'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_dbredirects'
                ),

                array(
                    'type' => 'hidden',
                    'name' => 'type',
                ),

                array(
                    'type' => 'text',
                    'label' => $this->l('Url'),
                    'desc' => 'Debe ser la url sin el dominio, por ejemplo: /ruta-antigua-redireccion',
                    'name' => 'url_antigua',
                    'required' => true,
                    'lang' => false,
                ),

                array(
                    'type' => 'switch',
                    'label' => $this->l('Activo'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),

            ),
        );


        $this->fields_form['submit'] = array(
            'title' => $this->trans('Save', array(), 'Admin.Actions'),
        );

        return parent::renderForm();
    }

    public function processAdd()
    {
        if (!($object = $this->loadObject(true))) {
            return;
        }

        $_POST['date_add'] = date('Y-m-d H:i:s');
        $url_antigua = Tools::getValue('url_antigua');
        $exists = DbRedirect::isRedirect(trim($url_antigua));
        if((int)$exists['id_dbredirects'] == 0) {
            parent::processAdd();
        } else {
            $this->context->controller->errors[] = $this->l( sprintf('La URL ya tiene un registro creado: %s', $url_antigua) );
        }

    }

}
<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');

$document = JFactory::getDocument();

// Add styles
$document->addStyleDeclaration('
    .pagtesouro-form {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .pagtesouro-form .control-group {
        margin-bottom: 15px;
    }
    .pagtesouro-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .pagtesouro-form select,
    .pagtesouro-form input[type="text"],
    .pagtesouro-form input[type="number"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .pagtesouro-form .btn-primary {
        margin-top: 15px;
        background-color: #243413;
        color: #fff;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
    }
    .pagtesouro-form .btn-primary:hover {
        background-color: #45472f;
    }
    .pagtesouro-form .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    .pagtesouro-form .alert-info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #31708f;
    }
    .pagtesouro-form .alert-danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }
    .pagtesouro-form .hide {
        display: none;
    }
');

// Add script
$document->addScriptDeclaration('
jQuery(document).ready(function($) {

$("#formError").hide();
        // Variables to store UASG and service information
    var selectedOrgao = "";
    var selectedUg = "";
    var selectedServico = "";

    // Load services when UASG is   selected
    $("#uasg_id").on("change", function() {
        var uasgId = $(this).val();
        if (uasgId) {
            // Get UASG details
            $.ajax({
                url: "' . JRoute::_('index.php?option=com_pagtesouro&view=raw&task=formulario.getUasgDetails&format=raw', false) . '",
                type: "POST",
                data: {
                    uasg_id: uasgId,
                    "' . JSession::getFormToken() . '": 1
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        console.error(response.message);
                        return;
                    }
                    
                    selectedOrgao = response.data.orgao;
                    selectedUg = response.data.ug;
                }
            });

            // Get services - MUDANÇA AQUI DE json PARA raw
            $.ajax({
                url: "' . JRoute::_('index.php?option=com_pagtesouro&view=raw&task=formulario.getServicos&format=raw', false) . '",
                type: "POST",
                data: {
                    uasg_id: uasgId,
                    "' . JSession::getFormToken() . '": 1
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        console.error(response.message);
                        return;
                    }
                    
                    var options = \'<option value="">'.JText::_('COM_PAGTESOURO_SELECT_SERVICE').'</option>\';
                    
                    $.each(response.data, function(index, item) {
                        options += \'<option value="\' + item.id + \'">\' + item.codigo + \' - \' + item.descricao + \'</option>\';
                    });
                    
                    $("#servico_id").html(options).prop("disabled", false);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $("#servico_id").html(\'<option value="">'.JText::_('COM_PAGTESOURO_SELECT_SERVICE').'</option>\').prop("disabled", true);
            selectedOrgao = "";
            selectedUg = "";
        }
    });

    // Get service details when service is selected - MUDANÇA AQUI DE json PARA raw
    $("#servico_id").on("change", function() {
        var servicoId = $(this).val();
        if (servicoId) {
            $.ajax({
                url: "' . JRoute::_('index.php?option=com_pagtesouro&view=raw&task=formulario.getServicoDetails&format=raw', false) . '",
                type: "POST",
                data: {
                    servico_id: servicoId,
                    "' . JSession::getFormToken() . '": 1
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        console.error(response.message);
                        return;
                    }
                    
                    selectedServico = response.data.codigo;
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            selectedServico = "";
        }
    });

    // Submit form
    $("#pagtesouroForm").on("submit", function(e) {
        e.preventDefault();
        
        // Get form values
        var nomeContribuinte = $("#nome_contribuinte").val();
        var cpfCnpj = $("#cpf_cnpj").val();
        var numeroReferencia = $("#numero_referencia").val();
        var valorPrincipal = $("#valor_principal").val();
        
        // Validate form
        if (!selectedOrgao || !selectedUg || !selectedServico || !nomeContribuinte || !cpfCnpj || !numeroReferencia || !valorPrincipal) {
            $("#formError").removeClass("hide").text("' . JText::_('COM_PAGTESOURO_ERROR_FILL_ALL_FIELDS') . '");
            return false;
        }
        
        // Remove any non-numeric characters from CPF/CNPJ
        cpfCnpj = cpfCnpj.replace(/\D/g, "");
        
       // Create the URL and open in a new window
        var url = "https://pagtesouro.tesouro.gov.br/portal-gru/#/pagamento-gru?orgao=" + selectedOrgao + 
                  "&ug=" + selectedUg + 
                  "&servico=" + selectedServico + 
                  "&nomeContribuinte=" + encodeURIComponent(nomeContribuinte) + 
                  "&cpfCnpjContribuinte=" + cpfCnpj + 
                  "&numeroReferencia=" + numeroReferencia + 
                  "&valorPrincipal=" + valorPrincipal;
        
        window.open(url, "_blank");
    });
});
');
?>

<div class="pagtesouro-form">
    <h2><?php echo JText::_('COM_PAGTESOURO_FORM_TITLE'); ?></h2>
    
    <div id="formError" class="alert alert-danger hide"></div>
    
    <form id="pagtesouroForm" action="" method="post" class="form-validate">
        <div class="control-group">
            <label for="uasg_id"><?php echo JText::_('COM_PAGTESOURO_FIELD_UASG_LABEL'); ?></label>
            <select id="uasg_id" name="uasg_id" class="form-control required">
                <option value=""><?php echo JText::_('COM_PAGTESOURO_SELECT_UASG'); ?></option>
                <?php foreach ($this->uasgs as $uasg) : ?>
                    <option value="<?php echo $uasg->id; ?>"><?php echo $uasg->orgao . ' - ' . $uasg->ug . ' (' . $uasg->descricao . ')'; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="control-group">
            <label for="servico_id"><?php echo JText::_('COM_PAGTESOURO_FIELD_SERVICO_LABEL'); ?></label>
            <select id="servico_id" name="servico_id" class="form-control required" disabled>
                <option value=""><?php echo JText::_('COM_PAGTESOURO_SELECT_SERVICE'); ?></option>
            </select>
        </div>
        
        <div class="control-group">
            <label for="nome_contribuinte"><?php echo JText::_('COM_PAGTESOURO_FIELD_NOME_CONTRIBUINTE_LABEL'); ?></label>
            <input type="text" id="nome_contribuinte" name="nome_contribuinte" class="form-control required" />
        </div>
        
        <div class="control-group">
            <label for="cpf_cnpj"><?php echo JText::_('COM_PAGTESOURO_FIELD_CPF_CNPJ_LABEL'); ?></label>
            <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control required" />
        </div>
        
        <div class="control-group">
            <label for="numero_referencia"><?php echo JText::_('COM_PAGTESOURO_FIELD_NUMERO_REFERENCIA_LABEL'); ?></label>
            <input type="text" id="numero_referencia" name="numero_referencia" class="form-control required" />
        </div>
        
        <div class="control-group">
            <label for="valor_principal"><?php echo JText::_('COM_PAGTESOURO_FIELD_VALOR_PRINCIPAL_LABEL'); ?></label>
            <input type="number" id="valor_principal" name="valor_principal" step="0.01" min="0.01" class="form-control required" />
        </div>
        
        <div class="control-group">
            <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_PAGTESOURO_BUTTON_SUBMIT'); ?></button>
        </div>
    </form>
</div>
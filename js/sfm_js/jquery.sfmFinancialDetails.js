/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 25, 2013
 */
$(function($){
    //initialize all needed events and functionality...
    documentWriteSFMParamCustoms();
    getLevelsForSelect();
    initNumericOnlyAllowed("#CL-new");
    initNumericOnlyAllowed("#CT-new");
    initCustomerSearchAutoComplete("#customer_code");
    
    $('#SFM-FDMForm').on('submit',function(){
        var self = $(this);
        var CustomerID = self.find('input[name="CustomerID"]').val();
        var IBMID = self.find('input[name="IBMID"]').val();
        var NewGSUType = self.find('select[name="GSUType-new"]').val();
        var NewCL = self.find('input[name="CL-new"]').val();
        var NewCT = self.find('select[name="CT-new"]').val();
        var OldGSUType = self.find('select[name="GSUType-old"]').val();
        var OldCL = self.find('input[name="CL-old"]').val();
        var OldCT = self.find('select[name="CT-old"]').val();
        var action = self.find('input[name="action"]').val();
        
        if((NewGSUType == '' && NewCL == '' && NewCT == '') || (NewGSUType == OldGSUType && NewCL == OldCL && NewCT == OldCT)){
            dialogMessage("No update made in financial details, same values retains.");
            return false;
        }
        
        dialogMessage("Validating and updating financial details, please wait...");
        $.ajax({
            cache: false,
            type:'POST',
            url:'pages/sfm/param_ajax_calls/financial_details_maintenance.php',
            data:{'action':action,'CustomerID':CustomerID,'IBMID':IBMID,'NewGSUType':NewGSUType,'OldGSUType':OldGSUType,'NewCL':NewCL,'NewCT':NewCT,'OldCL':OldCL,'OldCT':OldCT},
            success: frmFDMDoResponse
        });
        return false;
    });
    
    
    //Everything about functions used here follows below...
    function getLevelsForSelect(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type:'POST',
            data: { 'action':'get_levels' },
            success: frmListsLevelsSelect
        });
    }
    
    function frmListsLevelsSelect(response){
        var res = $.parseJSON(response);
        var html = '';
        
        if(res.levels.length > 0){
            for(var x = 0; x < res.levels.length; x++){
                html+= '<option class="SFL" value="'+ res.levels[x].codeID +'">'+ res.levels[x].codeID + ' - ' + res.levels[x].description +'</option>';
            }
            
            $('.SFL').remove();
            $('#SFM-FDMForm select[name="SFL"]').append(html);
        }
    }
    
    //Function that will show up lists of customer options for financial details update...
    function initCustomerSearchAutoComplete(DOMelement){
        //Validation if ther's a selected sales force level....
        $(DOMelement).on('keydown',function(){
            var SFL = $("#SFL").val();
            
            if(SFL == ''){
                dialogMessage('Select sales force level first before searching customer.');
            }
        });
        
        //Do the process of jquery ui autocomplete of customer options...
        $(DOMelement).autocomplete({
            source: function(request, response){
                var SFL = $("#SFL").val();
                //get source remotely from database...
                $.ajax({
                        url: 'pages/sfm/param_ajax_calls/financial_details_maintenance.php',
                        type:'POST',
                        data: { 'action':'search','input_term':request.term, 'SFL':SFL },
                        success: function(data){ //set up details for display....
                            response( $.map( $.parseJSON(data).lists, function( item ) {
                                return {
                                    label: '(' + item.Code + ') -- ' + item.Name,
                                    value: { 'Code':item.Code,'Name':item.Name,'ID':item.ID,'IBMID':item.IBMID,'IBMCode':item.IBMCode,'IBMCredit':item.IBMCredit,
                                             'IBMName':item.IBMName,'GSUType':item.tpi_GSUTypeID,'CTId':item.CreditTermID,'ACL':item.ApprovedCL } 
                                }
                            }));
                            
                            $('#autocomplete-loader').hide();
                        }
                    });
            },
            search: function(){ $('#autocomplete-loader').text('Searching...'); $('#autocomplete-loader').show(); clearFields(); }, //do some notifications that source is still in process....
            select: function( event, ui ) { //select function...
                var FDM = ui.item.value;
                
                $(DOMelement + '_selected').text(FDM.Code + ' -- ' + FDM.Name.toUpperCase());
                $(DOMelement + '_IBM').html('Code: ' + FDM.IBMCode + ' <br />Name: ' + FDM.IBMName + '<br />Credit Limit: ' + FDM.IBMCredit);
                $("#GSUType-old").val(FDM.GSUType);
                
                if(FDM.GSUType == '1'){
                    $("#CT-new").attr('disabled',true);
                }else{
                    $("#CT-new").attr('disabled',false);
                }
                
                $("#GSUType-new").val(FDM.GSUType);
                $("#CL-new").val(FDM.ACL);
                $("#CT-new").val(FDM.CTId);
                
                $("#CL-old").val(FDM.ACL);
                $("#CT-old").val(FDM.CTId);
                $("#CustomerID").val(FDM.ID);
                $("#IBMID").val(FDM.IBMID);
                $("#tbl-FDM-edits").slideDown();
                
                return false;
            }
        });
    }
    
    function frmFDMDoResponse(response){
        var res = $.parseJSON(response);
        dialogMessage(res.message);
    }
    
    //Function that will clear all fields...
    function clearFields(){
        $("#tbl-FDM-edits").slideUp();
        $("#GSUType-old").val('');
        $("#CL-old").val('');
        $("#CT-old").val('');
        $("#GSUType-new").val('');
        $("#CL-new").val('');
        $("#CT-new").val('');
        $("#CustomerID").val('');
        $("#IBMID").val('');
    }
});



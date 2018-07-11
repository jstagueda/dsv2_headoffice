/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 03, 2013
 */
var list_limit = 15;
var pageListerMethod;
var otherObjects;
//Data holder when action set in form is "update", so data will not lost in "With Advance Payment" fields was unselected.
var WAUpdateData = { minAmount:'', noOfDays:'', PDPE:'', PDPS:'' };

$(function($){
    documentWriteSFMParamCustoms();    
    
    if(pageParam){
        initFunctionsADHOCParam();
    }else{
        initFunctionsADHOCGenerator();
    }

    /********** EVERYTHING ABOUT ADHOC CL INCREASE FUNCTIONS BELOW **********/
    //function init for ADHOC CL paremeters page.
    function initFunctionsADHOCParam(){
        pageListerMethod = function ADHOC_CL_GetLists(start,limit){
                                $.ajax({
                                    cache: false,
                                    url: 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php',
                                    type: 'POST',
                                    data: {'action':'lists', 'start':start, 'end':limit},
                                    success: function(response){ ADHOC_CL_Lists(response); }
                                });
                            }
                            
        getLevelsForSelect();
        initCheckBoxSetter('#hasBOCLI');
        doDatePickerLoad("#eDate");
        doDatePickerLoad("#poPeriodStart");
        doDatePickerLoad("#poPeriodEnd");
        doDatePickerLoad("#PaymentDatePeriodStart");
        doDatePickerLoad("#PaymentDatePeriodEnd");
        initSetMultipleFieldsNumericOnly();
        pageListerMethod(0,list_limit);
        paginationActions(list_limit);
        initElementsAdhocCLParameter();
        initCheckBoxSelectSaveIds();
        ajaxDeletePath = 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php';
        initActionDeleteSaveIds();
    }
    
    //function init for ADHOC CL generator page.
    function initFunctionsADHOCGenerator(){
        getADHOCParamsOptions();
        initElementsAdhocCLGenerator();
        paginationActions(list_limit);
        initCheckBoxSaveIdsForPrinting();
        ajaxPrintPath = 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php';
        initActionPrintSaveIds();
    }
      
    function ADHOC_CL_Lists(response){
        var res = $.parseJSON(response);
        var html = '', txt_cp = '',checker = '';
        
        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                
                html+= '<tr class="tbl-td-rows" id="td-row-'+ res.lists[x].AdhocID +'">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].AdhocID +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><a class="adhocID" data-id="'+ res.lists[x].AdhocID +'" href="#">'+ res.lists[x].Description +'</a></td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].EffectivityDate +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].desc_code +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].ctName +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].gsuName +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><input type="checkbox" value="'+ res.lists[x].AdhocID +'" name="action_chk[]" class="action_chk" /></td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
            $('#btn-for-checkbox').removeClass('hide');
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        else $('#tblPageNavigation').html('');
        
    }
    
    //Function that will load / initialize elements action in ADHOC CL parameters page.
    function initElementsAdhocCLParameter(){
        //For listing, when item name was clicked
        $('.tbl-listing-div table').on('click','a.adhocID', function(e){
            var self = $(this);
            var adhocID = self.data('id');

            dialogMessage('Preparing edit details, please wait...');
            clearFields($('#SFM-AHOCCLIForm'));
            $.ajax({
                cache: false,
                url: 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php',
                type: 'POST',
                data: {'action':'edit', 'adhocID':adhocID},
                success: function(response){ setInputsEditDetails(response); }
            });

            e.preventDefault();
        });

        $('#SFM-AHOCCLIForm').on('submit',function(){
            dialogMessage('Processing adhoc, please wait...');
            $.ajax({
                cache: false,
                type:'POST',
                data: $(this).serialize(),
                url: 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php',
                success: function(response){
                    var res = $.parseJSON(response);
                    if($('#dialog-message').is(':data(dialog)')) $('#dialog-message').dialog("destroy");

                    if(res.status == 'success'){
                        clearFields($('#SFM-AHOCCLIForm'));
                    }

                    pageListerMethod(0,list_limit);
                    dialogMessage(res.message);
                }
            });

            return false;
        });

        $('#withAdvancePayment').on('click', function(){
            var self = $(this);
            var element = $('#SFM-AHOCCLIForm');
            var action = element.find('input[name="action"]').val();

            if(self.is(':checked')){
                self.val(1);
                $('#withAdvancePayement-others').slideDown();
                $('#withAdvancePayement-others').removeClass('hide');
                
                if(action == 'update'){
                    element.find('input[name="minAmount"]').val(WAUpdateData.minAmount);
                    element.find('input[name="noOfDays"]').val(WAUpdateData.noOfDays);
                    element.find('input[name="PaymentDatePeriodStart"]').val(WAUpdateData.PDPS);
                    element.find('input[name="PaymentDatePeriodEnd"]').val(WAUpdateData.PDPE);
                }
            }else{
                self.val(0);
                $('#withAdvancePayement-others').slideUp();
                $('#withAdvancePayement-others').addClass('hide');
                element.find('input[name="minAmount"]').val('');
                element.find('input[name="noOfDays"]').val('');
                element.find('input[name="PaymentDatePeriodEnd"]').val('');
                element.find('input[name="PaymentDatePeriodStart"]').val('');
            } 
        });
        
        $('#hasBOCLI').on('click', function(){
            var self = $(this);
            var element = $('#SFM-AHOCCLIForm');
            var action = element.find('input[name="action"]').val();
            
            if(self.is(':checked')){
                self.val(1);
                $('#hasBOCLI-others').slideDown();
                $('#hasBOCLI-others').removeClass('hide');
            }else{
                self.val(0);
                $('#hasBOCLI-others').slideUp();
                $('#hasBOCLI-others').addClass('hide');
                
                //Reset/hide element options for Basis of CL Increase...
                element.find('input[name="hasBOCLIOption"]').attr('checked',false);
                element.find('input[name="hasBOCLIOptPPOB"]').attr('checked',false);
                element.find('input[name="hasBOCLIValue"]').val('');
                $('#hasBOCLIOption-others').slideUp();
                $('#hasBOCLIOption-others').addClass('hide');
            }
        });
        
        $('input[name="hasBOCLIOption"]').on('click', function(){
            var self = $(this);
            var element = $('#SFM-AHOCCLIForm');
            
            //Percentage of Advance Payment option, to check if "With Advance Payment" was checked condition.
            //Make sure that WAP has values been set before choosing this option, because this would be the basis of it's CL increase
            if(self.val() == 'PAP' && !element.find('#withAdvancePayment').is(':checked')){
                dialogMessage("Sorry, you can't select this unless you have set / checked and fill in fields under \"With Advance Payment\".");
                return false;
            }
            
            if(self.val() == 'PPOB'){
                $('#hasBOCLIOption-others').slideDown();
                $('#hasBOCLIOption-others').removeClass('hide');
            }else{
                element.find('input[name="hasBOCLIOptPPOB"]').attr('checked',false);
                $('#hasBOCLIOption-others').slideUp();
                $('#hasBOCLIOption-others').addClass('hide');
            }
        });
    }
    
    function initElementsAdhocCLGenerator(){
        pageListerMethod = function formSubmitGenerator(start,limit){
                                $.ajax({
                                    cache: false,
                                    type:'POST',
                                    dataType: "json",
                                    data: $('#SFM-GenAdhocCLIForm').serialize() + '&start='+ start +'&end='+ limit +'&fullDetails=' + JSON.stringify(otherObjects),
                                    url: 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php',
                                    beforeSend: function(x) {
                                        if (x && x.overrideMimeType) {
                                            x.overrideMimeType("application/j-son;charset=UTF-8");
                                        }
                                    },
                                    success: prepareResultsAdhocCLILists
                                });
                           }
        
        
        $('#SFM-GenAdhocCLIForm').on('submit',function(){
            otherObjects = $(this).find('select[name="ADHOC-CLC"] option:selected').data('adhoc');
            var selected = $(this).find('select[name="ADHOC-CLC"]').val();
            
            if(selected == ''){ dialogMessage('Select on CL Increase description first.'); return false; }
            
            //If form has selected CLI Description let's proceed..
            $('#btnGenAdhocCLI').hide();
            $('#frmLoader').text('Generation on process, please wait...');
            pageListerMethod(0,list_limit);
            return false;
        });
    }
    
    function prepareResultsAdhocCLILists(response){
        var res = response;
        var html = '';
        
        if(res.results.length > 0){
            for(var x = 0; x < res.results.length; x++){
                
                html+= '<tr class="tbl-td-rows" id="td-row-'+ res.results[x].ID +'">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.results[x].Code +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.results[x].Name +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.results[x].EnrollmentDate +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><input type="checkbox" value="'+ res.results[x].ID +'" name="action_chk[]" class="action_chk" /></td>';
                html+= '</tr>';
            }
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
            $('#btn-for-checkbox').removeClass('hide');
        }else{
            $('.tbl-td-rows td').text(res.message);
        }
        
        //We changed the old dialog message showing....
        $('#btnGenAdhocCLI').show();
        $('#frmLoader').text('');
        
        if(res.total > list_limit) generatePaginationLinks(res.total,list_limit);
        else $('#tblPageNavigation').html('');
    }
    
    function getADHOCParamsOptions(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/adhocCLIncrease_ajax.php',
            type:'POST',
            data: {'action':'get_cl_options'},
            success: frmListsADHOCOptions
        });
    }
    
    function frmListsADHOCOptions(response){
        var res = $.parseJSON(response);
        var html = '',fullDetails = {};
        
        if(res.lists.length > 0){            
            for(var x = 0; x < res.lists.length; x++){
                //Prepare full details of ADHOC criterias, so when in callback file no need to query details again.
               fullDetails = _prepareFullDetails(res.lists[x]);
                
                html+= '<option class="ADHOC-CLC" data-adhoc=\'{'+ fullDetails +'}\' value="'+ res.lists[x].AdhocID +'">'+ res.lists[x].Description +'</option>';
            }
            $('.ADHOC-CLC').remove();
            $('#SFM-GenAdhocCLIForm select[name="ADHOC-CLC"]').append(html);
        }
    }
    
    //Function that will prepare string values for stringify of JS JSON....
    function _prepareFullDetails(data){
        var prepared = '';
        var hasBOCLIOptPPOB = '';
        var hasBOCLIOption = '';
        var hasBOCLIValue = '';
        
        if(data.hasBOCLIOptPPOB != ''){ hasBOCLIOptPPOB = data.hasBOCLIOptPPOB; }
        if(data.hasBOCLIOption != ''){ hasBOCLIOption = data.hasBOCLIOption; }
        if(data.hasBOCLIValue != ''){ hasBOCLIValue = data.hasBOCLIValue; }

        prepared = '"AdhocID":"' + data.AdhocID + '","Description":"'+ data.Description +'","Level":"' + data.Level + '","CreditTermID":"' + data.CreditTermID + 
                '","CreditTypeID":"' + data.CreditTypeID + '","EffectivityDate":"' + data.EffectivityDate + '","MMRFEP":"' + data.MMRFEP + '","NOM_WOUT_PDA":"' + data.NOM_WOUT_PDA +
                '","POPeriodStart":"' + data.POPeriodStart + '","POPeriodEnd":"' + data.POPeriodEnd + '","MinPOAmt":"' + data.MinPOAmt + '","CCLStart":"' + data.CCLStart +
                '","CCLEnd":"' + data.CCLEnd + '","MaxCLInc":"' + data.MaxCLInc + '","WithAdvancePayment":"' + data.WithAdvancePayment + '","WAPMinAmt":"' + data.WAPMinAmt +
                '","WAPNoOfDays":"' + data.WAPNoOfDays + '","WAPPaymentDatePeriodStart":"' + data.WAPPaymentDatePeriodStart + '","WAPPaymentDatePeriodEnd":"' + data.WAPPaymentDatePeriodEnd +
                '","hasBOCLI":"' + data.hasBOCLI + '","hasBOCLIOption":"' + hasBOCLIOption + '","hasBOCLIOptPPOB":"' + hasBOCLIOptPPOB + '","hasBOCLIValue":"' + hasBOCLIValue + '"';
        
        return prepared;
    }
    
    function initSetMultipleFieldsNumericOnly(){
        var fields = ['min_mon_res_fr_eff_period','num_mon_wout_pda','minPOAmt',
                      'CCLEnd','CCLStart','CCLStart','maxCLI','minAmount','noOfDays'];
        for(var x = 0;x < fields.length; x++){
            initNumericOnlyAllowed( '#' + fields[x] );
        }   
    }
    
    function initCheckBoxSetter(element){
        $(element).on('click', function(){
            var self = $(this);

            if(self.is(':checked')) self.val(1);
            else self.val(0);
        });
    }
    
    function getLevelsForSelect(){
        $.ajax({
            url: 'pages/sfm/param_ajax_calls/level_ajax.php',
            type:'POST',
            data: {'action':'get_levels'},
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
            $('#SFM-AHOCCLIForm select[name="SFL"]').append(html);
        }
    }
    
    function radioCheckSetter(element,value){
        element.each(function(){
            if($(this).val() == value){
                $(this).attr('checked',true);
            }
        });
    }
    
    //Function that will set input fields of edit details...
    function setInputsEditDetails(response){
        var res = $.parseJSON(response);
        var element = $('#SFM-AHOCCLIForm');
        
        if(res.status == 'success'){    
            element.find('input[name="editID"]').val(res.data.AdhocID);
            element.find('input[name="description"]').val(res.data.Description);
            element.find('input[name="eDate"]').val(res.data.EffectivityDate);
            element.find('select[name="SFL"]').val(res.data.Level);
            element.find('select[name="CT"]').val(res.data.CreditTermID);
            element.find('select[name="GSUType"]').val(res.data.CreditTypeID);

            element.find('input[name="min_mon_res_fr_eff_period"]').val(res.data.MMRFEP);
            element.find('input[name="num_mon_wout_pda"]').val(res.data.NOM_WOUT_PDA);
            element.find('input[name="poPeriodEnd"]').val(res.data.POPeriodEnd);
            element.find('input[name="poPeriodStart"]').val(res.data.POPeriodStart);
            element.find('input[name="minPOAmt"]').val(res.data.MinPOAmt);
            element.find('input[name="CCLEnd"]').val(res.data.CCLEnd);
            element.find('input[name="CCLStart"]').val(res.data.CCLStart);
            element.find('input[name="maxCLI"]').val(res.data.MaxCLInc);
            
            element.find('input[name="withAdvancePayment"]').val(res.data.WithAdvancePayment);
            if(res.data.WithAdvancePayment == '1'){
                element.find('input[name="withAdvancePayment"]').attr('checked',true);
                $('#withAdvancePayement-others').slideDown();
                $('#withAdvancePayement-others').removeClass('hide');
            }else{
                element.find('input[name="withAdvancePayment"]').attr('checked',false);
                $('#withAdvancePayement-others').slideUp();
                $('#withAdvancePayement-others').addClass('hide');
            }
            
            element.find('input[name="minAmount"]').val(res.data.WAPMinAmt);
            element.find('input[name="noOfDays"]').val(res.data.WAPNoOfDays);
            element.find('input[name="PaymentDatePeriodStart"]').val(res.data.WAPPaymentDatePeriodStart);
            element.find('input[name="PaymentDatePeriodEnd"]').val(res.data.WAPPaymentDatePeriodEnd);
            
            //Store WAP field data, to be used in unchecking and checking of checkbox.
            WAUpdateData.minAmount = res.data.WAPMinAmt;
            WAUpdateData.noOfDays = res.data.WAPNoOfDays;
            WAUpdateData.PDPS = res.data.WAPPaymentDatePeriodStart;
            WAUpdateData.PDPE = res.data.WAPPaymentDatePeriodEnd;
            
            if(res.data.hasBOCLI == '1'){
                element.find('input[name="hasBOCLI"]').attr('checked',true);
                $('#hasBOCLI-others').slideDown();
                $('#hasBOCLI-others').removeClass('hide');
                radioCheckSetter(element.find('input[name="hasBOCLIOption"]'),res.data.hasBOCLIOption);
                
                if(res.data.hasBOCLIOption == 'PPOB'){
                    $('#hasBOCLIOption-others').slideDown();
                    $('#hasBOCLIOption-others').removeClass('hide');
                    radioCheckSetter(element.find('input[name="hasBOCLIOptPPOB"]'),res.data.hasBOCLIOptPPOB);
                }
            }else{ 
                element.find('input[name="hasBOCLI"]').attr('checked',false);
                $('#hasBOCLI-others').slideUp();
                $('#hasBOCLI-others').addClass('hide');
            }
                
            element.find('input[name="hasBOCLI"]').val(res.data.hasBOCLI);
            element.find('input[name="hasBOCLIValue"]').val(res.data.hasBOCLIValue);
            element.find('input[name="action"]').val('update');
            element.find('input[name="frmbtnADHOC"]').val('Update');
            
            $('#dialog-message').dialog("destroy");
        }
    }
    
    //Function for clearing / emptying form fields...
    function clearFields(element){
        element.find('input[name="description"]').val('');
        element.find('input[name="eDate"]').val('');
        element.find('select[name="SFL"]').get(0).selectedIndex = 0;
        element.find('select[name="CT"]').get(0).selectedIndex = 0;
        element.find('select[name="GSUType"]').get(0).selectedIndex = 0;
        
        element.find('input[name="min_mon_res_fr_eff_period"]').val('');
        element.find('input[name="num_mon_wout_pda"]').val('');
        element.find('input[name="poPeriodEnd"]').val('');
        element.find('input[name="poPeriodStart"]').val('');
        element.find('input[name="minPOAmt"]').val('');
        element.find('input[name="CCLEnd"]').val('');
        element.find('input[name="CCLStart"]').val('');
        element.find('input[name="maxCLI"]').val('');
        element.find('input[name="minAmount"]').val('');
        element.find('input[name="noOfDays"]').val('');
        element.find('input[name="PaymentDatePeriodStart"]').val('');
        element.find('input[name="PaymentDatePeriodEnd"]').val('');
        
        WAUpdateData.minAmount = '';
        WAUpdateData.noOfDays = '';
        WAUpdateData.PDPS = '';
        WAUpdateData.PDPE = '';
        
        if(element.find('input[name="withAdvancePayment"]').is(':checked')){
            $('#withAdvancePayement-others').slideUp();
            $('#withAdvancePayement-others').addClass('hide');
        }
        
        element.find('input[name="withAdvancePayment"]').attr('checked',false);
        element.find('input[name="hasBOCLI"]').attr('checked',false);
        element.find('input[name="withAdvancePayment"]').val('0');
        element.find('input[name="hasBOCLI"]').val('0');
        element.find('input[name="hasBOCLIValue"]').val('');
        
        //Reset/hide element options for Basis of CL Increase...
        $('#hasBOCLI-others').slideUp();
        $('#hasBOCLI-others').addClass('hide');
        element.find('input[name="hasBOCLIOption"]').attr('checked',false);
        element.find('input[name="hasBOCLIOptPPOB"]').attr('checked',false);
        $('#hasBOCLIOption-others').slideUp();
        $('#hasBOCLIOption-others').addClass('hide');
        
        element.find('input[name="editID"]').val('');
        element.find('input[name="action"]').val('insert');
        element.find('input[name="frmbtnADHOC"]').val('Continue');
    }
});



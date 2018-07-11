/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: Feb 06, 2013
 * @description: Used in reactivate dealer page.
 */
var pageListerMethod;
var limit = 20;

$(function($){
    documentWriteSFMParamCustoms(); //function to load all default display that is not visible..
    //DOM for link select networks...
    $('#reactivate-select-network').on('click', function(e){
        dialogMessage('Getting IBMs lists, please wait....');
        current_page = 0; //We reset the pagination selected page...
        pageListerMethod(0, limit);
        
        e.preventDefault();
        return false;
    });
    
    //Function that is getting IBMs lists...
    pageListerMethod = function getIBMsSelection(start,limit){
        $.ajax({
            cache: false,
            url: 'pages/ajax_request/sfmIBMsForReActivate.php',
            type: 'POST',
            data: {'action':'lists','start': start,'limit': limit},
            success: listsIBMsSelection
        });
    }
    
    //Function for displaying lists...
    function listsIBMsSelection(response){
        var res = $.parseJSON(response);
        var html = '', html_holder = '';
        var total = 0;
        
        $('#dialog-message').dialog("close");
        
        //Prepare HTML display for popup IBM lists
        html_holder = '<div style="margin-left: auto;margin-right: auto;padding:10px 0;">';
        html_holder+= '<div class="tbl-listing-div" id="IBMs-selection">';        
        html_holder+= '<table border="1" cellspacing="3" cellpadding="3" style="border-collapse: collapse;border-color: #959F63;width: 290px;">';
        html_holder+= '    <tr>';
        html_holder+= '        <th width="20%" class="td-bottom-border">IBM Code</th>';
        html_holder+= '        <th width="40%" class="td-bottom-border">Name</th>';
        html_holder+= '    </tr>';
        html_holder+= '    <tr class="tbl-td-rows">';
        html_holder+= '        <td class="tbl-td-center td-bottom-border" colspan="2">Fetching IBMs lists...</td>';
        html_holder+= '    </tr>';
        html_holder+= '</table>';
        html_holder+= '<div class="tbl-clear clear-small"></div>';
        html_holder+= '<div class="tbl-float-inherit page">';
        html_holder+= '    <div id="tblPageNavigation"></div>';
        html_holder+= '</div>';
        html_holder+= '</div></div>';
        
        //Show the dialog...
        dialogFormListsEtcDisplay('IBMs Selection',html_holder,{ width: '350',position: 'center top' });

        if(res.lists.length > 0){
            for(var x = 0; x < res.lists.length; x++){
                html+= '<tr class="tbl-td-rows">';
                html+= '<td class="tbl-td-center td-bottom-border">'+ res.lists[x].CODE +'</td>';
                html+= '<td class="tbl-td-center td-bottom-border"><a class="bold" href="javascript:void(0);" data-ibm=\'{"id":"'+ res.lists[x].ID +'","code":"'+ res.lists[x].CODE +'","name":"'+ res.lists[x].NAME +'"}\'>'+ res.lists[x].NAME +'</a></td>';
                html+= '</tr>';
            }
            
            total = res.lists[0].total_rows_found;
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
            
            generatePaginationLinks(total,limit); //Generates pagination links or buttons...
        
            paginationActions(limit); //Create the actions for pagination
            prepareIBMsAnchorClick(); //Create the actions for selecting IBM
        }else{
            html = '<tr class="tbl-td-rows">';
            html+= '<td class="tbl-td-center td-bottom-border" colspan="3">'+ res.message +'</td>';
            html+= '</tr>';
            
            $('.tbl-td-rows').remove();
            $('.tbl-listing-div table').append(html);
            $('#tblPageNavigation').html('');
        }
    }
    
    //Function for setting selected IBM...
    function prepareIBMsAnchorClick(){
        $('#IBMs-selection table').on('click','a',function(e){
            var self = $(this);
            var IBMid = self.data('ibm').id;
            var IBMCode = self.data('ibm').code;
            var IBMname = self.data('ibm').name;
            var html = '';
            
            html = '<td class="txt10" align="right">Selected New IBM:<td><td><b>'+ IBMCode + ' - '+ IBMname +'</b></td>';
            
            //Set display and value....
            $('#cboIBMNetwork').val(IBMid);
            $('#selected-IBMName').html(html);
            $('#dialog-message-etc').dialog("close");
            
            e.preventDefault();
        });
    }
    
});

function confirmCancel(){
	if (confirm('Are you sure you want to cancel this transaction?') == false)
		return false;
	else
		return true;
}
function confirmUpdate(){
	var ml = document.frmDealer;
	var mll = document.frmSearchDealer;
	var newibm = ml.cboIBMNetwork;
	var custid = mll.hCustID;
	
	if (custid.value == 0)
	{
		alert ('Select customer first.');
		return false;		
	}
	/*
         * @author: jdymosco
         * @date: March 12, 2013
         * @update: New IBM should be not required as per Ms. Joy.
         *if (newibm.value == 0)
	{
		alert ('Select New IBM No.');
		newibm.focus();
		return false;		
	}*/
	if (confirm('Are you sure you want to reactivate this dealer?') == false)
		return false;
	else
		return true;	
}


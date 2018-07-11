function getSelectionCOAID(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmDealer.hCOA');
	h.value = tmp_val[0];	
	i = eval('document.frmDealer.txtIBMNo');	
	j = eval('document.frmDealer.txtibmname');
	
	i.value = tmp_val[2];
	j.value = tmp_val[1];
	
	//i.focus();
	//i.select();
}
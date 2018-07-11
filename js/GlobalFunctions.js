/*
	@Author: 	 	Gino C. Leabres..
	@Date: 		 	04/07/2014...
	@Description: 	Compile here all global javascript..
*/

// Dynamic Pagination...
function Pagination(pageNum,RPP,num){
		
		/*
			Legend:
				pageNum => PageNumber..
				RRP => TotalView..
				num => Total Records...
		*/
		
		var PrevIc 		=	"images/bprv.gif";
		var FirstIc 	=	"images/bfrst.gif";
		var NextIc		=	"images/bnxt.gif";
		var LastIc		=	"images/blst.gif";
		var dPrevIc		=	"images/dprv.gif";
		var dFirstIc	=	"images/dfrst.gif";
		var dNextIc		=	"images/dnxt.gif";
		var dLastIc		=	"images/dlst.gif";
		var offset = (pageNum - 1) * RPP;
		var nav  = '';
		var page = pageNum - 3;
		var upper = pageNum + 3;
		var maxPage = Math.floor(eval(num)/eval(RPP));
		
		if(num > 0) {	
			if(page <= 0){
				page = 1;
			}
			
			if(upper > maxPage){
				upper = maxPage;
			}
	
			// Make sure there are 7 numbers (3 before, 3 after and current
			if(upper - page < 6){
	
				if(upper >= maxPage){
					 dif = maxPage - page;
					if(dif == 3){
					page = page - 3;
					}else if (dif == 4){
						page = page - 2;
					}else if (dif == 5){
						page = page - 1;
					}
					
				}else if (page <= 1){
					dif = upper-1;
	
					if (dif == 3){
						upper = upper + 3;
					}else if (dif == 4){
						upper = upper + 2;
					}else if (dif == 5){
						upper = upper + 1;
					}
				}
			}
	
			if(page <= 0) {
				page = 1;
			}
	
			if(upper > maxPage) {
				upper = maxPage;
			}
	
			for(page; page <=  upper; page++) {
	
				if(page == pageNum){
					nav += " <font color='red'>"+page+"</font> ";
				}else{
					nav += " <a style='cursor:pointer;' onclick='return showPage("+page+")'>"+page+"</a> ";
				}
			}
			
			if(pageNum > 1){
				page  = pageNum - 1;
				prev  = "<img border='0' src='"+PrevIc+"' onclick='return showPage("+page+")' style='cursor:pointer;'> ";
				first = "<img border='0' src='"+FirstIc+"' onclick='return showPage(1)'  style='cursor:pointer;'> ";
			}else{
				prev  = "<img border='0' src='"+dPrevIc+"'  style='cursor:pointer;'> ";
				first = "<img border='0' src='"+dFirstIc+"'   style='cursor:pointer;'> ";
			}
			
			if(pageNum < maxPage && upper <= maxPage){
				page = pageNum + 1;
				next = " <img border='0' src='"+NextIc+"' style='cursor:pointer;' onclick='return showPage("+page+")'  >";
				last = " <img border='0' src='"+LastIc+"' style='cursor:pointer;' onclick='return showPage("+maxPage+")' >";
			}else{
				next = " <img border='0' src='"+dNextIc+"' style='cursor:pointer;'>";
				last = " <img border='0' src='"+dLastIc+"' style='cursor:pointer;'>";
			}

			if(maxPage >= 1){
				return  first + prev + nav + next + last;
			}
		}
}
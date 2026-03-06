

$(document).ready(function(){





 



// store url for current page as global variable



current_page = document.location.href



 



// apply active states depending on current page



if (current_page.match(/home/)) {



$(".nav  li:eq(0) ").addClass('active');



}else if (current_page.match(/manageusers/)) {



$(".nav  li:eq(1) ").addClass('active');



} 



else if (current_page.match(/exam/) || current_page.match(/manageexams/) || current_page.match(/welcome/) ||  current_page.match(/thankyou/) ||  current_page.match(/create_question/) ||  current_page.match(/question_edit/) ) {



$(".nav  li:eq(2) ").addClass('active');



} 



else if (current_page.match(/checklist/)  ) {



$(".nav  li:eq(3) ").addClass('active');



} 



else if (current_page.match(/report/)) {



$(".nav  li:eq(4) ").addClass('active');



}



else if (current_page.match(/logout/)) {



$(".nav  li:eq(5) ").addClass('active');



} 





 else { 



$(" .nav  li").removeClass('active');



};



 



});












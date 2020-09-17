$("a.nav-link.delete").on("click", function(e){

    e.preventDefault();

    if (confirm("Are you sure?")) {

        let frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('action', $(this).attr('href'));
        frm.appendTo("body");
        frm.submit();

    }
});

/*It's not working as expected. After clicking the delete button and saying yes to the confirmation message,
* I'm still being redirected to the delete file page*/
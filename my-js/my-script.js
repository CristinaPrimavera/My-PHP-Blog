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

//Or with vanilla JS:

// const deleteButton = document.querySelector('.delete');
//
// if (deleteButton) {
//     deleteButton.addEventListener('click', e => {
//         e.preventDefault();
//         if (confirm('Are you sure?')) {
//             const deleteForm = document.createElement('form');
//
//             deleteForm.setAttribute('method', 'post');
//             deleteForm.setAttribute('action', e.target.href);
//             document.body.append(deleteForm);
//             deleteForm.submit();
//         }
//     });
// }



/*It's not working as expected. After clicking the delete button and saying yes to the confirmation message,
* I'm still being redirected to the delete file page*/